<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Process;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProcessController extends Controller
{
    /**
     * Exibe a página pública dos eventos do processo seletivo.
     */
    public function show(): View | RedirectResponse
    {
        if (!(Process::current()?->status)) {
            abort(404);
        }

        return view('site.process.index');
    }
}