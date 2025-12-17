<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\{   
    UserController
};
use App\Http\Middleware\{IsAdmin, WithInscription};


// 🔒 Rotas que exigem login
Route::middleware(['auth', IsAdmin::class])->group(function () {
    
    // Usuários sem inscrição
    Route::prefix('usuarios') // OK
        ->name('users.')
        ->group(function () {
            Route::get('/sem-inscricao', [UserController::class, 'index'])->name('index');
        });
        
}); // Fim Middleware de autenticado