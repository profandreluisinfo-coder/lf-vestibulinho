<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class CalendarController extends Controller
{
    /**
     * Exibe a página pública do calendário do processo seletivo.
     *
     * O calendário é obtido via Calendar::getActive(), que já usa cache
     * permanente (Cache::rememberForever). Não há consulta direta ao banco aqui.
     * O cache é invalidado automaticamente pelo model (booted → saved/deleted).
     */
    public function show(): View | RedirectResponse
    {       
        $calendar = Calendar::first() ?? new Calendar();

        if (!($calendar->is_active)) {
            return redirect()->route('home');
        }

        return view('site.calendar.index', compact(
            'calendar',
        ));
    }
}