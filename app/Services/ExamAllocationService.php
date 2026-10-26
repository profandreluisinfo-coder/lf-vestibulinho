<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Inscription;
use App\Models\Lgbt;
use App\Models\Location;
use App\Models\Pne;
use App\Models\Process;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExamAllocationService
{
    private const UPSERT_BATCH_SIZE = 1000;

    /**
     * Aloca os candidatos nas salas de prova.
     *
     * Regras:
     * - só roda depois do encerramento das inscrições do processo atual;
     * - só roda quando não há análise de PNE ou de nome social pendente;
     * - apenas inscrições deferidas do processo seletivo atual;
     * - candidatos PNE ocupam sempre as primeiras salas, separados dos demais;
     * - dentro de cada grupo, a ordem é alfabética (considerando o nome social aceito);
     * - o sistema comporta no máximo dois locais de prova;
     * - se 'split_locations' = yes, as salas a partir de 'split_from_room' vão para o segundo local.
     *
     * @throws DomainException quando a configuração é incompatível com os dados cadastrados.
     */
    public function allocate(array $config): void
    {
        $candidatesPerRoom = (int) $config['candidates_per_room'];
        $splitLocations = $config['split_locations'] === 'yes';
        $splitFromRoom = $splitLocations ? (int) ($config['split_from_room'] ?? 0) : null;

        if ($splitLocations && $splitFromRoom < 2) {
            throw new DomainException(
                'Informe a partir de qual sala os locais serão divididos (mínimo 2).'
            );
        }

        DB::transaction(function () use ($candidatesPerRoom, $splitLocations, $splitFromRoom) {
            // 1. Processo seletivo atual
            $process = Process::current();

            if (! $process) {
                throw new DomainException('Nenhum processo seletivo cadastrado.');
            }

            if (! $process->isInscriptionEnded()) {
                throw new DomainException(
                    $process->end
                        ? 'O período de inscrições só termina em ' . $process->formatDate($process->end)
                            . '. Aguarde o encerramento para alocar os candidatos.'
                        : 'O período de inscrições do processo atual não está definido.'
                );
            }

            // 2. Locais (no máximo dois)
            $locations = Location::orderBy('id')->limit(2)->get();

            if ($locations->isEmpty()) {
                throw new DomainException('Nenhum local de prova cadastrado.');
            }

            if ($splitLocations && $locations->count() < 2) {
                throw new DomainException(
                    'A divisão de locais está ativada, mas não há dois locais cadastrados.'
                );
            }

            $firstLocation = $locations[0];
            $secondLocation = $locations[1] ?? null;

            // 3. Candidatos (deferidos, do processo atual), já ordenados por nome
            $sorted = $this->sortedCandidates($process);

            if ($sorted->isEmpty()) {
                throw new DomainException('Não existem inscrições deferidas para alocar.');
            }

            // 3.1 Análises pendentes mudariam as salas e a ordem depois da alocação
            $this->assertNoPendingAnalyses($sorted);

            // 4. Separar PNE e não-PNE (mantendo a ordem alfabética em cada grupo)
            $pne = $sorted
                ->filter(fn ($i) => $i->user?->pne?->status === Pne::STATUS_ACCEPTED)
                ->values();

            $naoPne = $sorted
                ->reject(fn ($i) => $i->user?->pne?->status === Pne::STATUS_ACCEPTED)
                ->values();

            // 5. Validar capacidade antes de montar qualquer coisa
            $this->assertCapacity(
                $pne->count(),
                $naoPne->count(),
                $candidatesPerRoom,
                $splitLocations,
                $splitFromRoom,
                $firstLocation,
                $secondLocation
            );

            // 6. Montar as linhas (PNE primeiro, depois os demais)
            $now = now();
            $rows = [];

            $chunks = $pne->chunk($candidatesPerRoom)
                ->concat($naoPne->chunk($candidatesPerRoom))
                ->values();

            foreach ($chunks as $index => $chunk) {
                $roomNumber = $index + 1;

                $location = ($splitLocations && $roomNumber >= $splitFromRoom)
                    ? $secondLocation
                    : $firstLocation;

                foreach ($chunk as $inscription) {
                    $rows[] = [
                        'inscription_id' => $inscription->id,
                        'location_id'    => $location->id,
                        'room_number'    => $roomNumber,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                }
            }

            // 7. Remover alocações antigas de inscrições deste processo que saíram da lista
            //    (alocações de outros processos não são tocadas)
            Exam::whereHas('inscription', fn ($q) => $q->forProcess($process->id))
                ->whereNotIn('inscription_id', $sorted->pluck('id'))
                ->delete();

            // 8. Gravar em lotes (exige índice unique em exams.inscription_id)
            foreach (array_chunk($rows, self::UPSERT_BATCH_SIZE) as $batch) {
                Exam::upsert(
                    $batch,
                    ['inscription_id'],
                    ['location_id', 'room_number', 'updated_at']
                );
            }
        });
    }

    /**
     * Candidatos em ordem alfabética, usando o nome social quando aceito.
     * Considera apenas inscrições deferidas do processo informado.
     */
    private function sortedCandidates(Process $process): Collection
    {
        return Inscription::approved()
            ->forProcess($process->id)
            ->with(['user.lgbt', 'user.pne'])
            ->whereHas('user', fn ($q) => $q->where('role', 'user'))
            ->get()
            ->sortBy(function ($i) {
                $name = $i->user?->lgbt?->status === Lgbt::STATUS_ACCEPTED
                    ? $i->user->lgbt->name
                    : $i->user->name;

                return Str::lower(Str::ascii($name));
            })
            ->values();
    }

    /**
     * Impede a alocação enquanto houver PNE ou nome social ainda sem decisão.
     * "Pendente" = existe a solicitação, mas o status não é deferido nem indeferido.
     *
     * @throws DomainException
     */
    private function assertNoPendingAnalyses(Collection $candidates): void
    {
        $pendingPne = $candidates
            ->filter(fn ($i) => $i->user?->pne
                && ! in_array($i->user->pne->status, [Pne::STATUS_ACCEPTED, Pne::STATUS_REJECTED], true))
            ->count();

        $pendingLgbt = $candidates
            ->filter(fn ($i) => $i->user?->lgbt
                && ! in_array($i->user->lgbt->status, [Lgbt::STATUS_ACCEPTED, Lgbt::STATUS_REJECTED], true))
            ->count();

        if ($pendingPne === 0 && $pendingLgbt === 0) {
            return;
        }

        $parts = array_filter([
            $pendingPne > 0 ? "{$pendingPne} de PNE" : null,
            $pendingLgbt > 0 ? "{$pendingLgbt} de nome social" : null,
        ]);

        throw new DomainException(
            'Ainda há análises pendentes (' . implode(' e ', $parts) . '). '
            . 'Conclua os deferimentos antes de alocar os candidatos.'
        );
    }

    /**
     * Confere se as salas necessárias cabem nos locais e se os PNE ficam no primeiro local.
     *
     * @throws DomainException
     */
    private function assertCapacity(
        int $pneCount,
        int $naoPneCount,
        int $candidatesPerRoom,
        bool $splitLocations,
        ?int $splitFromRoom,
        Location $firstLocation,
        ?Location $secondLocation
    ): void {
        $pneRooms = (int) ceil($pneCount / $candidatesPerRoom);
        $roomsNeeded = $pneRooms + (int) ceil($naoPneCount / $candidatesPerRoom);

        // As salas dos PNE precisam caber inteiras no primeiro local
        if ($splitLocations && $pneRooms > $splitFromRoom - 1) {
            throw new DomainException(
                "As salas dos candidatos PNE ({$pneRooms}) não cabem inteiras no primeiro local. "
                . 'Divida a partir da sala ' . ($pneRooms + 1) . ' ou posterior.'
            );
        }

        $roomsInFirst = $splitLocations
            ? min($splitFromRoom - 1, $roomsNeeded)
            : $roomsNeeded;

        $roomsInSecond = $roomsNeeded - $roomsInFirst;

        if ($roomsInFirst > $firstLocation->rooms_available) {
            throw new DomainException(
                "O local {$firstLocation->name} comporta {$firstLocation->rooms_available} sala(s), "
                . "mas seriam necessárias {$roomsInFirst}."
            );
        }

        if ($roomsInSecond > 0 && $roomsInSecond > $secondLocation->rooms_available) {
            throw new DomainException(
                "O local {$secondLocation->name} comporta {$secondLocation->rooms_available} sala(s), "
                . "mas seriam necessárias {$roomsInSecond}."
            );
        }
    }
}