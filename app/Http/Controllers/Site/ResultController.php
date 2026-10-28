<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Models\Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Lista pública de classificados.
     *
     * Mostra apenas número da inscrição, posição e pontuação, e só depois que o
     * admin libera o resultado do processo atual. Nenhum dado pessoal é carregado.
     *
     * O $process também chega à view pelo GlobalDataHelper; aqui ele é usado só
     * para decidir a consulta, por isso não é repassado.
     */
    public function index(): View
    {
        $process = Process::cached();
        $released = (bool) $process?->isResultReleased();
 
        // Cache limpo em Process::booted (liberar ou retirar do ar salva o processo)
        $results = $released
            ? Cache::remember('global_results', 3600, fn () => Result::query()
                ->where('classified', true)
                ->whereHas('inscription', fn ($q) => $q->forProcess($process->id))
                ->orderBy('ranking')
                ->orderBy('inscription_id') // ordem estável entre empatados (mesma posição)
                ->get(['inscription_id', 'ranking', 'score'])
            )
            : collect();
 
        return view('site.results.index', compact('released', 'results'));
    }
}