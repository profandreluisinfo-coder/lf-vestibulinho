<?php

use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PdfController;
use App\Http\Middleware\EnsureInstructionsConfirmed;
use App\Http\Middleware\IsLocationEnabled;
use App\Http\Middleware\IsResultEnabled;
use App\Http\Middleware\NoInscription;
use App\Http\Middleware\WithInscription;
use Illuminate\Support\Facades\Route;

// 🔒 Rotas que exigem login
Route::middleware(['auth'])
    ->name('inscription.')
    ->group(function () {

        Route::middleware(WithInscription::class)
            ->group(function () {
                Route::get('area-do-candidato', [InscriptionController::class, 'show'])
                    ->name('show');

                Route::prefix('cartao')
                    ->name('card.')
                    ->group(function () {
                        // PDF Cartão do local de prova
                        Route::get('meu-local', [PdfController::class, 'locationCardToPdf'])
                            ->middleware([IsLocationEnabled::class])
                            ->name('exam');

                        // PDF Cartão do resultado da Prova
                        Route::get('resultado', [PdfController::class, 'resultCardToPdf'])
                            ->middleware([IsResultEnabled::class])
                            ->name('result');

                        // PDF Cartão de chamada
                        Route::get('chamada', [PdfController::class, 'callCardToPdf'])
                            ->name('call');
                    });

                Route::prefix('comprovante')
                    ->name('receipt.')
                    ->group(function () {
                        // PDF Comprovante de inscrição
                        Route::post('inscricao', [PdfController::class, 'inscriptionReceiptToPdf'])
                            ->name('to.pdf');
                    });
            });

        // Exibe a página com informações sobre como fazer a inscrição
        Route::get('informacoes', [InscriptionController::class, 'start'])
            ->middleware(NoInscription::class)
            ->name('start'); // Início

        Route::post('confirm-instructions', [InscriptionController::class, 'confirmInstructions'])
            ->name('confirm-instructions');

        // 📝 Passos da inscrição
        Route::middleware([EnsureInstructionsConfirmed::class])
            ->name('step.')
            ->prefix('inscricao')
            ->group(function () {

                Route::get('dados-pessoais', [InscriptionController::class, 'personal'])
                    ->name('personal');
                Route::post('dados-pessoais', [InscriptionController::class, 'personalStore']);

                Route::get('certidao-nascimento', [InscriptionController::class, 'certificate'])
                    ->name('certificate');
                Route::post('certidao-nascimento', [InscriptionController::class, 'certificateStore']);

                Route::get('endereco', [InscriptionController::class, 'address'])
                    ->name('address');
                Route::post('endereco', [InscriptionController::class, 'addressStore']);

                Route::get('dados-escolares', [InscriptionController::class, 'academic'])
                    ->name('academic');

                Route::post('dados-escolares', [InscriptionController::class, 'academicStore']);

                Route::get('filiacao', [InscriptionController::class, 'family'])
                    ->name('family');
                Route::post('filiacao', [InscriptionController::class, 'familyStore']);

                Route::get('pcd', [InscriptionController::class, 'pcd'])
                    ->name('pcd');
                Route::post('pcd', [InscriptionController::class, 'pcdStore']);

                Route::get('outras-informacoes', [InscriptionController::class, 'other'])
                    ->name('other');
                Route::post('outras-informacoes', [InscriptionController::class, 'otherStore']);

                Route::get('curso', [InscriptionController::class, 'course'])
                    ->name('course');
                Route::post('curso', [InscriptionController::class, 'courseStore']);

                Route::get('confirmar-dados', [InscriptionController::class, 'confirm'])
                    ->name('confirm');

                Route::post('finalizar', [InscriptionController::class, 'store'])
                    ->name('store');
            });
    });

Route::get('erro', fn () => view('inscription.failed'))
    ->name('failed');
