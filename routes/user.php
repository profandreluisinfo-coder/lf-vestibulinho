<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{   
    UserController
};
use App\Http\Middleware\{NotAdmin, WithInscription};

//
// 🏠 Rotas públicas
//
Route::middleware(['guest'])->group(function () {
    // Login e registro padrão (necessário para Laravel/Fortify)
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/login', [UserController::class, 'authenticate'])->middleware('throttle:3,1');

    // Route::post('/login', [UserController::class, 'authenticate'])->middleware('throttle:3,1');
    Route::get('/registrar', [UserController::class, 'register'])->name('register');
    Route::post('/registrar', [UserController::class, 'store']);
});

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
