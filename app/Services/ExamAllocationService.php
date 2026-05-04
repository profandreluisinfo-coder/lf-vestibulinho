<?php

namespace App\Services;

use App\Models\ExamLocation;
use App\Models\ExamResult;
use App\Models\Inscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExamAllocationService
{
    public function allocate(array $config): void
    {
        $candidatesPerRoom = (int) $config['candidates_per_room'];
        $splitLocations = $config['split_locations'] === 'yes';
        $splitFromRoom = $splitLocations ? (int) $config['split_from_room'] : null;
        $examDate = Carbon::parse($config['exam_date']);
        $examTime = Carbon::createFromFormat('H:i', $config['exam_time']);

        DB::transaction(function () use (
            $candidatesPerRoom,
            $splitLocations,
            $splitFromRoom,
            $examDate,
            $examTime
        ) {
            // 1. Buscar candidatos
            $inscriptions = Inscription::with(['user.user_detail'])
                ->whereHas('user', fn($q) => $q->where('role', 'user'))
                ->whereHas('user.user_detail')
                ->get();

            // 2. Ordenar por nome (com nome social)
            $sorted = $inscriptions->sortBy(function ($i) {
                $name = $i->user->social_name_option && $i->user->social_name
                    ? $i->user->social_name
                    : $i->user->name;

                return Str::ascii($name);
            })->values();

            // 3. Separar PNE e não-PNE
            $pne = $sorted->filter(fn($i) => $i->user->user_detail->pne)->values();
            $naoPne = $sorted->filter(fn($i) => !$i->user->user_detail->pne)->values();

            // 4. Locais
            $locations = ExamLocation::orderBy('id')->get();

            if ($locations->isEmpty()) {
                throw new \Exception('Nenhum local de prova cadastrado.');
            }

            if ($splitLocations && $locations->count() < 2) {
                throw new \Exception('Split de locais ativado, mas não há dois locais cadastrados.');
            }

            $firstLocation = $locations[0];
            $secondLocation = $locations[1] ?? null;

            $roomIndex = 1;
            $now = now();

            // 🔥 ARRAY GERAL (chave da otimização)
            $allData = [];

            // 5. Função para montar dados (SEM salvar ainda)
            $processRoom = function ($inscriptionsChunk, $roomNumber, $locationId) use (
                $examDate,
                $examTime,
                $now,
                &$allData
            ) {
                foreach ($inscriptionsChunk as $inscription) {
                    $allData[] = [
                        'inscription_id'   => $inscription->id,
                        'exam_date'        => $examDate,
                        'exam_time'        => $examTime,
                        'exam_location_id' => $locationId,
                        'room_number'      => $roomNumber,
                        'created_at'       => $now,
                        'updated_at'       => $now,
                    ];
                }
            };

            // 6. PNE primeiro
            foreach ($pne->chunk($candidatesPerRoom) as $chunk) {
                $location = $splitLocations && $splitFromRoom && $roomIndex >= $splitFromRoom
                    ? $secondLocation
                    : $firstLocation;

                $processRoom($chunk, $roomIndex, $location->id);
                $roomIndex++;
            }

            // 7. Não-PNE
            foreach ($naoPne->chunk($candidatesPerRoom) as $chunk) {
                $location = $splitLocations && $splitFromRoom && $roomIndex >= $splitFromRoom
                    ? $secondLocation
                    : $firstLocation;

                $processRoom($chunk, $roomIndex, $location->id);
                $roomIndex++;
            }

            // 🚀 8. UM ÚNICO UPSERT
            if (!empty($allData)) {
                ExamResult::upsert(
                    $allData,
                    ['inscription_id'],
                    ['exam_date', 'exam_time', 'exam_location_id', 'room_number', 'updated_at']
                );
            }
        });
    }
}