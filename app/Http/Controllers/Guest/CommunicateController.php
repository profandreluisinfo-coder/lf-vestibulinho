<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Communicate;

class CommunicateController extends Controller
{
    /**
     * Lista todos os comunicados publicados (paginado).
     * Rota: GET /comunicados
     */
    public function index()
    {
        $comunicados = Communicate::publicado()
            ->latest('published_at')
            ->paginate(10);

        return view('guest.communicates.index', compact('comunicados'));
    }

    /**
     * Exibe um comunicado publicado específico.
     * Rota: GET /comunicados/{communicate}
     */
    public function show(Communicate $communicate)
    {
        // Garante que o visitante só vê comunicados publicados
        abort_unless($communicate->estaPublicado(), 404);

        return view('guest.communicates.show', compact('communicate'));
    }
}
