<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Preserve the standalone FK index before removing the unique index.
        if (! $this->findIndex('call_lists', ['process_id'])) {
            Schema::table('call_lists', fn (Blueprint $table) => $table->index('process_id'));
        }

        if ($index = $this->findIndex('call_lists', ['process_id', 'number'], true)) {
            Schema::table('call_lists', fn (Blueprint $table) => $table->dropUnique($index['name']));
        }

        if (Schema::hasColumn('call_lists', 'vacancy_course_id')) {
            $this->dropForeignForColumn('call_lists', 'vacancy_course_id');
            Schema::table('call_lists', fn (Blueprint $table) => $table->dropColumn('vacancy_course_id'));
        }

        // In this database the compound index also supports the call_list_id
        // foreign key, so add a standalone index before dropping the compound one.
        if (! $this->findIndex('calls', ['call_list_id'])) {
            Schema::table('calls', fn (Blueprint $table) => $table->index('call_list_id'));
        }

        foreach (['course_id', 'enrollment_recorded_by'] as $column) {
            if (Schema::hasColumn('calls', $column)) {
                $this->dropForeignForColumn('calls', $column);
            }
        }

        if ($index = $this->findIndex('calls', ['call_list_id', 'enrollment_status'])) {
            Schema::table('calls', fn (Blueprint $table) => $table->dropIndex($index['name']));
        }

        $columnsToRemove = array_values(array_filter([
            'course_id',
            'enrollment_status',
            'regularization_deadline',
            'enrollment_notes',
            'enrollment_recorded_at',
            'enrollment_recorded_by',
        ], fn (string $column) => Schema::hasColumn('calls', $column)));

        if ($columnsToRemove !== []) {
            Schema::table('calls', fn (Blueprint $table) => $table->dropColumn($columnsToRemove));
        }
    }

    public function down(): void
    {
        $duplicates = DB::table('call_lists')
            ->select('process_id', 'number')
            ->groupBy('process_id', 'number')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($duplicates) {
            throw new RuntimeException(
                'Não é possível reverter esta migration: há números de chamada repetidos para o mesmo processo.'
            );
        }

        Schema::table('call_lists', function (Blueprint $table): void {
            $table->foreignId('vacancy_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->unique(['process_id', 'number']);
        });

        if ($index = $this->findIndex('call_lists', ['process_id'])) {
            Schema::table('call_lists', fn (Blueprint $table) => $table->dropIndex($index['name']));
        }

        Schema::table('calls', function (Blueprint $table): void {
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->string('enrollment_status', 32)->default('awaiting');
            $table->dateTime('regularization_deadline')->nullable();
            $table->text('enrollment_notes')->nullable();
            $table->timestamp('enrollment_recorded_at')->nullable();
            $table->foreignId('enrollment_recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->index(['call_list_id', 'enrollment_status']);
        });

        if ($index = $this->findIndex('calls', ['call_list_id'])) {
            Schema::table('calls', fn (Blueprint $table) => $table->dropIndex($index['name']));
        }
    }

    private function findIndex(string $table, array $columns, ?bool $unique = null): ?array
    {
        foreach (Schema::getIndexes($table) as $index) {
            if (($index['columns'] ?? []) === $columns
                && ($unique === null || (bool) ($index['unique'] ?? false) === $unique)) {
                return $index;
            }
        }

        return null;
    }

    private function dropForeignForColumn(string $tableName, string $column): void
    {
        foreach (Schema::getForeignKeys($tableName) as $foreignKey) {
            if (! in_array($column, $foreignKey['columns'] ?? [], true)) {
                continue;
            }

            $identifier = DB::connection()->getDriverName() === 'sqlite'
                ? $foreignKey['columns']
                : $foreignKey['name'];

            Schema::table($tableName, fn (Blueprint $table) => $table->dropForeign($identifier));
        }
    }
};
