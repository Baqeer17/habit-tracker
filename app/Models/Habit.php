<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi:
     * - user_id: Siapa pemiliknya (PENTING!)
     * - status: Apakah sudah dikerjakan?
     */
    protected $fillable = [
        'user_id', 
        'name',
        'description',
        'icon',
        'status',
    ];

    /**
     * Relasi Baru:
     * Kebiasaan ini MILIK satu User saja.
     * (Kebalikan dari hasMany di User.php tadi)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}