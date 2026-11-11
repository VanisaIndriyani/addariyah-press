<?php

namespace App\Http\Controllers;

use App\Models\Ecatalog;
use App\Support\Site;
use Illuminate\View\View;

class EcatalogController extends Controller
{
    public function index(): View
    {
        $search = Site::query();
        $items = Ecatalog::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('caption', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('ecatalog.index', compact('items', 'search'));
    }
}
