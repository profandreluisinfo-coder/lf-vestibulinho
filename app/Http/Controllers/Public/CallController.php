<?php

namespace App\Http\Controllers\Public;

use App\Models\Call;
use App\Http\Controllers\Controller;

class CallController extends Controller
{
    /**
     * Obter todas as chamadas e seus respectivos dados: nº da chamada, data e hora, além do, nome do usuário e 
     * o nº da sua inscrição.
     * As informações são exibdas na página pública de chamadas do site.
     * $calls é uma coleção agrupada
     */
    public function index()
    {
        // Obter todas as chamadas e seus respectivos dados
        $calls = Call::with('callList', 'examResult.inscription.user')
            ->whereHas('callList', fn($q) => $q->where('status', 'completed'))
            ->get()
            ->groupBy(fn($call) => $call->callList->number)
            ->sortKeys();

        // verificar se 'calls' é uma coleção vazia
        if ($calls->isEmpty()) {
            return redirect()->route('home');
        }

        return view('calls.public.index', compact('calls'));
    }
}
