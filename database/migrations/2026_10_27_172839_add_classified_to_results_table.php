<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Marca quem está dentro da lista de classificados do edital (item 9.1).
 * `ranking` guarda a posição de todos os candidatos com nota; `classified`
 * diz se a posição entra no limite de 3x as vagas (mais os empatados com o último).
 * Quem tem nota e classified = false está eliminado (item 9.3).
 */

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->boolean('classified')->default(false);
            $table->index('ranking');
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropIndex(['ranking']);
            $table->dropColumn('classified');
        });
    }
};