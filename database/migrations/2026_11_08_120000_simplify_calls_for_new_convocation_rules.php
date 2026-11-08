<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('call_lists', function (Blueprint $table): void {
            // MySQL uses the leading process_id column of the unique index
            // to support its foreign key, so preserve that support separately.
            $table->index('process_id');
            $table->dropUnique(['process_id', 'number']);
            $table->dropForeign(['vacancy_course_id']);
            $table->dropColumn('vacancy_course_id');
        });

        Schema::table('calls', function (Blueprint $table): void {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['enrollment_recorded_by']);
            $table->dropIndex(['call_list_id', 'enrollment_status']);
            $table->dropColumn([
                'course_id',
                'enrollment_status',
                'regularization_deadline',
                'enrollment_notes',
                'enrollment_recorded_at',
                'enrollment_recorded_by',
            ]);
        });
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
            $table->dropIndex(['process_id']);
        });

        Schema::table('calls', function (Blueprint $table): void {
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->string('enrollment_status', 32)->default('awaiting');
            $table->dateTime('regularization_deadline')->nullable();
            $table->text('enrollment_notes')->nullable();
            $table->timestamp('enrollment_recorded_at')->nullable();
            $table->foreignId('enrollment_recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->index(['call_list_id', 'enrollment_status']);
        });
    }
};
