<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
// use App\Models\Call;
// use App\Models\Exam;
// use App\Models\Process;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.home.index');
        // Condições das 5 etapas exibidas no stepper (admin/partials/stepper)
        // $calendar           = (bool) Process::current();
        // $location_published = Process::current()?->isLocationReleased() ?? false;
        // $result_published   = Process::current()?->isResultReleased() ?? false;        
        // $calls_exists = Call::exists();

        // O total vem do tamanho da lista, então nunca fica fora de sincronia com as etapas
        // $steps = [
        //     $calendar,
        //     $location_published,
        //     $result_published,
        //     $calls_exists,
        // ];

        // $steps_done  = count(array_filter($steps));
        // $steps_total = count($steps);
        // $steps_pct   = round(($steps_done / $steps_total) * 100);

        // return view('admin.home.index', [
        //     'calendar'    => $calendar,
        //     'location_published' => $location_published,
        //     'result_published'   => $result_published,
        //     'calls_exists'       => $calls_exists,
        //     'steps_done'         => $steps_done,
        //     'steps_total'        => $steps_total,
        //     'steps_pct'          => $steps_pct
        // ]);
    }
}
