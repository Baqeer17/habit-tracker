<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'latitude',
        'longitude',
        'google_id',
        'avatar',
        'bio',
        'phone',
        'gender',
        'location_name',
        'language',
        'theme',
        'font_size',
        'azan_notification',
        'silent_mode',
        'tahajud_time',
        'duha_time',
        'tilawah_time',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: Satu User memiliki BANYAK Habit.
     * Ini kuncinya agar kita bisa memanggil $user->habits
     */
    public function habits()
    {
        return $this->hasMany(Habit::class);
    }
}