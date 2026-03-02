<?php

use App\Http\Controllers\App\{PdfController};
use App\Http\Middleware\{isLocationEnabled, isResultEnabled, NotAdmin, WithInscription};
use Illuminate\Support\Facades\Route;

// 🔒 Rotas que exigem login
Route::middleware(['auth'])->group(function () {

    // 📝 Processo de inscrição
    Route::middleware([NotAdmin::class])->group(function () {

        Route::prefix('cartao')
            ->name('card.')
            ->middleware([WithInscription::class])
            ->group(function () {
                // PDF Cartão do local de prova
                Route::get('/meu-local', [PdfController::class, 'testLocationCardToPdf'])
                    ->name('exam')
                    ->middleware([isLocationEnabled::class]);

                // PDF Cartão do resultado da Prova
                Route::get('/resultado', [PdfController::class, 'testResultCardToPdf'])->name('result')->middleware([isResultEnabled::class]);

                // PDF Cartão de chamada
                Route::get('/chamada', [PdfController::class, 'callCardToPdf'])->name('call');
            });
    });
});
