<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Nesse formato a tabela é só a alocação: qual inscrição vai para qual local e sala. 
     * A data e a hora você obtém pelo processo, e o candidato as vê junto com o local na hora de 
     * gerar o comprovante ou o e-mail.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained();
            $table->unsignedSmallInteger('room_number');
            $table->timestamps();

            $table->index(['location_id', 'room_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
