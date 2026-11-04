<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('results')) {
            throw new RuntimeException('A tabela results precisa existir antes da migração das chamadas.');
        }

        $legacyResultMap = [];
        $legacyCalls = DB::table('calls')->get(['id', 'exam_result_id']);

        if ($legacyCalls->isNotEmpty() && ! Schema::hasTable('exam_results')) {
            throw new RuntimeException(
                'Há chamadas antigas sem a tabela exam_results necessária para associá-las a results. '
                . 'Arquive ou migre esses registros manualmente antes desta migration.'
            );
        }

        foreach ($legacyCalls as $legacyCall) {
            $inscriptionId = DB::table('exam_results')
                ->where('id', $legacyCall->exam_result_id)
                ->value('inscription_id');

            $resultId = $inscriptionId
                ? DB::table('results')->where('inscription_id', $inscriptionId)->value('id')
                : null;

            if (! $resultId) {
                throw new RuntimeException(
                    "A chamada {$legacyCall->id} não possui resultado correspondente em results."
                );
            }

            $legacyResultMap[$legacyCall->id] = $resultId;
        }

        Schema::table('call_lists', function (Blueprint $table): void {
            $table->foreignId('process_id')->nullable()->after('id')->constrained('processes')->nullOnDelete();
            $table->timestamp('published_at')->nullable()->after('status');
            $table->foreignId('published_by')->nullable()->after('published_at')->constrained('users')->nullOnDelete();
            $table->foreignId('vacancy_course_id')->nullable()->after('published_by')->constrained('courses')->nullOnDelete();
            $table->unique(['process_id', 'number']);
        });

        Schema::table('calls', function (Blueprint $table): void {
            $table->foreignId('result_id')->nullable()->after('call_list_id');
        });

        foreach ($legacyResultMap as $callId => $resultId) {
            DB::table('calls')->where('id', $callId)->update(['result_id' => $resultId]);
        }

        foreach (Schema::getForeignKeys('calls') as $foreignKey) {
            if (in_array('exam_result_id', $foreignKey['columns'] ?? [], true)) {
                $foreignKeyIdentifier = DB::connection()->getDriverName() === 'sqlite'
                    ? $foreignKey['columns']
                    : $foreignKey['name'];

                Schema::table('calls', fn (Blueprint $table) => $table->dropForeign($foreignKeyIdentifier));
            }
        }

        foreach (Schema::getIndexes('calls') as $index) {
            if (($index['name'] ?? null) === 'calls_exam_result_id_foreign') {
                Schema::table('calls', fn (Blueprint $table) => $table->dropIndex($index['name']));
            }
        }

        Schema::table('calls', function (Blueprint $table): void {
            $table->dropColumn('exam_result_id');
            $table->foreignId('course_id')->nullable()->after('result_id')->constrained('courses')->nullOnDelete();
            $table->string('enrollment_status', 32)->default('awaiting')->after('is_manual');
            $table->boolean('is_pcd_reserve')->default(false)->after('enrollment_status');
            $table->dateTime('regularization_deadline')->nullable()->after('enrollment_status');
            $table->text('enrollment_notes')->nullable()->after('regularization_deadline');
            $table->timestamp('enrollment_recorded_at')->nullable()->after('enrollment_notes');
            $table->foreignId('enrollment_recorded_by')->nullable()->after('enrollment_recorded_at')->constrained('users')->nullOnDelete();
            $table->index(['call_list_id', 'enrollment_status']);
        });

        Schema::table('calls', function (Blueprint $table): void {
            $table->foreign('result_id')->references('id')->on('results')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('calls', function (Blueprint $table): void {
            $table->dropForeign(['result_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['enrollment_recorded_by']);
            $table->dropIndex(['call_list_id', 'enrollment_status']);
            $table->dropColumn([
                'result_id',
                'course_id',
                'enrollment_status',
                'is_pcd_reserve',
                'regularization_deadline',
                'enrollment_notes',
                'enrollment_recorded_at',
                'enrollment_recorded_by',
            ]);
            $table->unsignedBigInteger('exam_result_id')->nullable();
        });

        Schema::table('call_lists', function (Blueprint $table): void {
            $table->dropUnique(['process_id', 'number']);
            $table->dropForeign(['process_id']);
            $table->dropForeign(['published_by']);
            $table->dropForeign(['vacancy_course_id']);
            $table->dropColumn(['process_id', 'published_at', 'published_by', 'vacancy_course_id']);
        });
    }
};
