<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_habits', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users & habits
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('habit_id')->constrained()->onDelete('cascade');

            // Target kebiasaan
            $table->integer('target_count')->default(1); // Contoh: 1 kali
            $table->enum('frequency', ['daily', 'weekly'])->default('daily'); // Harian atau Mingguan
            $table->time('reminder_time')->nullable(); // Jam pengingat (notifikasi)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_habits');
    }
};
