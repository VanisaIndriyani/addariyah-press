<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\View\View;

class BookController extends Controller
{
    public function show(Book $book): View
    {
        abort_unless($book->is_active, 404);

        $related = Book::query()
            ->active()
            ->forSale()
            ->where('id', '!=', $book->id)
            ->when($book->category, fn ($query) => $query->where('category', $book->category))
            ->latest()
            ->take(3)
            ->get();

        if ($related->isEmpty()) {
            $related = Book::query()->active()->forSale()->where('id', '!=', $book->id)->latest()->take(3)->get();
        }

        return view('books.show', compact('book', 'related'));
    }
}
