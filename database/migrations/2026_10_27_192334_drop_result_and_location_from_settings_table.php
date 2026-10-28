<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $existing = array_filter(
            ['location', 'result'],
            fn ($column) => Schema::hasColumn('settings', $column)
        );

        if (empty($existing)) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) use ($existing) {
            $table->dropColumn($existing);
        });
    }

    public function down(): void
    {
        $missing = array_filter(
            ['location', 'result'],
            fn ($column) => !Schema::hasColumn('settings', $column)
        );

        if (empty($missing)) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) use ($missing) {
            foreach ($missing as $column) {
                $table->boolean($column)->default(false);
            }
        });
    }
};