<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'catalog' => Book::query()->active()->forSale()->latest()->take(4)->get(),
            'upcoming' => Book::query()->active()->upcoming()->latest()->take(3)->get(),
            'posts' => Post::query()->published()->take(3)->get(),
            'heroBooks' => Book::query()->active()->where(function ($query) {
                $query->where('for_sale', true)->orWhere('upcoming', true);
            })->latest()->take(3)->get(),
        ]);
    }
}
