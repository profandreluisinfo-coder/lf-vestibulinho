<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('answers');
    }

    public function down(): void
    {
        // Recriação da tabela (para rollback)
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_id')->constrained('archives')->onDelete('cascade');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }
};
