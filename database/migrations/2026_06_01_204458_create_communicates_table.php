<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communicates', function (Blueprint $table) {

            $table->id();

            // ── Conteúdo ──────────────────────────────────────────
            $table->string('titulo');
            $table->text('resumo')->nullable();

            // Tipo flexível — ex: 'info', 'alerta', 'urgente', etc.
            $table->string('tipo', 50)->default('info');

            // Link externo opcional
            $table->string('url')->nullable();

            // ── Controle de publicação ────────────────────────────
            // 'rascunho' | 'publicado'
            $table->string('status', 20)->default('rascunho');
            $table->timestamp('published_at')->nullable();

            // ── Autor ─────────────────────────────────────────────
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communicates');
    }
};
