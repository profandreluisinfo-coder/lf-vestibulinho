<?php

use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController
};
use App\Http\Controllers\Guest\{
    ArchiveController, 
    HomeController, 
    ResultController
};
use App\Http\Controllers\Guest\{
    CallController,
    CalendarController,
    FaqController
};
use App\Http\Middleware\{isResultEnabled};
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])
    ->prefix('vestibulinho')
    ->name('guest.')
    ->group(function () {

        Route::get('/', [HomeController::class, 'index'])->name('home');

        // 🔒 Autenticação
        Route::get('/login', [LoginController::class, 'login'])->name('login');
        Route::post('/login', [LoginController::class, 'authenticate'])->middleware('throttle:3,1');

        // Registro
        Route::get('/registrar', [RegisterController::class, 'register'])->name('register');
        Route::post('/registrar', [RegisterController::class, 'store'])->name('register.store');

        // Provas anteriores
        Route::get('/provas-anteriores', [ArchiveController::class, 'index'])->name('archives.index');

        // Resultados
        Route::get('/resultado-final', [ResultController::class, 'index'])->name('results.index')->middleware(isResultEnabled::class);

        // Chamadas
        Route::get('/chamadas', [CallController::class, 'index'])->name('calls.index');

        // Perguntas frequentes
        Route::get('/perguntas-frequentes', [FaqController::class, 'index'])->name('faqs.index');

        // Calendário
        Route::get('/calendario', [CalendarController::class, 'show'])
            ->name('calendar.show');
    });
