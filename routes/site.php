<?php

use App\Http\Controllers\Site\{
    HomeController,
    InfoController,
    PostController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])
    ->group(function () {

        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Notícias públicas
        Route::prefix('noticias')
            ->name('news.')
            ->group(function () {
                Route::get('/', [PostController::class, 'index'])->name('index');
                Route::get('/{slug}', [PostController::class, 'show'])->name('show');
        });

        // Comunicados públicos
        Route::prefix('comunicados')
            ->name('infos.')
            ->group(function () {
                Route::get('/', [InfoController::class, 'index'])->name('index');
                Route::get('/{slug}', [InfoController::class, 'show'])->name('show');
        });
    });