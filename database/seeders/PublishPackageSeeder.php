<?php

namespace Database\Seeders;

use App\Models\PublishPackage;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class PublishPackageSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setValue('price_note', 'Contoh harga untuk melihat tampilan. Ganti lewat admin.');
        Setting::setValue('price_included', implode("\n", [
            'ISBN',
            'Penyuntingan',
            'Tata letak',
            'Desain cover',
            'Proofreading',
        ]));
        Setting::setValue('price_specs', implode("\n", [
            'Ukuran buku 15 x 23 cm',
            'Cover art carton',
            'Jilid lem dan shrink',
        ]));

        $packages = [
            [
                'title' => 'Cetak hitam putih',
                'sort_order' => 1,
                'tables' => [
                    $this->table('Book Paper 70 gsm', 2500000),
                    $this->table('HVS 80 gsm', 2400000),
                ],
            ],
            [
                'title' => 'Cetak full colour 10%',
                'sort_order' => 2,
                'tables' => [
                    $this->table('Book Paper 70 gsm', 3200000),
                    $this->table('HVS 80 gsm', 3100000),
                ],
            ],
        ];

        foreach ($packages as $package) {
            PublishPackage::query()->updateOrCreate(
                ['title' => $package['title']],
                $package
            );
        }
    }

    private function table(string $paper, int $start): array
    {
        $rows = [];
        foreach ([20, 30, 50, 100] as $index => $oplah) {
            $base = $start + ($index * 450000);
            $rows[] = [
                'oplah' => $oplah.' Eksemplar',
                'prices' => [$base, $base + 350000, $base + 700000],
            ];
        }

        return [
            'paper' => $paper,
            'columns' => ['150-199 halaman', '200-250 halaman', '251-300 halaman'],
            'rows' => $rows,
        ];
    }
}
