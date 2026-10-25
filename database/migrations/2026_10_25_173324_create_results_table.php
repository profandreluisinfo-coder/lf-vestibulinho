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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            // Inscrição vinculada ao candidato
            $table->foreignId('inscription_id')->constrained('inscriptions')->onDelete('cascade');

            // Dados da prova
            $table->unsignedInteger('score')->nullable(); // ou integer, se permitir notas negativas futuramente
            $table->unsignedInteger('ranking')->nullable(); // Classificação (1º, 2º, etc.)

            // Garante que a inscrição tenha apenas um resultado
            $table->unique('inscription_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
