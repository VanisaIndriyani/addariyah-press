<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\Site;
use App\Support\Uploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $search = Site::query();
        $posts = Post::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'search'));
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post(['is_published' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $post = Post::query()->create($this->payload($request, new Post));

        return redirect()->route('admin.berita.edit', $post)->with('success', 'Berita berhasil disimpan.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $post->update($this->payload($request, $post));

        return redirect()->route('admin.berita.edit', $post)->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Uploader::deletePublic($post->image_path);
        $post->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita dihapus.');
    }

    private function payload(Request $request, Post $post): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'excerpt' => ['nullable', 'string', 'max:240'],
            'body' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $excerpt = $data['excerpt'] ?? '';
        if (trim($excerpt) === '') {
            $excerpt = Str::limit(trim(preg_replace('/\s+/', ' ', $data['body']) ?? ''), 160);
        }

        $payload = [
            'title' => $data['title'],
            'slug' => Site::uniqueSlug(Post::class, $data['title'], $post->id),
            'excerpt' => $excerpt,
            'body' => $data['body'],
            'is_published' => $request->boolean('is_published'),
            'published_at' => $data['published_at'] ?? now(),
        ];

        if ($request->hasFile('image')) {
            Uploader::deletePublic($post->image_path);
            $payload['image_path'] = Uploader::publicFile($request->file('image'), 'news');
        }

        return $payload;
    }
}
