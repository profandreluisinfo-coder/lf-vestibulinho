<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guardians', function (Blueprint $table) {
            $table->dropColumn('degree');
        });

        Schema::table('guardians', function (Blueprint $table) {
            $table->foreignId('degree_id')
                ->nullable()
                ->after('phone')
                ->constrained('degrees')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('guardians', function (Blueprint $table) {
            $table->dropForeign(['degree_id']);
            $table->dropColumn('degree_id');
        });

        Schema::table('guardians', function (Blueprint $table) {
            $table->string('degree', 45)->nullable()->after('phone');
        });
    }
};