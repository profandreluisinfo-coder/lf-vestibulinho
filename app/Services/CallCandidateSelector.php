<?php

namespace App\Services;

use App\Models\Process;
use App\Models\Result;
use DomainException;
use Illuminate\Database\Eloquent\Collection;

class CallCandidateSelector
{
    /**
     * Returns the next not-yet-called candidates in general ranking order.
     * A tie at the requested boundary is kept together.
     */
    public function select(Process $process, int $totalLimit, int $pcdLimit): Collection
    {
        $pcdCandidates = $pcdLimit > 0
            ? $this->next($process, $pcdLimit, true)
            : new Collection;

        if ($pcdCandidates->count() < $pcdLimit) {
            throw new DomainException('A quantidade de candidatos PcD solicitada excede os PcD deferidos ainda não convocados.');
        }

        $generalCandidates = $this->next(
            $process,
            max(0, $totalLimit - $pcdCandidates->count()),
            false,
            $pcdCandidates->modelKeys(),
        );

        return $pcdCandidates->concat($generalCandidates)->sortBy([
            ['ranking', 'asc'],
            ['id', 'asc'],
        ])->values();
    }

    public function next(
        Process $process,
        int $limit,
        bool $pcdOnly = false,
        array $excludedIds = [],
    ): Collection
    {
        if ($limit < 1) {
            return new Collection;
        }

        $pool = $this->eligibleResults($process)
            ->reject(fn (Result $result) => in_array($result->id, $excludedIds, true));

        if ($pcdOnly) {
            $pool = $pool->filter(fn (Result $result) => $result->inscription?->user?->pne?->status === 'accepted');
        }

        $pool = $pool->sortBy([['ranking', 'asc'], ['id', 'asc']])->values();
        $selected = $pool->take($limit)->values();

        if ($selected->isEmpty() || $selected->count() < $limit) {
            return $selected;
        }

        $lastRank = $selected->last()->ranking;
        $selectedIds = $selected->modelKeys();
        $tiedAtBoundary = $pool
            ->where('ranking', $lastRank)
            ->whereNotIn('id', $selectedIds)
            ->values();

        return $selected->concat($tiedAtBoundary)->values();
    }

    private function eligibleResults(Process $process): Collection
    {
        return Result::query()
            ->with(['inscription.user.lgbt', 'inscription.user.pne'])
            ->whereNotNull('ranking')
            ->where('classified', true)
            ->whereHas('inscription', fn ($query) => $query
                ->approved()
                ->forProcess($process->id))
            ->whereDoesntHave('calls')
            ->get();
    }
}
