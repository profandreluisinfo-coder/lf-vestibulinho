<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\User\{
    AdminController
};

// 🔒 Rotas que exigem login
Route::middleware(['auth', IsAdmin::class])->group(function () {

    // 🛠️ Área administrativa

    // Painel principal
    Route::prefix('painel')
        ->name('control.')
        ->group(function () {
            Route::get('/administrativo', [AdminController::class, 'dashboard'])->name('panel');
        }); // Fim Painel do admin
        
}); // Fim Middleware de autenticado