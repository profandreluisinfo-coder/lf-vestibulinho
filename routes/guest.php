<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Guest\{
    ArchiveController,
    FaqController,
    HomeController,
    ResultController
};

use App\Http\Controllers\Guest\{
    CallController
};

use App\Http\Middleware\{
    isResultEnabled
};

// 🏠 Rotas públicas

Route::middleware(['guest'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');   

    // FaQ
    Route::get('/perguntas-frequentes', [FaqController::class, 'index'])->name('guest.faqs.index');

    // Provas anteriores
    Route::get('/provas-anteriores', [ArchiveController::class, 'index'])->name('guest.archives.index');

    // Resultados
    Route::get('/resultado-final', [ResultController::class, 'index'])
    ->name('guest.results.index')
    ->middleware([isResultEnabled::class]);

    // Chamadas
    Route::get('/chamadas', [CallController::class, 'index'])->name('guest.calls.index');
});