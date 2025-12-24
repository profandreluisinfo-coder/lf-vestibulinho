<?php

namespace App\Http\Controllers\Guest;

use App\Models\Calendar;
use App\Http\Controllers\Controller;


class CalendarController extends Controller
{
    /**
     * Lista as datas do vestibulinho no site público.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $calendar = Calendar::first();

        if (!$calendar) {
            return redirect()->route('home');
        }

        return view(
'app.calendar.public.index', compact('calendar'));
    }
}