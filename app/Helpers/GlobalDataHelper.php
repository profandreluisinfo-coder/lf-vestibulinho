<?php

namespace App\Helpers;

use App\Models\Inscription;
use App\Models\SelectionProcess;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class GlobalDataHelper
{
    public static function share()
    {
        /**
         * =========================
         * CACHE DOS DADOS GLOBAIS
         * =========================
         */

        $selectionProcess = Cache::remember(
            'global_selection_process',
            300,
            fn() => SelectionProcess::current()
        );

        $totalInscriptions = Cache::remember(
            'global_total_inscriptions',
            300,
            fn() => Inscription::count()
        );

        $usersWithoutInscription = Cache::remember(
            'global_users_without_inscription',
            300,
            fn() => User::where('role', 'user')
                ->doesntHave('inscription')
                ->count()
        );

        $settings = Cache::remember(
            'global_settings',
            300,
            fn() => Setting::latest('id')->first()
        );

        $year = now()->year;

        /**
         * =========================
         * COMPARTILHAMENTO GLOBAL
         * =========================
         */

        View::composer('*', function ($view) use (
            $selectionProcess,
            $totalInscriptions,
            $usersWithoutInscription,
            $settings,
            $year
        ) {
            $view->with([
                'selection_process' => $selectionProcess,
                'totalInscriptions' => $totalInscriptions,
                'usersWithoutInscription' => $usersWithoutInscription,
                'settings' => $settings,
                'year' => $year,
            ]);
        });
    }
}
