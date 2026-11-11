<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecatalog;
use App\Support\Site;
use App\Support\Uploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EcatalogController extends Controller
{
    public function index(): View
    {
        $search = Site::query();
        $items = Ecatalog::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('caption', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.ecatalogs.index', compact('items', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:160'],
            'caption' => ['nullable', 'string', 'max:240'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $sort = (int) Ecatalog::query()->max('sort_order');
        $title = $request->input('title') ?: 'Biaya penerbitan';

        foreach ($request->file('images', []) as $image) {
            $sort++;
            Ecatalog::query()->create([
                'title' => $title,
                'caption' => $request->input('caption'),
                'image_path' => Uploader::publicFile($image, 'ecatalog'),
                'sort_order' => $sort,
            ]);
        }

        return back()->with('success', 'Gambar e-catalog berhasil diunggah.');
    }

    public function update(Request $request, Ecatalog $ecatalog): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:160'],
            'caption' => ['nullable', 'string', 'max:240'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $payload = [
            'title' => $data['title'] ?: 'Biaya penerbitan',
            'caption' => $data['caption'] ?? null,
            'sort_order' => $data['sort_order'] ?? $ecatalog->sort_order,
        ];

        if ($request->hasFile('image')) {
            Uploader::deletePublic($ecatalog->image_path);
            $payload['image_path'] = Uploader::publicFile($request->file('image'), 'ecatalog');
        }

        $ecatalog->update($payload);

        return back()->with('success', 'Gambar e-catalog diperbarui.');
    }

    public function destroy(Ecatalog $ecatalog): RedirectResponse
    {
        Uploader::deletePublic($ecatalog->image_path);
        $ecatalog->delete();

        return back()->with('success', 'Gambar dihapus.');
    }
}
