<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Library;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Library::create([
            'title' => 'Panduan Shalat Lengkap',
            'author' => 'Ustadz Baqeer',
            'category' => 'hadis',
            'cover_url' => 'https://via.placeholder.com/300x400?text=Panduan+Shalat',
            'source_url' => 'https://example.com/panduan-shalat',
            'description' => 'Tata cara shalat yang benar sesuai dengan sunnah Nabi SAW.',
        ]);

        Library::create([
            'title' => 'Doa Harian Muslim',
            'author' => 'Tim Mahabba',
            'category' => 'dzikir',
            'cover_url' => 'https://via.placeholder.com/300x400?text=Doa+Harian',
            'source_url' => 'https://example.com/doa-harian',
            'description' => 'Koleksi doa-doa harian mulai dari bangun tidur hingga tidur kembali.',
        ]);

        Library::create([
            'title' => 'Rukun Islam Dasar',
            'author' => 'Tim Mahabba',
            'category' => 'hadis',
            'cover_url' => 'https://via.placeholder.com/300x400?text=Rukun+Islam',
            'source_url' => 'https://example.com/rukun-islam',
            'description' => 'Penjelasan mendalam mengenai 5 pilar utama dalam agama Islam.',
        ]);
    }
}

