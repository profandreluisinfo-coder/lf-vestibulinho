<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\{
    isResultEnabled
};

use App\Http\Controllers\Guest\{
    ArchiveController,
    HomeController,
    ResultController
};

use App\Http\Controllers\Guest\{
    CallController
};

Route::middleware(['guest'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');   

    // Provas anteriores
    Route::get('/provas-anteriores', [ArchiveController::class, 'index'])->name('guest.archives.index');

    // Resultados
    Route::get('/resultado-final', [ResultController::class, 'index'])->name('guest.results.index')->middleware(isResultEnabled::class);

    // Chamadas
    Route::get('/chamadas', [CallController::class, 'index'])->name('guest.calls.index');
});