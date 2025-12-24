<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Guest\{
    ArchiveController,
    FaqController,
    HomeController,
    ResultController
};

use App\Http\Controllers\App\{
    CallController
};

use App\Http\Middleware\{
    isResultEnabled
};

// 🏠 Rotas públicas

Route::middleware(['guest'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');   

    // FaQ
    Route::get('/perguntas-frequentes', [FaqController::class, 'index'])->name('faqs.public.index');

    // Provas anteriores
    Route::get('/provas-anteriores', [ArchiveController::class, 'index'])->name('archives.public.index');

    // Resultados
    Route::get('/resultado-final', [ResultController::class, 'index'])
    ->name('results.public.index')
    ->middleware([isResultEnabled::class]);

    // Chamadas
    Route::get('/chamadas', [CallController::class, 'index'])
    ->name('calls.public.index');

    // Calendario
    //Route::get('/calendario', [CalendarController::class, 'index'])->name('calendar.admin.index'); // Calendario público
});
