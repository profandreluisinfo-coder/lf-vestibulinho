<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dash\{
    ProfileController
};
use App\Http\Middleware\{NotAdmin, NoInscription, WithInscription, isLocationEnabled, isResultEnabled
};

// 🔒 Rotas que exigem login
Route::middleware(['auth'])->group(function () {

    // 📝 Processo de inscrição
    Route::middleware([NotAdmin::class])->group(function () {

        // Area do candidato: exibe dashboard com as informações de como fazer a inscrição
        Route::get('/inscricao', [ProfileController::class, 'profile'])->name('profile.user')->middleware([NoInscription::class]);

        // 📄 Área do candidato (inscrição concluída)
        Route::prefix('candidato')
            ->name('candidate.')
            ->middleware([WithInscription::class])
            ->group(function () {
                // Área do candidato: exibe o perfil da inscrição existente
                Route::get('/area-restrita', [ProfileController::class, 'inscription'])->name('profile');

                Route::get('/meu-local/pdf', [ProfileController::class, 'examCardPdf'])
                ->name('card.pdf')
                ->middleware([isLocationEnabled::class]);

                Route::get('/meu-resultado/pdf', [ProfileController::class, 'resultCardPdf'])
                ->name('result.pdf')
                ->middleware([isResultEnabled::class]);

                Route::get('/chamada/pdf', [ProfileController::class, 'generateCallPdf'])->name('call.pdf');
            });
    });
});