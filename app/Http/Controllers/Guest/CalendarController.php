<?php

namespace App\Http\Controllers\Guest;

use App\Models\Calendar;
use App\Http\Controllers\Controller;


class CalendarController extends Controller
{
    /**
     * Exibe a página pública do calendário do processo seletivo.
     *
     * O calendário é obtido via Calendar::getActive(), que já usa cache
     * permanente (Cache::rememberForever). Não há consulta direta ao banco aqui.
     * O cache é invalidado automaticamente pelo model (booted → saved/deleted).
     */
    public function show()
    {
        $calendar = Calendar::getActive();

        return view('guest.calendar.index', compact(
            'calendar',
        ));
    }
}