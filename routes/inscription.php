<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\{
    // IsAdmin,
    isLocationEnabled,
    isResultEnabled,
    NotAdmin,
    WithInscription,
    NoInscription
};

use App\Http\Controllers\{
    PdfController,
    UserController
};

use App\Http\Controllers\{
    InscriptionController
};

// 🔒 Rotas que exigem login
Route::middleware(['auth'])->name('inscription.')->group(function () {

    // 📝 Processo de inscrição
    Route::middleware(NoInscription::class)
        ->prefix('inscricao')
        ->name('step.')
        ->group(function () {
            // Area do candidato: exibe a página com informações sobre como fazer a inscrição
            Route::get('informacoes', [UserController::class, 'index'])->name('start'); // pasta e view
            Route::get('dados-pessoais', [InscriptionController::class, 'personal'])->name('personal');
            Route::post('dados-pessoais', [InscriptionController::class, 'personalStore']);

            Route::get('certidao-nascimento', [InscriptionController::class, 'certificate'])->name('certificate');
            Route::post('certidao-nascimento', [InscriptionController::class, 'certificateStore']);

            Route::get('endereco', [InscriptionController::class, 'address'])->name('address');
            Route::post('endereco', [InscriptionController::class, 'addressStore']);

            Route::get('dados-escolares', [InscriptionController::class, 'academic'])->name('academic');
            Route::post('dados-escolares', [InscriptionController::class, 'academicStore']);

            Route::get('filiacao', [InscriptionController::class, 'family'])->name('family');
            Route::post('filiacao', [InscriptionController::class, 'familyStore']);

            Route::get('outras-informacoes', [InscriptionController::class, 'other'])->name('other');
            Route::post('outras-informacoes', [InscriptionController::class, 'otherStore']);

            Route::get('curso', [InscriptionController::class, 'course'])->name('course');
            Route::post('curso', [InscriptionController::class, 'courseStore']);

            Route::get('confirmar-dados', [InscriptionController::class, 'confirm'])->name('confirm');

            Route::post('finalizar', [InscriptionController::class, 'inscriptionStore'])->name('finalize');
        });

    Route::middleware(WithInscription::class)
        ->group(function () {
            Route::prefix('cartao')
                ->name('card.')
                ->group(function () {
                    // PDF Cartão do local de prova
                    Route::get('meu-local', [PdfController::class, 'testLocationCardToPdf'])
                        ->name('exam')
                        ->middleware([isLocationEnabled::class]);

                    // PDF Cartão do resultado da Prova
                    Route::get('resultado', [PdfController::class, 'testResultCardToPdf'])->name('result')->middleware([isResultEnabled::class]);

                    // PDF Cartão de chamada
                    Route::get('chamada', [PdfController::class, 'callCardToPdf'])->name('call');
                });

            Route::prefix('comprovante')
                ->name('receipt.')
                ->group(function () {
                    // PDF Comprovante de inscrição
                    Route::post('inscricao', [PdfController::class, 'inscriptionReceiptToPdf'])->name('to.pdf');
                });
        });
});

Route::get('erro', fn() => view('inscription.failed'))->name('failed');