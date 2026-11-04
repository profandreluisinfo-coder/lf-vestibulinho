<?php

namespace App\Http\Controllers\Site;

use App\Models\Call;
use App\Http\Controllers\Controller;
use App\Models\Process;


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
        $process = Process::current();
        $calls = Call::callsCompleted($process?->id);

        return view('site.calls.index', compact('calls', 'process'));
    }
}
