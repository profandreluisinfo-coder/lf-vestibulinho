<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dash\{
    AdminController
};

use App\Http\Middleware\{IsAdmin};

Route::middleware('auth')->group(function () {      

    Route::prefix('admin')
        ->name('admin.')
        ->middleware([IsAdmin::class])
        ->group(function () {
            // Rota para exibir o dashboard do admin (🛠️ Área administrativa)
            Route::get('/dashboard', [AdminController::class, 'home'])->name('dashboard');
    });
});