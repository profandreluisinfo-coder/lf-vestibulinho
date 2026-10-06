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
        Schema::table('appeals', function (Blueprint $table) {
            // Armazena o caminho relativo do arquivo anexado ao recurso
            // Aceita PDF, imagens (jpg, png, etc.) ou documentos (doc, docx)
            $table->string('path')->nullable()->after('allegations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appeals', function (Blueprint $table) {
            $table->dropColumn('path');
        });
    }
};