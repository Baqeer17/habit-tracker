<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuranLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_surah',
        'start_ayat',
        'end_surah',
        'end_ayat',
        'total_ayat',
        'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
