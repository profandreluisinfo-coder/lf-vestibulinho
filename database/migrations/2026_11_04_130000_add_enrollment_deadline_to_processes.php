<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('processes', function (Blueprint $table): void {
            $table->date('enrol_deadline')->nullable()->after('enrol_remaining');
        });
    }

    public function down(): void
    {
        Schema::table('processes', function (Blueprint $table): void {
            $table->dropColumn('enrol_deadline');
        });
    }
};
