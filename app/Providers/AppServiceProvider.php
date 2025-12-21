<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Faq;
use App\Models\User;
use App\Helpers\GlobalDataHelper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('pt_BR');

        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        // Somente admin pode editar/excluir/publicar FAQ
        Gate::define('manage-faq', function (User $user, Faq $faq) {
            return $user->id === $faq->user_id && $user->role === 'admin';
        });

        // Torna variáveis globais acessíveis em todas as views
        GlobalDataHelper::share();
    }
}