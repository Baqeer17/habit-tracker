<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DzikirLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'date', 
        'pagi_completed', 'pagi_details',
        'petang_completed', 'petang_details',
        'salat_completed', 'salat_details',
    ];

    protected $casts = [
        'pagi_completed' => 'boolean',
        'petang_completed' => 'boolean',
        'salat_completed' => 'boolean',
        'pagi_details' => 'array',
        'petang_details' => 'array',
        'salat_details' => 'array',
    ];
}
