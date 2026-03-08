<?php

use App\Http\Middleware\{
    IsAdmin, isLocationEnabled, isResultEnabled, NotAdmin, WithInscription
};
use App\Http\Controllers\App\{PdfController};
use App\Http\Controllers\App\InscriptionController;
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
        
        Route::prefix('comprovante')
            ->name('receipt.')
            ->middleware([WithInscription::class])
            ->group(function () {
                // PDF Comprovante de inscrição
                Route::post('/inscricao', [PdfController::class, 'inscriptionReceiptToPdf'])->name('inscription');
            });
    });

    // ==========================
    // 📋 Inscrições (Visualização de inscrições por Admin)
    // ==========================
    Route::prefix('inscricoes')
        ->name('inscriptions.')
        ->middleware([IsAdmin::class])
        ->group(function () {
            // Lista de inscrições
            Route::get('/', [InscriptionController::class, 'index'])->name('index');
            Route::post('/inscriptions/data', [InscriptionController::class, 'getData'])
                ->name('get.data');
            // Lista de pessoas com deficiência
            Route::get('/pessoas-com-deficiencia', [InscriptionController::class, 'pcd'])
                ->name('pcd');
            Route::post('/pcd-data', [InscriptionController::class, 'getPcd'])
                ->name('pcd.data');
            // Candidatos com nome social
            Route::get('/nome-social', [InscriptionController::class, 'socialName'])
                ->name('social.name');
            Route::get('/candidato/{id}', [InscriptionController::class, 'show'])
                ->name('show');
        });
});
