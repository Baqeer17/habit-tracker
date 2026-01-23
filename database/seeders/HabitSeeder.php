<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Habit;

class HabitSeeder extends Seeder
{
    public function run(): void
    {

        Habit::create([
            'name' => 'Shalat 5 Waktu',
            'description' => 'Menjaga shalat fardhu tepat waktu di masjid (berjamaah)',
            'icon' => 'mosque', // Nanti kita pakai icon masjid
        ]);


        Habit::create([
            'name' => 'Shalat Dhuha',
            'description' => 'Minimal 2 rakaat di pagi hari sebagai sedekah persendian',
            'icon' => 'sun', // Icon matahari
        ]);


        Habit::create([
            'name' => 'Baca Al-Qur\'an',
            'description' => 'One Day One Juz atau minimal 1 halaman per hari',
            'icon' => 'book-open', // Icon buku terbuka
        ]);

        // 4. Tambahan (Opsional, biar lengkap)
        Habit::create([
            'name' => 'Dzikir Pagi Petang',
            'description' => 'Membaca Al-Ma\'tsurat',
            'icon' => 'heart',
        ]);
    }
}
