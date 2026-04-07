<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Habit;

class HabitSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar habit yang mau dimasukkan
        $habits = [
            [
                'name' => 'Shalat 5 Waktu',
                'description' => 'Menjaga shalat fardhu tepat waktu di masjid (berjamaah)',
                'icon' => 'mosque',
                'status' => 'pending' // Pastikan status default ada
            ],
            [
                'name' => 'Shalat Dhuha',
                'description' => 'Minimal 2 rakaat di pagi hari sebagai sedekah persendian',
                'icon' => 'sun',
                'status' => 'pending'
            ],
            [
                'name' => 'Baca Al-Qur\'an',
                'description' => 'One Day One Juz atau minimal 1 halaman per hari',
                'icon' => 'book-open',
                'status' => 'pending'
            ],
            [
                'name' => 'Dzikir Pagi Petang',
                'description' => 'Membaca Al-Ma\'tsurat',
                'icon' => 'heart',
                'status' => 'pending'
            ]
        ];

        // Loop untuk memasukkan data satu per satu dengan aman
        foreach ($habits as $habit) {
            // Cek berdasarkan 'name', kalau belum ada baru dibuat
            Habit::updateOrCreate(
                ['name' => $habit['name']], 
                $habit
            );
        }
    }
}
