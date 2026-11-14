<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manuscript extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'title',
        'category',
        'abstract',
        'page_count',
        'message',
        'statement_path',
        'originality_path',
        'cover_path',
        'dummy_path',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'page_count' => 'integer',
        ];
    }
}
