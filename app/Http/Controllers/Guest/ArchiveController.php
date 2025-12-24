<?php

namespace App\Http\Controllers\Guest;

use App\Models\Archive;
use App\Http\Controllers\Controller;

class ArchiveController extends Controller
{
    /**
     * Obter todos os arquivos de prova em ordem decrescente.
     *
     * Este método obtem todos os arquivos de prova da pasta 'archives' no banco de dados
     * que estejam publicados e os ordena em ordem decrescente de ano.
     * 
     * Os arquivos de prova são passados para a view 'archives.public.list', que exibirá os arquivos para 
     * os uários que acessarem o site do vestibulinho.
     * 
     * Última atualização: 15/12/2025 às 16:17
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Archive::hasActiveArchives()) {
            return redirect()->route('home');
        }

        $archives = Archive::getActiveArchives();

        return view('guest.archives.index', compact('archives'));
    }
}
