<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Manuscript;
use App\Support\Site;
use App\Support\Uploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManuscriptPageController extends Controller
{
    public function index(): View
    {
        return view('manuscript.index');
    }

    public function guide(): View
    {
        $search = Site::query();
        $files = $this->downloads('pedoman', $search);

        return view('manuscript.guide', compact('files', 'search'));
    }

    public function templates(): View
    {
        $search = Site::query();
        $files = $this->downloads('template', $search);

        return view('manuscript.templates', compact('files', 'search'));
    }

    private function downloads(string $type, string $search)
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

    public function create(): View
    {
        return view('manuscript.create', [
            'sampleStatement' => Site::get('sample_statement', ''),
            'sampleOriginality' => Site::get('sample_originality', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect()->route('manuscripts.create')->with('success', 'Naskah terkirim. Tim Addariyah Press akan meninjau kiriman Anda.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'title' => ['required', 'string', 'max:180'],
            'category' => ['nullable', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:3000'],
            'statement' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'originality' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'cover' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'dummy' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'book' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
        ]);

        Manuscript::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'title' => $data['title'],
            'category' => $data['category'] ?? null,
            'message' => $data['message'] ?? null,
            'statement_path' => Uploader::privateFile($request->file('statement'), 'naskah'),
            'originality_path' => Uploader::privateFile($request->file('originality'), 'naskah'),
            'cover_path' => Uploader::privateFile($request->file('cover'), 'naskah'),
            'dummy_path' => Uploader::privateFile($request->file('dummy'), 'naskah'),
            'file_path' => Uploader::privateFile($request->file('book'), 'naskah'),
        ]);

        return redirect()
            ->route('manuscripts.create')
            ->with('success', 'Naskah terkirim. Tim Addariyah Press akan meninjau kiriman Anda.');
    }
}
