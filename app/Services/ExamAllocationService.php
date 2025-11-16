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
            // 1. Buscar candidatos válidos com inscrição (apenas role=user)
            $inscriptions = Inscription::with('user')
                ->whereHas('user', fn($q) => $q->where('role', 'user'))
                ->get();

            // 2. Ordenar globalmente por nome (corrigindo acentuação)
            $sorted = $inscriptions->sortBy(function ($i) {
                return Str::ascii($i->user->name);
            })->values();
            

            // 3. Separar PNEs e não-PNEs mantendo a ordem global
            $pne = $sorted->filter(fn($i) => $i->user->pne)->values();
            $naoPne = $sorted->filter(fn($i) => !$i->user->pne)->values();

            // 4. Pegar locais de prova
            $locations = ExamLocation::orderBy('id')->get();
            $firstLocation = $locations[0];
            $secondLocation = $locations[1] ?? null;

            $roomIndex = 1;

            // 5. Função para alocar inscrições em uma sala
            $allocateRoom = function ($inscriptionsChunk, $roomNumber, $locationId) use (
                $examDate,
                $examTime
            ) {
                foreach ($inscriptionsChunk as $inscription) {
                    ExamResult::updateOrCreate([
                        'inscription_id'   => $inscription->id,
                        'exam_date'        => $examDate,
                        'exam_time'        => $examTime,
                        'exam_location_id' => $locationId,
                        'room_number'      => $roomNumber
                    ]);
                }
            };

            // 6. Alocar PNEs primeiro
            foreach ($pne->chunk($candidatesPerRoom) as $chunk) {
                $location = $splitLocations && $splitFromRoom && $roomIndex >= $splitFromRoom
                    ? $secondLocation
                    : $firstLocation;

                $allocateRoom($chunk, $roomIndex, $location->id);
                $roomIndex++;
            }

            // 7. Alocar não-PNEs
            foreach ($naoPne->chunk($candidatesPerRoom) as $chunk) {
                $location = $splitLocations && $splitFromRoom && $roomIndex >= $splitFromRoom
                    ? $secondLocation
                    : $firstLocation;

                $allocateRoom($chunk, $roomIndex, $location->id);
                $roomIndex++;
            }
        });
    }
}
