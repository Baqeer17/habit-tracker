<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $fillable = [
        'title',
        'author',
        'category',
        'cover_url',
        'source_url',
        'description',
    ];
}

