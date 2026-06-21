<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\SelectionProcess;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SelectionProcessController extends Controller
{
    /**
     * Exibe a página pública dos eventos do processo seletivo.
     */
    public function show(): View | RedirectResponse
    {       
        $selection_process = SelectionProcess::current();
        
        if (!($selection_process->status)) {
            abort(404);
        }

        $event = $selection_process->latestEvent;

        return view('site.selection-process.index', compact('event'));
    }
}