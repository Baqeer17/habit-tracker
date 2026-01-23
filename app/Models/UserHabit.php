<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHabit extends Model
{
    protected $fillable = [
        'user_id',
        'habit_id',
        'target_count',
        'frequency',
        'reminder_time'
    ];

    // Milik User siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mengambil data Habit aslinya (namanya apa, iconnya apa)
    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    // Punya banyak catatan aktivitas (log)
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
