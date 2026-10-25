<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\Event;
use App\Models\ExamResult;
use App\Models\Process;
use App\Models\Setting;

class AdminController extends Controller
{
    public function index()
    {
        // Condições das 5 etapas exibidas no stepper (admin/partials/stepper)
        $process_defined = (bool) Process::current();
        $local_status = ExamResult::hasRecords();
        $ranking_active = ExamResult::hasScores();
        $result_published = Setting::isResultEnabled();
        $calls_exists = Call::exists();
        $events_exists = Event::latest()->exists();

        // O total vem do tamanho da lista, então nunca fica fora de sincronia com as etapas
        $steps = [
            $process_defined,
            $local_status,
            $ranking_active,
            $result_published,
            $calls_exists,
        ];

        $steps_done = count(array_filter($steps));
        $steps_total = count($steps);
        $steps_pct = round(($steps_done / $steps_total) * 100);

        return view('admin.home.index', [
            'local_status' => $local_status,
            'ranking_active' => $ranking_active,
            'calls_exists' => $calls_exists,
            'events_exists' => $events_exists,
            'steps_done' => $steps_done,
            'steps_total' => $steps_total,
            'steps_pct' => $steps_pct
        ]);
    }
}