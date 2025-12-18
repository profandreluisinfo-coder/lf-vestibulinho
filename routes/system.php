<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\System\{
    SettingController
};

// 🔒 Rotas que exigem login
Route::middleware(['auth', IsAdmin::class])->group(function () {
// ==========================
    // ⚙️ Configurações do Sistema
    // ==========================
    Route::prefix('sistema') // OK
        ->name('system.')
        ->group(function () {
            Route::get('/redefinir-dados', [SettingController::class, 'index'])->name('index');
            Route::get('/apagar-dados', [SettingController::class, 'reset'])->name('reset');
            Route::post('/liberar-acesso-local', [SettingController::class, 'location'])->name('location');
            Route::post('/liberar-acesso-resultados', [SettingController::class, 'result'])->name('result');
        });

});