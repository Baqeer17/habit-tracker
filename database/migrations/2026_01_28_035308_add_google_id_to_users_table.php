<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambah kolom google_id setelah email
            $table->string('google_id')->nullable()->after('email');
            // Mengubah password jadi boleh kosong (opsional, tapi disarankan)
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
            // Kembalikan password jadi wajib diisi
            $table->string('password')->nullable(false)->change();
        });
    }
};