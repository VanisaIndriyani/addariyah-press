<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use App\Support\Site;
use App\Support\Uploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ManuscriptController extends Controller
{
    public function index(): View
    {
        $search = Site::query();
        $manuscripts = Manuscript::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.manuscripts.index', compact('manuscripts', 'search'));
    }

    public function show(Manuscript $manuscript): View
    {
        return view('admin.manuscripts.show', compact('manuscript'));
    }

    public function file(Manuscript $manuscript, string $kind): BinaryFileResponse
    {
        $column = [
            'pernyataan' => 'statement_path',
            'keaslian' => 'originality_path',
            'cover' => 'cover_path',
            'dummy' => 'dummy_path',
            'buku' => 'file_path',
        ][$kind] ?? null;

        abort_unless($column, 404);

        $path = Uploader::privatePath($manuscript->{$column});
        abort_unless($path, 404);

        return response()->download($path, $kind.'-'.basename($path));
    }

    public function destroy(Manuscript $manuscript): RedirectResponse
    {
        foreach (['statement_path', 'originality_path', 'cover_path', 'dummy_path', 'file_path'] as $column) {
            Uploader::deletePrivate($manuscript->{$column});
        }
        $manuscript->delete();

        return redirect()->route('admin.manuscripts.index')->with('success', 'Naskah dihapus.');
    }
}
