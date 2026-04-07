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
        Schema::create('login_counters', function (Blueprint $table) {
            $table->id();
            $table->date('login_date')->unique(); // Tanggal hari ini
            $table->integer('total_count')->default(0); // Hitungan login
            $table->timestamps();
        }); // <-- Tadi kurang penutup kurung kurawal di sini
    } // <-- Dan kurang penutup fungsi di sini

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_counters');
    }
};