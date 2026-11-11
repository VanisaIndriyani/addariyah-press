<?php

namespace App\Support;

use App\Models\Book;
use Illuminate\Support\Collection;

class Cart
{
    public static function raw(): array
    {
        $cart = session('cart', []);

        return is_array($cart) ? $cart : [];
    }

    public static function lines(): Collection
    {
        $raw = self::raw();
        if ($raw === []) {
            return collect();
        }

        $books = Book::query()
            ->whereIn('id', array_keys($raw))
            ->where('is_active', true)
            ->where('for_sale', true)
            ->whereNotNull('price')
            ->get()
            ->keyBy('id');

        return collect($raw)->map(function ($qty, $id) use ($books) {
            $book = $books->get((int) $id);
            if (! $book) {
                return null;
            }

            $qty = max(1, min(10, (int) $qty));

            return [
                'book' => $book,
                'qty' => $qty,
                'subtotal' => (float) $book->price * $qty,
            ];
        })->filter()->values();
    }

    public static function count(): int
    {
        return (int) self::lines()->sum('qty');
    }

    public static function total(): float
    {
        return (float) self::lines()->sum('subtotal');
    }

    public static function add(int $id, int $qty = 1): void
    {
        $cart = self::raw();
        $cart[$id] = min(10, (int) ($cart[$id] ?? 0) + max(1, $qty));
        session(['cart' => $cart]);
    }

    public static function update(int $id, int $qty): void
    {
        $cart = self::raw();
        if ($qty <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = min(10, $qty);
        }
        session(['cart' => $cart]);
    }

    public static function clear(): void
    {
        session()->forget('cart');
    }
}
