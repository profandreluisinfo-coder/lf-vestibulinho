<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Guest\{
    ArchiveController,
    CallController,
    FaqController,
    HomeController,
    ResultController,
};

use App\Http\Controllers\AuthController;

//
// 🏠 Rotas públicas
//
Route::middleware(['guest'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');   

    // FaQ
    Route::get('/perguntas-frequentes', [FaqController::class, 'index'])->name('faq.public.index');

    // Provas anteriores
    Route::get('/provas-anteriores', [ArchiveController::class, 'index'])->name('archives.public.index');

    // Resultados
    Route::get('/resultado-final', [ResultController::class, 'index'])->name('results.public.index');

    // Chamadas
    Route::get('/chamadas', [CallController::class, 'index'])->name('callings.index');
});
