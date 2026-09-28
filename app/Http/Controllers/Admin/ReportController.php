<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pne;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function lgbts(): View
    {
        return view('admin.reports.lgbts');
    }

    public function pcds(): View
    {
        $pcds = Pne::where('status', 'accepted')->paginate(20);

        return view('admin.reports.pcds', [
            'pcds' => $pcds
        ]);
    }
}
