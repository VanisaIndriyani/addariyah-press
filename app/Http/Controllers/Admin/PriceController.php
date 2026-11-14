<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublishPackage;
use App\Models\Setting;
use App\Support\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PriceController extends Controller
{
    public function index(): View
    {
        return view('admin.prices.index', [
            'packages' => PublishPackage::query()->orderBy('sort_order')->orderBy('id')->get(),
            'note' => Site::get('price_note', ''),
            'included' => Site::get('price_included', ''),
            'specs' => Site::get('price_specs', ''),
        ]);
    }

    public function updateNotes(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'price_note' => ['nullable', 'string', 'max:160'],
            'price_included' => ['nullable', 'string', 'max:2000'],
            'price_specs' => ['nullable', 'string', 'max:2000'],
        ]);

        Setting::setValue('price_note', $data['price_note'] ?? '');
        Setting::setValue('price_included', $data['price_included'] ?? '');
        Setting::setValue('price_specs', $data['price_specs'] ?? '');

        return back()->with('success', 'Keterangan harga disimpan.');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
        ]);

        $package = PublishPackage::query()->create([
            'title' => $data['title'],
            'tables' => PublishPackage::blankTables(),
            'sort_order' => (int) PublishPackage::query()->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.prices.edit', $package)->with('success', 'Paket ditambahkan. Isi tabel harganya.');
    }

    public function edit(PublishPackage $package): View
    {
        return view('admin.prices.edit', [
            'package' => $package,
            'tables' => old('papers', $package->tables ?: PublishPackage::blankTables()),
        ]);
    }

    public function update(Request $request, PublishPackage $package): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'papers' => ['nullable', 'array', 'max:8'],
            'papers.*.paper' => ['nullable', 'string', 'max:120'],
            'papers.*.columns' => ['nullable', 'array', 'max:4'],
            'papers.*.columns.*' => ['nullable', 'string', 'max:80'],
            'papers.*.rows' => ['nullable', 'array', 'max:12'],
            'papers.*.rows.*.oplah' => ['nullable', 'string', 'max:80'],
            'papers.*.rows.*.prices' => ['nullable', 'array', 'max:4'],
            'papers.*.rows.*.prices.*' => ['nullable', 'string', 'max:20'],
        ]);

        $package->update([
            'title' => $data['title'],
            'sort_order' => (int) ($data['sort_order'] ?? $package->sort_order),
            'tables' => $this->tables($data['papers'] ?? []),
        ]);

        return redirect()->route('admin.prices.edit', $package)->with('success', 'Tabel harga disimpan.');
    }

    public function destroy(PublishPackage $package): RedirectResponse
    {
        $package->delete();

        return redirect()->route('admin.prices.index')->with('success', 'Paket dihapus.');
    }

    private function tables(array $papers): array
    {
        $tables = [];

        foreach ($papers as $paper) {
            $columns = [];
            foreach ($paper['columns'] ?? [] as $column) {
                $column = trim((string) $column);
                if ($column !== '') {
                    $columns[] = $column;
                }
            }
            if ($columns === []) {
                continue;
            }

            $rows = [];
            foreach ($paper['rows'] ?? [] as $row) {
                $oplah = trim((string) ($row['oplah'] ?? ''));
                if ($oplah === '') {
                    continue;
                }
                $prices = [];
                foreach (array_values($columns) as $index => $column) {
                    $digits = preg_replace('/\D+/', '', (string) ($row['prices'][$index] ?? '')) ?? '';
                    $prices[] = $digits === '' ? null : (int) $digits;
                }
                $rows[] = ['oplah' => $oplah, 'prices' => $prices];
            }

            $tables[] = [
                'paper' => trim((string) ($paper['paper'] ?? '')) ?: 'Kertas',
                'columns' => array_values($columns),
                'rows' => $rows,
            ];
        }

        return $tables;
    }
}
