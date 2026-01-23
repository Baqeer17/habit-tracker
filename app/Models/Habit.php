<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    // Kolom yang boleh diisi manual
    protected $fillable = ['name', 'description', 'icon'];

    // Relasi: Satu kebiasaan bisa diambil oleh banyak user
    public function userHabits()
    {
        return $this->hasMany(UserHabit::class);
    }
}
