<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublishPackage extends Model
{
    protected $fillable = ['title', 'tables', 'sort_order'];

    protected function casts(): array
    {
        return [
            'tables' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public static function blankTables(): array
    {
        return [[
            'paper' => 'Book Paper 70 gsm',
            'columns' => ['150-199 halaman', '200-250 halaman', '251-300 halaman'],
            'rows' => [
                ['oplah' => '20 Eksemplar', 'prices' => ['', '', '']],
                ['oplah' => '30 Eksemplar', 'prices' => ['', '', '']],
                ['oplah' => '50 Eksemplar', 'prices' => ['', '', '']],
                ['oplah' => '100 Eksemplar', 'prices' => ['', '', '']],
            ],
        ]];
    }
}
