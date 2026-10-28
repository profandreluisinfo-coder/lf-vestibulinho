<?php

namespace App\Helpers;

use App\Models\Call;
use App\Models\Inscription;
use App\Models\Post;
use App\Models\Process;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class GlobalDataHelper
{
    public static function share()
    {
        $process = Process::cached();

        $isInscriptionOpen = Cache::remember(
            'global_inscription_open',
            300,
            fn() => $process?->isInscriptionOpen() ?? false
        );

        $totalInscriptions = Cache::remember(
            'global_total_inscriptions',
            300,
            fn() => Inscription::forProcess($process?->id)->count()
        );

        $usersWithoutInscription = Cache::remember(
            'global_users_without_inscription',
            300,
            fn() => User::where('role', 'user')->doesntHave('inscription')->count()
        );

        $calls_exists = Cache::remember(
            'calls_exists',
            60,
            fn() => class_exists(Call::class) ? Call::exists() : false
        );

        $totalComunicados = Cache::remember('global_total_comunicados', 300, function () {
            try {
                return Post::comunicados()->published()->count();
            } catch (\Throwable $e) {
                report($e);
                return 0;
            }
        });

        $totalPublicacoes = Cache::remember('global_total_publicacoes', 300, function () {
            try {
                return count(array_intersect(
                    Setting::releasedPublications(),
                    array_keys(Setting::PUBLICATION_LISTS),
                ));
            } catch (\Throwable $e) {
                report($e);
                return 0;
            }
        });

        $locationReleased = $process?->isLocationReleased() ?? false; // Locais de prova liberados?
        $resultReleased   = $process?->isResultReleased() ?? false; // Resultado de prova liberado?

        $year = now()->year;

        View::composer('*', function ($view) use (
            $process,
            $isInscriptionOpen,
            $totalInscriptions,
            $usersWithoutInscription,
            $year,
            $calls_exists,
            $locationReleased,
            $resultReleased,
        ) {
            $view->with([
                'process' => $process,
                'isInscriptionOpen' => $isInscriptionOpen,
                'totalInscriptions' => $totalInscriptions,
                'usersWithoutInscription' => $usersWithoutInscription,
                'year' => $year,
                'calls_exists' => $calls_exists,
                'locationReleased' => $locationReleased,
                'resultReleased'   => $resultReleased,
            ]);
        });

        // O parcial quick-access.blade.php consome estas variáveis.
        // Se o arquivo for renomeado, ajuste o padrão abaixo.
        View::composer('*quick-access*', function ($view) use ($totalComunicados, $totalPublicacoes) {
            $view->with([
                'totalComunicados' => $totalComunicados,
                'totalPublicacoes' => $totalPublicacoes,
            ]);
        });
    }
}
