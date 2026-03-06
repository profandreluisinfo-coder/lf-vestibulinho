<?php

use App\Http\Controllers\App\{ RegistrationController };
use App\Http\Middleware\{ NotAdmin, NoInscription };
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::middleware([NotAdmin::class])->group(function () {

        // Formulário de inscrição (sem inscrição existente)
        Route::prefix('inscricao')
            ->name('step.')
            ->middleware([NoInscription::class])
            ->group(function () {
                Route::get('/dados-pessoais', [RegistrationController::class, 'personal'])->name('personal');
                Route::post('/dados-pessoais', [RegistrationController::class, 'personalStore']);

                Route::get('/certidao-nascimento', [RegistrationController::class, 'certificate'])->name('certificate');
                Route::post('/certidao-nascimento', [RegistrationController::class, 'certificateStore']);

                Route::get('/endereco', [RegistrationController::class, 'address'])->name('address');
                Route::post('/endereco', [RegistrationController::class, 'addressStore']);

                Route::get('/dados-academicos', [RegistrationController::class, 'academic'])->name('academic');
                Route::post('/dados-academicos', [RegistrationController::class, 'academicStore']);

                Route::get('/filiacao', [RegistrationController::class, 'family'])->name('family');
                Route::post('/filiacao', [RegistrationController::class, 'familyStore']);

                Route::get('/outras-informacoes', [RegistrationController::class, 'other'])->name('other');
                Route::post('/outras-informacoes', [RegistrationController::class, 'otherStore']);

                Route::get('/curso', [RegistrationController::class, 'course'])->name('course');
                Route::post('/curso', [RegistrationController::class, 'courseStore']);

                Route::get('/confirmar-dados', [RegistrationController::class, 'confirm'])->name('confirm');

                Route::post('/finalizar', [RegistrationController::class, 'inscriptionStore'])->name('finalize');

                Route::get('/erro', fn() => view('app.registration.failed'))->name('failed');
            });
    });
});