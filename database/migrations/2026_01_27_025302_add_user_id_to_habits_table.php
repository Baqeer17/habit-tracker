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
        Schema::table('habits', function (Blueprint $table) {
            // menambahkan kolom user_id setelah kolom id
            // constrained('users') artinya: kolom ini nyambung ke tabel users
            // onDelete('cascade') artinya: kalau User dihapus, habit-nya ikut terhapus
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};