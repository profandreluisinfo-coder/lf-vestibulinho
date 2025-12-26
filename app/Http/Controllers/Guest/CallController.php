<?php

namespace App\Http\Controllers\Guest;

use App\Models\Call;
use App\Http\Controllers\Controller;


/**
 * Class CallController
 * Controller para gerenciamento de chamadas
 *
 * @package App\Http\Controllers
 */
class CallController extends Controller
{
    /**
     * Obter todas as chamadas e seus respectivos dados: nº da chamada, data e hora, além do, nome do usuário e 
     * o nº da sua inscrição.
     * As informações são exibdas na página PÚBLICA de chamadas do site.
     * $calls é uma coleção agrupada
     */
    public function index()
    {
        // Obter todas as chamadas e seus respectivos dados
        $calls = Call::callsCompleted();

        // verificar se 'calls' é uma coleção vazia
        if ($calls->isEmpty()) {
            return redirect()->route('home');
        }

        return view('guest.calls.index', compact('calls'));
    }
}