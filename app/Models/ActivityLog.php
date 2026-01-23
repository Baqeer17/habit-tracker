<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_habit_id', 'date', 'is_completed', 'notes'];

    // Terhubung ke kebiasaan user yang mana
    public function userHabit()
    {
        return $this->belongsTo(UserHabit::class);
    }
}
