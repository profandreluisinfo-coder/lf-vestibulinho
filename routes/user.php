<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dash\{
    UserController
};

use App\Http\Middleware\{IsAdmin, NotAdmin, WithInscription, NoInscription, isLocationEnabled, isResultEnabled};

// 🔒 Rotas que exigem login
Route::middleware('auth')->group(function () {

    // 📝 Area do admin para visualização dos dados dos usuários
    Route::prefix('usuarios')
        ->name('users.')
        ->middleware([IsAdmin::class])
        ->group(function () {

            // Area do candidato: exibe dashboard com as informações de como fazer a inscrição
            Route::get('/informacoes', [UserController::class, 'profile'])->name('profile')->middleware([NoInscription::class]);

            // Lista de usuários sem inscrição
            Route::get('/usuarios', [UserController::class, 'index'])->name('index');

            // Rota para apagar o nome social dos candidatos que não possuem autorização dos pais
            Route::patch('/users/{user}/clear-social-name', [UserController::class, 'clearSocialNameFromList'])
                ->name('clear.social.name');

            // Rota para apagar a condição de pessoa com deficiência dos candidatos que não enviaram laudo
            Route::patch('/users/{user}/clear-pne', [UserController::class, 'clearPneFromList'])
                ->name('clear.pne.condition');
        });

    // 📝 Area do admin para visualização dos dados dos usuários
    Route::prefix('usuario')
        ->name('user.')
        ->middleware([NotAdmin::class, NoInscription::class])
        ->group(function () {

            // Area do candidato: exibe a página cominformações sobre como fazer a inscrição
            Route::get('/informacoes', [UserController::class, 'profile'])->name('profile');
        });
});


// 📝 Processo de inscrição
Route::middleware([NotAdmin::class, WithInscription::class])->group(function () {

    // 📄 Área do candidato (inscrição concluída)
    Route::prefix('candidato')
        ->name('candidate.')
        ->middleware([WithInscription::class])
        ->group(function () {
            // Área do candidato: exibe o perfil da inscrição existente
            Route::get('/area-restrita', [UserController::class, 'inscription'])->name('profile');

            Route::get('/meu-local/pdf', [UserController::class, 'examCardToPdf'])
                ->name('card.pdf')
                ->middleware([isLocationEnabled::class]);

            Route::get('/meu-resultado/pdf', [UserController::class, 'resultCardToPdf'])
                ->name('result.pdf')
                ->middleware([isResultEnabled::class]);

            Route::get('/chamada/pdf', [UserController::class, 'callCardToPdf'])->name('call.pdf');
        });
});

// PDF genérico
Route::post('/comprovante-de-inscricao', [UserController::class, 'pdf'])->name('pdf');
