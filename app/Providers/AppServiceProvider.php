<?php

namespace App\Providers;

use App\Helpers\GlobalDataHelper;
use App\Models\Faq;
use App\Models\SelectionProcess;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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

        // Impede consultas ao banco durante comandos Artisan.
        if ($this->app->runningInConsole()) {
            return;
        }

        // Evita erro caso a tabela ainda não tenha sido criada.
        if (! Schema::hasTable('selection_processes')) {
            return;
        }

        $selectionProcess = SelectionProcess::latest('id')->first();

        // Torna variáveis globais acessíveis em todas as views
        GlobalDataHelper::share();
        
    }
}