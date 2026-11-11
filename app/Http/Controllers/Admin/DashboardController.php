<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Ecatalog;
use App\Models\Manuscript;
use App\Models\Post;
use App\Support\Site;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'bookCount' => Book::query()->count(),
            'catalogCount' => Book::query()->forSale()->count(),
            'upcomingCount' => Book::query()->upcoming()->count(),
            'postCount' => Post::query()->count(),
            'manuscriptCount' => Manuscript::query()->count(),
            'ecatalogCount' => Ecatalog::query()->count(),
            'latestManuscripts' => Manuscript::query()->latest()->take(5)->get(),
            'needsQris' => ! Site::qrisReady(),
            'needsWhatsapp' => Site::waNumber() === '' || Site::waNumber() === '6281234567890',
        ]);
    }
}
