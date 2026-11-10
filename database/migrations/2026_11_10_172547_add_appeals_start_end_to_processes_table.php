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
        Schema::table('processes', function (Blueprint $table) {
            $table->date('deferrals')->nullable()->after('end');
            $table->date('appeals_start')->nullable()->after('deferrals');
            $table->date('appeals_end')->nullable()->after('appeals_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->dropColumn(['deferrals', 'appeals_start', 'appeals_end']);
        });
    }
};
