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
        'message',
        'file_path',
    ];
}
