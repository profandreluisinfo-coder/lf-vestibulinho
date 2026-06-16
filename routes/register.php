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
    PdfController
};

use App\Http\Controllers\Auth\{
    RegisterController
};

use App\Http\Controllers\Dash\{
    UserController
};

// 🔒 Rotas que exigem login
Route::middleware(['auth', NotAdmin::class])
    ->prefix('vestibulinho')
    ->name('inscription.')
    ->group(function () {

        // 📝 Processo de inscrição
        Route::middleware(NoInscription::class)
            ->prefix('inscricao')
            ->name('step.')
            ->group(function () {
                // Area do candidato: exibe a página com informações sobre como fazer a inscrição
                Route::get('/', [UserController::class, 'index'])->name('start'); // pasta e view
                Route::get('dados-pessoais', [RegisterController::class, 'personal'])->name('personal');
                Route::post('dados-pessoais', [RegisterController::class, 'personalStore']);

                Route::get('certidao-nascimento', [RegisterController::class, 'certificate'])->name('certificate');
                Route::post('certidao-nascimento', [RegisterController::class, 'certificateStore']);

                Route::get('endereco', [RegisterController::class, 'address'])->name('address');
                Route::post('endereco', [RegisterController::class, 'addressStore']);

                Route::get('dados-escolares', [RegisterController::class, 'academic'])->name('academic');
                Route::post('dados-escolares', [RegisterController::class, 'academicStore']);

                Route::get('filiacao', [RegisterController::class, 'family'])->name('family');
                Route::post('filiacao', [RegisterController::class, 'familyStore']);

                Route::get('outras-informacoes', [RegisterController::class, 'other'])->name('other');
                Route::post('outras-informacoes', [RegisterController::class, 'otherStore']);

                Route::get('curso', [RegisterController::class, 'course'])->name('course');
                Route::post('curso', [RegisterController::class, 'courseStore']);

                Route::get('confirmar-dados', [RegisterController::class, 'confirm'])->name('confirm');

                Route::post('finalizar', [RegisterController::class, 'inscriptionStore'])->name('finalize');
            });

        Route::middleware(WithInscription::class)
            ->group(function () {
                Route::prefix('cartao')
                    ->name('card.')
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
                    ->group(function () {
                        // PDF Comprovante de inscrição
                        Route::post('/inscricao', [PdfController::class, 'inscriptionReceiptToPdf'])->name('to.pdf');
                    });

                // Área do candidato: exibe o perfil da inscrição existente
                Route::get('/candidato', [UserController::class, 'show'])->name('show');
            });
    });

Route::get('erro', fn() => view('app.registration.failed'))->name('failed');
