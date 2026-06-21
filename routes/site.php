<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Site\{
    HomeController, 
    PostController, 
    ArchiveController, 
    ResultController, 
    CallController, 
    FaqController, 
    SelectionProcessController
};

Route::middleware(['guest'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

Route::middleware(['guest'])->name('site.')->group(function () {

        // Postagens públicas
        Route::prefix('posts')
            ->name('posts.')
            ->group(function () {
                Route::get('', [PostController::class, 'index'])->name('index');
                Route::get('{slug}', [PostController::class, 'show'])->name('show');
            });

        // Provas anteriores
        Route::get('provas-anteriores', [ArchiveController::class, 'index'])->name('archives.index');

        // Resultados
        Route::get('resultado-final', [ResultController::class, 'index'])->name('results.index');

        // Chamadas
        Route::get('chamadas', [CallController::class, 'index'])->name('calls.index');

        // Perguntas frequentes
        Route::get('perguntas-frequentes', [FaqController::class, 'index'])->name('faqs.index');

        // Calendário
        Route::get('calendario', [SelectionProcessController::class, 'show'])
            ->name('process.show');
});