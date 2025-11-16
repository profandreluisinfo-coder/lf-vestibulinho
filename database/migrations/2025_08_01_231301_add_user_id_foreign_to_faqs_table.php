<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            // Garante que a coluna user_id exista
            if (!Schema::hasColumn('faqs', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            } else {
                // Se já existir a coluna mas não a foreign key
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            // Adicionar a coluna status
            $table->boolean('status')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('status');
        });
    }
};