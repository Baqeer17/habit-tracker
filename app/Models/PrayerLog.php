<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahan opsional, tapi bagus ada
use Illuminate\Database\Eloquent\Model;

class PrayerLog extends Model
{
    use HasFactory;

    // 1. Ini KUNCI agar data bisa disimpan (Auto-Save)
    protected $fillable = [
        'user_id',
        'date',
        'fajr',
        'dhuhr',
        'asr',
        'maghrib',
        'isha',
        'q_subuh', 'q_dhuhr', 'b_dhuhr', 'b_maghrib', 'q_isha', 'b_isha',
        'dhuha', 'tahajud', 'witir'
    ];

    // 2. Ini agar data keluar sebagai Tipe Data yang benar
    protected $casts = [
        'date' => 'date',       // Otomatis jadi Carbon (Tanggal)
        'fajr' => 'boolean',    // Otomatis jadi True/False
        'dhuhr' => 'boolean',
        'asr' => 'boolean',
        'maghrib' => 'boolean',
        'isha' => 'boolean',
        'q_subuh' => 'boolean',
        'q_dhuhr' => 'boolean',
        'b_dhuhr' => 'boolean',
        'b_maghrib' => 'boolean',
        'q_isha' => 'boolean',
        'b_isha' => 'boolean',
        'dhuha' => 'boolean',
        'tahajud' => 'boolean',
        'witir' => 'boolean',
    ];
}