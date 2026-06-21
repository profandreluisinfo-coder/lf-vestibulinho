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
        if (!(SelectionProcess::current()?->status)) {
            abort(404);
        }

        return view('site.selection-process.index');
    }
}