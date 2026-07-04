<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('processes')->updateOrInsert(
            ['year' => 2026],
            [
                'edital' => 'Edital Vestibulinho 2026',
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $processId = DB::table('processes')
            ->where('year', 2026)
            ->value('id');

        DB::table('events')->updateOrInsert(
            ['process_id' => $processId],
            [
                'start' => '2026-07-01',
                'end' => '2026-07-31',
                'location_publish' => '2026-08-10',
                'exam_date' => '2026-08-16',
                'answer_publish' => '2026-08-17',
                'result_publish' => '2026-08-24',
                'revision_start' => '2026-08-18',
                'revision_end' => '2026-08-19',
                'enrol_start' => '2026-08-25',
                'enrol_remaining' => '2026-08-29',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
