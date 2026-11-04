<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $indexExists = collect(Schema::getIndexes('events'))
            ->contains(fn (array $index) => $index['name'] === 'events_process_id_unique');

        if (! $indexExists) {
            Schema::table('events', function (Blueprint $table) {
                $table->unique('process_id');
            });
        }
    }

    public function down(): void
    {
        $indexExists = collect(Schema::getIndexes('events'))
            ->contains(fn (array $index) => $index['name'] === 'events_process_id_unique');

        if ($indexExists) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropUnique('events_process_id_unique');
            });
        }
    }
};
