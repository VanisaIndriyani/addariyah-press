<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Support\Site;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function index(): View
    {
        $search = Site::query();
        $books = Book::query()->active()->upcoming()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('publications.index', compact('books', 'search'));
    }
}
