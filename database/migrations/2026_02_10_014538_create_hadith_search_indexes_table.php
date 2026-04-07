<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('hadith_search_indexes', function (Blueprint $table) {
            $table->id();
            $table->string('narrator')->index(); // e.g. 'bukhari'
            $table->integer('number');
            $table->text('content'); // Indonesian translation
            $table->text('arabic')->nullable();
            $table->timestamps();

            // Composite index for specific lookup
            $table->index(['narrator', 'number']);
            // We can add a raw GIN index here if needed later, but for now standard text is fine.
        });
    }

    public function down()
    {
        Schema::dropIfExists('hadith_search_indexes');
    }
};
