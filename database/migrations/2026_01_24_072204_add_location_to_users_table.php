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
        Schema::table('users', function (Blueprint $table) {
        // Menambah kolom koordinat GPS
        // 10,8 artinya ada 10 angka total, dengan 8 angka di belakang koma (sangat akurat)
        $table->decimal('latitude', 10, 8)->nullable(); 
        $table->decimal('longitude', 11, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
