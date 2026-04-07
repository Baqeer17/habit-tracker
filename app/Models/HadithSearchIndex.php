<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HadithSearchIndex extends Model
{
    protected $table = 'hadith_search_indexes';
    protected $fillable = ['narrator', 'number', 'content', 'arabic'];
}
