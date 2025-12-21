<?php

namespace App\Helpers;

use App\Models\Call;
use App\Models\User;
use App\Models\Notice;
use App\Models\Archive;
use App\Models\Setting;
use App\Models\Calendar;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class GlobalDataHelper
{
    public static function share()
    {
        View::composer('*', function ($view) {

            // Configurações gerais
            $settings = Cache::remember('global_settings', 60, fn() => Setting::first() ?? new Setting());
            
            // Chamadas existentes
            $calls_exists = Cache::remember('calls_exists', 60, fn() => Call::exists());

            // Estatísticas de inscrições
            $totalInscriptions = Cache::remember('global_total_inscriptions', 60, fn() => Inscription::count());

            $usersWithoutInscription = Cache::remember(
                'global_users_without_inscription',
                60,
                fn() => User::where('role', 'user')
                    ->doesntHave('inscription')
                    ->count()
            );

            // Edital
            $notice = Cache::remember('global_notice', 60, fn() => Notice::first() ?? new Notice());

            // Calendário: Se não houver registros, retorna uma instância vazia
            $calendar = Cache::remember('global_calendar', 60, fn() => Calendar::first() ?? new Calendar());

            // Usuário autenticado e ano atual
            $authUser = Auth::user();
            $currentYear = now()->year;

            // Envia tudo para as views
            $view->with(compact(
                'settings',
                'calls_exists',
                'notice',
                'calendar',
                'authUser',
                'currentYear',
                'totalInscriptions',
                'usersWithoutInscription'
            ));
        });
    }
}
