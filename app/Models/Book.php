<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'author',
        'publisher',
        'category',
        'description',
        'pages',
        'isbn',
        'qrcbn',
        'editor',
        'book_layout',
        'cover_designer',
        'price',
        'for_sale',
        'upcoming',
        'expected_label',
        'cover_path',
        'ebook_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'pages' => 'integer',
            'price' => 'decimal:2',
            'for_sale' => 'boolean',
            'upcoming' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSale($query)
    {
        return $query->where('for_sale', true)->whereNotNull('price');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('upcoming', true);
    }
}
