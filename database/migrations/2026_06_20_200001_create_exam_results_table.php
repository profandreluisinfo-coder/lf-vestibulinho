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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();

            // Inscrição vinculada ao candidato
            $table->foreignId('inscription_id')->constrained('inscriptions')->onDelete('cascade');

            // Dados da prova
            $table->date('exam_date');
            $table->time('exam_time');
            $table->unsignedInteger('score')->nullable(); // ou integer, se permitir notas negativas futuramente
            $table->unsignedInteger('ranking')->nullable(); // Classificação (1º, 2º, etc.)

            // Local e sala de prova
            $table->foreignId('exam_location_id')->constrained('exam_locations')->onDelete('restrict');
            $table->unsignedInteger('room_number')->nullable(); // Ex: sala 1, 2, 3...

            $table->timestamp('email_sent_at')->nullable();

            $table->timestamps();

            // Garante que a inscrição tenha apenas uma alocação de prova
            $table->unique('inscription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};