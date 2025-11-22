<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    DashController,
    UserController
};
use App\Http\Middleware\{NotAdmin, WithInscription};

// 🔒 Rotas que exigem login
Route::middleware(['auth'])->group(function () {
    //
    // 👤 Área do candidato
    //
    Route::middleware([NotAdmin::class])->group(function () {

        // 📄 Área do candidato (inscrição concluída)
        Route::prefix('candidato')
            ->name('user.')
            ->middleware([WithInscription::class])
            ->group(function () {
                Route::get('/meu-local/pdf', [UserController::class, 'examCardPdf'])->name('card.pdf');
                Route::get('/meu-resultado/pdf', [UserController::class, 'resultCardPdf'])->name('result.pdf');
                Route::get('/chamada/pdf', [UserController::class, 'generateCallPdf'])->name('call.pdf');
            });
    });

});