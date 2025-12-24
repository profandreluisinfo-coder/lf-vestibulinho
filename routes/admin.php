<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Dash\{
    AdminController
};

// 🔒 Rotas que exigem login
Route::middleware(['auth', IsAdmin::class])->group(function () {

    // 🛠️ Área administrativa

    // Painel principal
    Route::prefix('dash') // pasta
        ->name('dash.') // pasta
        ->group(function () {
            Route::get('/administrativo', [AdminController::class, 'home'])->name('admin.home'); // pasta e view
        }); // Fim Painel do admin
        
}); // Fim Middleware de autenticado