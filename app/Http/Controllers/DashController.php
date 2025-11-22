<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashController extends Controller
{
    /**
     * Página principal do painel de administração do candidato
     * 
     * Exibe as informações do usuário logado, do resultado da prova e da chamada para o resultado com lista finalizada.
     * 
     * Route: GET / candidato
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        return view('dashboard.index', compact('user'));
    }
}