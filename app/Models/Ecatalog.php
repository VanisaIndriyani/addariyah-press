<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecatalog extends Model
{
    protected $fillable = ['title', 'caption', 'image_path', 'sort_order'];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
