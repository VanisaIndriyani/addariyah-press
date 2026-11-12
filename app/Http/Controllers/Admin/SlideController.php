<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Support\Site;
use App\Support\Uploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SlideController extends Controller
{
    public function index(): View
    {
        $search = Site::query();
        $items = Slide::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('caption', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.slides.index', compact('items', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titles' => ['nullable', 'array'],
            'titles.*' => ['nullable', 'string', 'max:160'],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:240'],
            'images' => ['required', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $images = $request->file('images', []);
        $saved = 0;
        $sort = (int) Slide::query()->max('sort_order');

        foreach ($images as $index => $image) {
            if (! $image) {
                continue;
            }

            $sort++;
            $saved++;
            Slide::query()->create([
                'title' => trim((string) ($data['titles'][$index] ?? '')) ?: 'Sertifikat',
                'caption' => trim((string) ($data['captions'][$index] ?? '')) ?: null,
                'image_path' => Uploader::publicFile($image, 'slides'),
                'sort_order' => $sort,
            ]);
        }

        if ($saved === 0) {
            return back()
                ->withErrors(['images' => 'Unggah minimal satu gambar.'])
                ->withInput();
        }

        return back()->with('success', $saved.' gambar slide berhasil diunggah.');
    }

    public function update(Request $request, Slide $slide): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:160'],
            'caption' => ['nullable', 'string', 'max:240'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $payload = [
            'title' => trim((string) ($data['title'] ?? '')) ?: 'Sertifikat',
            'caption' => trim((string) ($data['caption'] ?? '')) ?: null,
            'sort_order' => $data['sort_order'] ?? $slide->sort_order,
        ];

        if ($request->hasFile('image')) {
            Uploader::deletePublic($slide->image_path);
            $payload['image_path'] = Uploader::publicFile($request->file('image'), 'slides');
        }

        $slide->update($payload);

        return back()->with('success', 'Gambar slide diperbarui.');
    }

    public function destroy(Slide $slide): RedirectResponse
    {
        Uploader::deletePublic($slide->image_path);
        $slide->delete();

        return back()->with('success', 'Gambar slide dihapus.');
    }
}
