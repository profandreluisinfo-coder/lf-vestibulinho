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

Route::middleware('auth')->group(function () {

    Route::prefix('usuarios')
        ->name('user.')
        ->middleware([NotAdmin::class, WithInscription::class])
        ->group(function () {
            // PDF Ficha de Inscrição do Candidato
            Route::post('/ficha-em-pdf', [PdfController::class, 'inscriptionToPdf'])
                ->name('inscription.to.pdf');
        });

    

    Route::prefix('candidato') // pasta
        ->name('dash.') // pasta
        ->group(function () {
            Route::middleware([NotAdmin::class, NoInscription::class])
                ->group(function () {
                    // Area do candidato: exibe a página com informações sobre como fazer a inscrição
                    Route::get('/', [UserController::class, 'start'])->name('user.start'); // pasta e view
                });

            // Área do candidato (com inscrição concluída)
            Route::middleware([NotAdmin::class, WithInscription::class])->group(function () {
                // Área do candidato: exibe o perfil da inscrição existente
                Route::get('/inscricao', [UserController::class, 'inscription'])->name('user.inscription');
            });

            
            
    });

    Route::prefix('admin')
        ->name('admin.')
        ->middleware([IsAdmin::class])
        ->group(function () {
            // Rota para exibir o dashboard do admin (🛠️ Área administrativa)
            Route::get('/dashboard', [AdminController::class, 'home'])->name('dashboard');
    });
});
