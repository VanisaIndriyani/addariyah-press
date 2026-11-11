<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Support\Cart as CartBag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index', [
            'lines' => CartBag::lines(),
            'total' => CartBag::total(),
        ]);
    }

    public function store(Request $request, Book $book): RedirectResponse
    {
        abort_unless($book->is_active && $book->for_sale && $book->price !== null, 404);

        $qty = max(1, min(10, (int) $request->input('qty', 1)));
        CartBag::add($book->id, $qty);

        return redirect()->route('cart')->with('success', 'Buku masuk ke keranjang.');
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        CartBag::update($book->id, (int) $data['qty']);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        CartBag::update($book->id, 0);

        return back()->with('success', 'Buku dikeluarkan dari keranjang.');
    }
}
