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
            $table->string('directorate', 150)->nullable();
            $table->string('unit', 100);
            $table->string('profession', 100);
            $table->string('status', 50);
            $table->string('tenure', 50);
            $table->string('age', 50)->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('education', 100)->nullable();
            $table->string('income', 100)->nullable();
            $table->string('children', 50)->nullable();

            // Penilaian & Kualitatif
            $table->unsignedTinyInteger('nps_score');
            $table->text('like_text')->nullable();
            $table->text('improve_text')->nullable();
            $table->json('feedback_data')->nullable(); // Rincian alasan & saran terstruktur per indikator

            // Indeks Kepuasan Pegawai (0.00 - 100.00) & NPS
            $table->decimal('general_score', 5, 2)->default(0.00);
            $table->string('nps_category', 20)->default('passive'); // 'promoter', 'passive', 'detractor'

            $table->dateTime('completed_at');
            $table->timestamps();

            // Indeks Optimasi Query Dashboard
            $table->index(['period_id', 'directorate']);
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
