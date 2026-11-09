<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $dateColumns = [
        'start',
        'end',
        'location_publish',
        'exam_date',
        'answer_publish',
        'result_publish',
        'revision_start',
        'revision_end',
        'enrol_start',
        'enrol_remaining',
    ];

    public function up(): void
    {
        foreach ($this->dateColumns as $column) {
            if (!Schema::hasColumn('processes', $column)) {
                Schema::table('processes', function (Blueprint $table) use ($column) {
                    $table->date($column)->nullable();
                });
            }
        }

        // 2) Copia os dados de events para processes
        DB::table('events')->orderBy('id')->each(function ($process) {
            $data = [];
            foreach ($this->dateColumns as $column) {
                $data[$column] = $process?->$column;
            }

            DB::table('processes')
                ->where('id', $process?->process_id)
                ->update($data);
        });

        // 3) Remove a tabela antiga após copiar os dados. Isso também evita
        // colisão de nomes de índice no SQLite durante os testes.
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('events');
        Schema::enableForeignKeyConstraints();

        // 4) Índices
        $indexes = collect(Schema::getIndexes('processes'))->pluck('name')->all();

        Schema::table('processes', function (Blueprint $table) use ($indexes) {
            if (! in_array('processes_exam_date_index', $indexes, true)) {
                $table->index('exam_date');
            }
            if (! in_array('idx_period', $indexes, true)) {
                $table->index(['start', 'end'], 'idx_period');
            }
            if (! in_array('idx_enrol_period', $indexes, true)) {
                $table->index(['enrol_start', 'enrol_remaining'], 'idx_enrol_period');
            }
        });

    }

    public function down(): void
    {
        // Recria events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_id')->constrained('processes')->onDelete('cascade');

            foreach ($this->dateColumns as $column) {
                $table->date($column)->nullable();
            }

            $table->timestamps();
        });

        // Copia de volta
        DB::table('processes')->orderBy('id')->each(function ($process) {
            $data = ['process_id' => $process?->id, 'created_at' => now(), 'updated_at' => now()];
            foreach ($this->dateColumns as $column) {
                $data[$column] = $process?->$column;
            }

            DB::table('events')->insert($data);
        });

        // Remove índices e colunas de processes
        Schema::table('processes', function (Blueprint $table) {
            $table->dropIndex(['exam_date']);
            $table->dropIndex('idx_period');
            $table->dropIndex('idx_enrol_period');
            $table->dropColumn($this->dateColumns);
        });
    }
};
