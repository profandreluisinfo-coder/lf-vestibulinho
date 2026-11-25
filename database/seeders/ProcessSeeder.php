<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('processes')->updateOrInsert(
            ['year' => 2027],
            [
                'edital' => 'Edital Vestibulinho 2027',
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $processId = DB::table('processes')
            ->where('year', 20227)
            ->value('id');

        DB::table('events')->updateOrInsert(
            ['process_id' => $processId],
            [
                'start'             => '2026-11-01',
                'end'               => '2026-11-30',
                'deferrals'         => '2026-12-05',
                'appeals_start'     => '2026-12-06',
                'appeals_end'       => '2026-12-09',
                'appeals_results'   => '2026-12-12',
                'location_publish'  => '2026-12-12',                
                'definitive_list'   => '2026-12-17',
                'exam_date'         => '2026-12-16',
                'answer_publish'    => '2026-12-17',
                'result_publish'    => '2026-12-24',
                'revision_start'    => '2026-12-18',
                'revision_end'      => '2026-12-19',
                'enrol_start'       => '2027-01-13',
                'enrol_remaining'   => '2027-01-17',
                'enrol_deadline'    => '2027-02-22',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );
    }
}
