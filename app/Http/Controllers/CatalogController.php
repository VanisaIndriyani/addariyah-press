<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim(str_replace(['%', '_'], '', $request->string('q')->toString()));
        $category = trim($request->string('kategori')->toString());

        $books = Book::query()
            ->active()
            ->forSale()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Book::query()
            ->active()
            ->forSale()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('catalog.index', compact('books', 'categories', 'search', 'category'));
    }
}
