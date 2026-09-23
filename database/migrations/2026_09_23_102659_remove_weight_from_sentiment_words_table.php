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
        Schema::table('sentiment_words', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sentiment_words', function (Blueprint $table) {
            $table->unsignedTinyInteger('weight')->default(1)->after('sentiment');
        });
    }
};
