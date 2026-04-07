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
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('phone')->nullable()->after('bio');
            $table->string('gender')->nullable()->after('phone');
            $table->string('location_name')->nullable()->after('longitude');
            $table->string('language')->default('id')->after('location_name');
            $table->string('theme')->default('Otomatis')->after('language');
            $table->integer('font_size')->default(3)->after('theme');
            $table->boolean('azan_notification')->default(true)->after('font_size');
            $table->boolean('silent_mode')->default(false)->after('azan_notification');
            $table->string('tahajud_time')->default('03:30')->after('silent_mode');
            $table->string('duha_time')->default('08:20')->after('tahajud_time');
            $table->string('tilawah_time')->default('18:22')->after('duha_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar', 'bio', 'phone', 'gender', 'location_name', 
                'language', 'theme', 'font_size', 'azan_notification', 
                'silent_mode', 'tahajud_time', 'duha_time', 'tilawah_time'
            ]);
        });
    }
};
