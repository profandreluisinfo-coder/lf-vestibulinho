<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('call_list_id') // vínculo com a lista de chamadas
                ->constrained('call_lists')
                ->onDelete('cascade');

            $table->unsignedBigInteger('exam_result_id'); // vínculo com o resultado da prova
            $table->unsignedInteger('call_number');       // Número da chamada (ex: 1, 2, 3...)
            $table->unsignedInteger('amount');            // Quantidade de convocados nesta chamada
            $table->date('date');                    // Data de comparecimento
            $table->time('time');                    // Hora de comparecimento
            $table->boolean('is_manual')->default(false);
            $table->timestamps();

            // Índices e chave estrangeira do resultado da prova
            $table->foreign('exam_result_id')
                ->references('id')->on('exam_results')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};