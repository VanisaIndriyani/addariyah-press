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
        return view('manuscript.create');
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
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,rtf', 'max:10240'],
        ]);

        Manuscript::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'title' => $data['title'],
            'category' => $data['category'] ?? null,
            'message' => $data['message'] ?? null,
            'file_path' => Uploader::privateFile($request->file('file'), 'naskah'),
        ]);

        return redirect()
            ->route('manuscripts.create')
            ->with('success', 'Naskah terkirim. Tim Addariyah Press akan meninjau kiriman Anda.');
    }
}
