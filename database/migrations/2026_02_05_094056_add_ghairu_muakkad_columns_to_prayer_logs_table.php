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
        Schema::table('prayer_logs', function (Blueprint $table) {
            $table->boolean('dhuha')->default(false)->after('b_isha');
            $table->boolean('tahajud')->default(false)->after('dhuha');
            $table->boolean('witir')->default(false)->after('tahajud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayer_logs', function (Blueprint $table) {
            $table->dropColumn(['dhuha', 'tahajud', 'witir']);
        });
    }
};
