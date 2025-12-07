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
     * Exibe as informações do usuário logado, bem como as informações de como fazer a inscrição.
     * 
     * Route: GET /dashboard (dashboard.index)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        return view('dashboard.index', compact('user'));
    }
}