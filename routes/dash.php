<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dash\{
    AdminController,
    UserController
};

use App\Http\Controllers\App\{
    PdfController
};

use App\Http\Middleware\{IsAdmin, NotAdmin, WithInscription, NoInscription};

// 🔒 Rotas que exigem login
Route::middleware('auth')->group(function () {

    Route::prefix('usuarios')
        ->name('user.')
        ->middleware([NotAdmin::class, WithInscription::class])
        ->group(function () {
            // PDF Ficha de Inscrição do Candidato
            Route::post('/ficha-em-pdf', [PdfController::class, 'inscriptionToPdf'])
                ->name('inscription.to.pdf');
        });

    // Area do 'admin' para visualização dos dados dos usuários
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

    // Area do 'admin' para visualização dos dados dos usuários
    Route::prefix('dash') // pasta
        ->name('dash.') // pasta
        ->group(function () {
            Route::middleware([NotAdmin::class, NoInscription::class])
                ->group(function () {
                    // Area do candidato: exibe a página cominformações sobre como fazer a inscrição
                    Route::get('/usuario', [UserController::class, 'home'])->name('user.home'); // pasta e view
                });

            // Área do candidato (com inscrição concluída)
            Route::middleware([NotAdmin::class, WithInscription::class])->group(function () {
                // Área do candidato: exibe o perfil da inscrição existente
                Route::get('/candidato', [UserController::class, 'inscription'])->name('user.inscription');
            });

            Route::middleware([IsAdmin::class])
                ->group(function () {
                    // 🛠️ Área administrativa
                    Route::get('/admin', [AdminController::class, 'home'])->name('admin.home'); // pasta e view

                }); // Fim Middleware de autenticado
        });
});
