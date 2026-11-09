<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Models\Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $results = $released ? $this->classifiedResultsQuery($process?->id)->limit(50)->get() : collect();
 
        return view('site.results.index', compact('released', 'results'));
    }

    /** Carrega páginas adicionais da lista pública, sem expor dados antes da liberação. */
    public function more(Request $request): JsonResponse
    {
        $process = Process::cached();
        if (! $process?->isResultReleased()) {
            return response()->json(['data' => [], 'has_more' => false], 403);
        }

        $validated = $request->validate([
            'offset' => ['sometimes', 'integer', 'min:0'],
            'search' => ['sometimes', 'nullable', 'string', 'max:30'],
        ]);
        $offset = (int) ($validated['offset'] ?? 0);
        $search = trim($validated['search'] ?? '');
        $query = $this->classifiedResultsQuery($process?->id);
        if ($search !== '') {
            $query->where('inscription_id', 'like', $search . '%');
        }
        $data = $query->offset($offset)->limit(51)->get();
        $hasMore = $data->count() > 50;
        $data = $data->take(50)->values();

        return response()->json([
            'data' => $data,
            'has_more' => $hasMore,
            'next_offset' => $offset + $data->count(),
        ]);
    }

    private function classifiedResultsQuery(int $processId)
    {
        return Result::query()
            ->where('classified', true)
            ->whereHas('inscription', fn ($q) => $q->forProcess($processId))
            ->orderBy('ranking')
            ->orderBy('inscription_id')
            ->select(['inscription_id', 'ranking', 'score']);
    }
 
    /**
     * Lista pública de candidatos não classificados (eliminados, edital 9.3).
     *
     * Mostra apenas o número da inscrição, e só depois que o resultado é liberado.
     */
    public function eliminated(): View
    {
        $process = Process::cached();
        $released = (bool) $process?->isResultReleased();
 
        // Cache limpo em Process::booted, junto com o da lista de classificados
        $numbers = $released
            ? Cache::remember('global_results_eliminated', 3600, fn () => Result::query()
                ->where('classified', false)
                ->whereNotNull('score')
                ->whereHas('inscription', fn ($q) => $q->forProcess($process?->id))
                ->orderBy('inscription_id')
                ->pluck('inscription_id')
            )
            : collect();
 
        return view('site.results.eliminated', compact('released', 'numbers'));
    }
}
