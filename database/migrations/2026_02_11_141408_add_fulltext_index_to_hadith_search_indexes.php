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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE hadith_search_indexes ADD COLUMN search_vector tsvector GENERATED ALWAYS AS (to_tsvector('indonesian', content)) STORED");
        \Illuminate\Support\Facades\DB::statement("CREATE INDEX hadith_search_indexes_search_vector_gin ON hadith_search_indexes USING GIN(search_vector)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("DROP INDEX IF EXISTS hadith_search_indexes_search_vector_gin");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE hadith_search_indexes DROP COLUMN IF EXISTS search_vector");
    }
};
