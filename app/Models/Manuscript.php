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
        'statement_path',
        'originality_path',
        'cover_path',
        'dummy_path',
        'file_path',
    ];
}
