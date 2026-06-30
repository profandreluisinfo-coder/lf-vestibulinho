<?php

namespace App\Helpers;

use App\Models\Inscription;
use App\Models\Process;
use App\Models\Setting;
use App\Models\User;
use App\Models\Call;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class GlobalDataHelper
{
    public static function share()
    {
        $process = Cache::remember(
            'global_process',
            300,
            fn () => Process::current()
        );

        $totalInscriptions = Cache::remember(
            'global_total_inscriptions',
            300,
            fn () => Inscription::count('id')
        );

        $usersWithoutInscription = Cache::remember(
            'global_users_without_inscription',
            300,
            fn () => User::where('role', 'user')->doesntHave('inscription')->count()
        );

        $settings = Cache::remember(
            'global_settings',
            300,
            fn () => Setting::latest('id')->first()
        );

        $calls_exists = Cache::remember(
            'calls_exists',
            60,
            fn () => class_exists(\App\Models\Call::class) ? Call::exists() : false
        );

        $year = now()->year;

        View::composer('*', function ($view) use (
            $process,
            $totalInscriptions,
            $usersWithoutInscription,
            $settings,
            $year,
            $calls_exists
        ) {
            $view->with([
                'process' => $process,
                'totalInscriptions' => $totalInscriptions,
                'usersWithoutInscription' => $usersWithoutInscription,
                'settings' => $settings,
                'year' => $year,
                'calls_exists' => $calls_exists,
            ]);
        });
    }
}