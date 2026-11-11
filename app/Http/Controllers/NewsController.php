<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\Site;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $search = Site::query();
        $posts = Post::query()->published()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->paginate(9)
            ->withQueryString();

        return view('news.index', compact('posts', 'search'));
    }

    public function show(Post $post): View
    {
        abort_unless(
            $post->is_published && ($post->published_at === null || $post->published_at->lte(now())),
            404
        );

        $others = Post::query()->published()->where('id', '!=', $post->id)->take(3)->get();

        return view('news.show', compact('post', 'others'));
    }
}
