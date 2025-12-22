<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dash\{
    UserController
};

use App\Http\Middleware\{
    IsAdmin
};

// 🔒 Rotas que exigem login
Route::middleware(['auth'])->group(function () {

    // 📝 Area do admin para visualização dos dados dos usuários
    Route::prefix('usuarios')
        ->name('users.')
        ->middleware([IsAdmin::class])
        ->group(function () {
            // Lista de usuários sem inscrição
            Route::get('/usuarios', [UserController::class, 'index'])->name('index');

            // Rota para apagar o nome social dos candidatos que não possuem autorização dos pais
            Route::patch('/users/{user}/clear-social-name', [UserController::class, 'clearSocialName'])
                ->name('clear.social.name');
            
            // Rota para apagar a condição de pessoa com deficiência dos candidatos que não enviaram laudo
            Route::patch('/users/{user}/clear-pne', [UserController::class, 'clearPne'])
                ->name('clear.pne.condition');
        });

    
});
