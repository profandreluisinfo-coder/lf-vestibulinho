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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('selection_process_id')->constrained('selection_processes')->onDelete('cascade');

            // Período de inscrições
            $table->date('start')->nullable();
            $table->date('end')->nullable();

            // Exame
            $table->date('location_publish')->nullable();
            $table->date('exam_date')->nullable();

            // Resultados
            $table->date('answer_publish')->nullable();
            $table->date('result_publish')->nullable();

            // Revisão de prova
            $table->date('revision_start')->nullable();
            $table->date('revision_end')->nullable();

            // Matrículas (1ª chamada)
            $table->date('enrol_start')->nullable();
            $table->date('enrol_remaining')->nullable();

            $table->timestamps();

            // Índices para performance em consultas por data
            $table->index('start');
            $table->index('end');
            $table->index('exam_date');
            $table->index(['start', 'end'], 'idx_period');
            $table->index(['enrol_start', 'enrol_remaining'], 'idx_enrol_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};