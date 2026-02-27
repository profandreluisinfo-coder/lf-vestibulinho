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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('calendar')->default(false); // Adiciona a coluna 'calendar' para controlar a exibição do calendário
            $table->boolean('notice')->default(false); // Adiciona a coluna 'notice' para controlar a exibição dos editais
            $table->boolean('location')->default(false); // Adiciona a coluna 'location' para controlar a exibição do local do vestibular
            $table->boolean('result')->default(false); // Adiciona a coluna 'result' para controlar a exibição dos resultados do vestibular
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
