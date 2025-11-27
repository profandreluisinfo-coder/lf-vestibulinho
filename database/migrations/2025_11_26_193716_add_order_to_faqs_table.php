<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
/**
 * Adicione uma coluna à tabela de perguntas frequentes para armazenar a ordem das perguntas.
 * O valor padrão da ordem será 0.
 */
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('answer');
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};