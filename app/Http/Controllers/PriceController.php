<?php

namespace App\Http\Controllers;

use App\Models\PublishPackage;
use App\Support\Site;
use Illuminate\View\View;

class PriceController extends Controller
{
    public function index(): View
    {
        return view('prices.index', [
            'packages' => PublishPackage::query()->orderBy('sort_order')->orderBy('id')->get(),
            'note' => Site::get('price_note', ''),
            'included' => $this->lines(Site::get('price_included', '')),
            'specs' => $this->lines(Site::get('price_specs', '')),
        ]);
    }

    private function lines(mixed $text): array
    {
        $lines = preg_split("/\r\n|\n|\r/", trim((string) $text)) ?: [];

        return array_values(array_filter(array_map('trim', $lines), fn ($line) => $line !== ''));
    }
}
