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
        Schema::create('vestibulinhos', function (Blueprint $table) {
            $table->id();

            // Campos adicionais sugeridos
            $table->year('year')->nullable()->index()->comment('Ano do processo');
            $table->year('reference')->nullable()->index()->comment('Ano de referência do processo');

            $table->boolean('is_active')->default(false)->index()->comment('Calendário ativo');

            // Período de inscrições
            $table->date('inscription_start')->nullable();
            $table->date('inscription_end')->nullable();

            // Exame
            $table->date('exam_location_publish')->nullable();
            $table->date('exam_date')->nullable();

            // Resultados
            $table->date('answer_key_publish')->nullable();
            $table->date('final_result_publish')->nullable();

            // Revisão de prova
            $table->date('exam_revision_start')->nullable();
            $table->date('exam_revision_end')->nullable();

            // Matrículas (1ª chamada)
            $table->date('enrollment_start')->nullable();

            // Matrículas (Vagas Remanescentes)
            $table->date('enrollment_end')->nullable();

            $table->timestamps();

            // Índices para performance em consultas por data
            $table->index('inscription_start');
            $table->index('inscription_end');
            $table->index('exam_date');
            $table->index(['inscription_start', 'inscription_end'], 'idx_inscription_period');
            $table->index(['enrollment_start', 'enrollment_end'], 'idx_enrollment_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vestibulinhos');
    }
};
