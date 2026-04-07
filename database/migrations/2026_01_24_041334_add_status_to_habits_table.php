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
            // Tambahkan baris ini untuk membuat kolom status
            $table->string('status')->default('pending'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            // Tambahkan baris ini untuk menghapus kolom jika migrasi di-rollback
            $table->dropColumn('status');
        });
    }
};
