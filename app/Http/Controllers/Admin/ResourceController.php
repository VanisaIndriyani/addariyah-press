<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Setting;
use App\Support\Site;
use App\Support\Uploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(): View
    {
        $search = Site::query();

        return view('admin.resources.index', [
            'pedoman' => Site::get('pedoman', ''),
            'guides' => $this->files('pedoman', $search),
            'templates' => $this->files('template', $search),
            'search' => $search,
            'sampleStatement' => Site::get('sample_statement', ''),
            'sampleOriginality' => Site::get('sample_originality', ''),
        ]);
    }

    public function updateSamples(Request $request): RedirectResponse
    {
        $request->validate([
            'sample_statement' => ['nullable', 'file', 'mimes:pdf,doc,docx,rtf', 'max:10240'],
            'sample_originality' => ['nullable', 'file', 'mimes:pdf,doc,docx,rtf', 'max:10240'],
        ]);

        if (! $request->hasFile('sample_statement') && ! $request->hasFile('sample_originality')) {
            return back()->withErrors(['sample_statement' => 'Pilih minimal satu contoh surat.'])->withInput();
        }

        foreach (['sample_statement', 'sample_originality'] as $key) {
            if (! $request->hasFile($key)) {
                continue;
            }

            $old = (string) Setting::getValue($key, '');
            if ($old !== '') {
                Uploader::deletePublic($old);
            }

            Setting::setValue($key, Uploader::publicFile($request->file($key), 'samples'));
        }

        return back()->with('success', 'Contoh surat disimpan.');
    }

    private function files(string $type, string $search)
    {
        return Download::query()
            ->where('type', $type)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();
    }

    public function updateGuide(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pedoman' => ['nullable', 'string'],
        ]);

        Setting::setValue('pedoman', $data['pedoman'] ?? '');

        return back()->with('success', 'Pedoman penulisan disimpan.');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:pedoman,template'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:500'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,rtf,txt', 'max:10240'],
        ]);

        Download::query()->create([
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_path' => Uploader::publicFile($request->file('file'), 'resources'),
        ]);

        return back()->with('success', 'Berkas berhasil diunggah.');
    }

    public function update(Request $request, Download $download): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:pedoman,template'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:500'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,rtf,txt', 'max:10240'],
        ]);

        $payload = [
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ];

        if ($request->hasFile('file')) {
            Uploader::deletePublic($download->file_path);
            $payload['file_path'] = Uploader::publicFile($request->file('file'), 'resources');
        }

        $download->update($payload);

        return back()->with('success', 'Berkas diperbarui.');
    }

    public function destroy(Download $download): RedirectResponse
    {
        Uploader::deletePublic($download->file_path);
        $download->delete();

        return back()->with('success', 'Berkas dihapus.');
    }
}
