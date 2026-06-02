<?php

use App\Http\Controllers\Guest\{ ArchiveController, CommunicateController, HomeController, ResultController };
use App\Http\Controllers\Guest\{ CallController };
use App\Http\Controllers\Guest\CalendarController;
use App\Http\Controllers\Guest\FaqController;
use App\Http\Middleware\{ isResultEnabled };
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');   

    // Provas anteriores
    Route::get('/provas-anteriores', [ArchiveController::class, 'index'])->name('guest.archives.index');

    // Resultados
    Route::get('/resultado-final', [ResultController::class, 'index'])->name('guest.results.index')->middleware(isResultEnabled::class);

    // Chamadas
    Route::get('/chamadas', [CallController::class, 'index'])->name('guest.calls.index');

    // Perguntas frequentes
    Route::get('/perguntas-frequentes', [FaqController::class, 'index'])->name('guest.faqs.index');

    // Calendário
    Route::get('/calendario', [CalendarController::class, 'show'])
        ->name('guest.calendar.show');

    // Comunicados públicos
    Route::get('/comunicados', [CommunicateController::class, 'index'])
        ->name('guest.communicates.index');
});