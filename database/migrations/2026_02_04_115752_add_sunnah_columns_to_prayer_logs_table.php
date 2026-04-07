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
            $table->boolean('q_subuh')->default(false)->after('date'); 
            $table->boolean('q_dhuhr')->default(false)->after('fajr'); 
            $table->boolean('b_dhuhr')->default(false)->after('q_dhuhr');
            $table->boolean('b_maghrib')->default(false)->after('maghrib');
            $table->boolean('q_isha')->default(false)->after('isha');
            $table->boolean('b_isha')->default(false)->after('q_isha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayer_logs', function (Blueprint $table) {
            $table->dropColumn(['q_subuh', 'q_dhuhr', 'b_dhuhr', 'b_maghrib', 'q_isha', 'b_isha']);
        });
    }
};
