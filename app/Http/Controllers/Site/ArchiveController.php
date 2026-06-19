<?php

namespace App\Http\Controllers\Site;

use App\Models\Archive;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    /**
     * Exibe a página de arquivos de provas.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $archives = Archive::getActiveArchives();

        return view('site.archives.index', compact('archives'));
    }
}