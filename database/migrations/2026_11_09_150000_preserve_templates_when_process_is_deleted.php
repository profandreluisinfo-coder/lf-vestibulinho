<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('templates', function (Blueprint $table): void {
            $table->dropForeign(['process_id']);
            $table->unsignedBigInteger('process_id')->nullable()->change();
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::table('templates')->whereNull('process_id')->exists()) {
            throw new RuntimeException(
                'Não é possível reverter: há modelos sem processo associado.'
            );
        }

        Schema::table('templates', function (Blueprint $table): void {
            $table->dropForeign(['process_id']);
            $table->unsignedBigInteger('process_id')->nullable(false)->change();
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->cascadeOnDelete();
        });
    }
};
