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
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();

            // Demografi Responden
            $table->string('profession', 100);
            $table->string('unit', 100);
            $table->string('status', 50);
            $table->string('tenure', 50);

            // Penilaian Keseluruhan & Kualitatif
            $table->unsignedTinyInteger('overall_score');
            $table->unsignedTinyInteger('nps_score');
            $table->text('like_text')->nullable();
            $table->text('improve_text')->nullable();

            // Skor Hasil Kalkulasi Analitik
            $table->decimal('intrinsic_score', 5, 2)->default(0.00);
            $table->decimal('extrinsic_score', 5, 2)->default(0.00);
            $table->decimal('general_score', 5, 2)->default(0.00);
            $table->decimal('hospital_score', 5, 2)->default(0.00);
            $table->string('nps_category', 20)->default('passive'); // 'promoter', 'passive', 'detractor'

            $table->dateTime('completed_at');
            $table->timestamps();

            // Indeks Optimasi Query Dashboard
            $table->index(['period_id', 'unit']);
            $table->index(['period_id', 'profession']);
            $table->index(['period_id', 'nps_category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
