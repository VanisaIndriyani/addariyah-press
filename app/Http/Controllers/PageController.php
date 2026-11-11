<?php

namespace App\Http\Controllers;

use App\Support\Site;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about', [
            'about' => Site::get('about', ''),
        ]);
    }
}
