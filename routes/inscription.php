<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashController,
    InscriptionController,
};

use App\Http\Middleware\{NotAdmin, NoInscription, WithInscription};

// 🔒 Rotas que exigem login
Route::middleware(['auth'])->group(function () {

    // 📝 Processo de inscrição
    Route::middleware([NotAdmin::class])->group(function () {

        Route::get('/dashboard', [DashController::class, 'index'])->name('dashboard.index')->middleware([NoInscription::class]); // OK
        Route::get('/area-do-candidato', [InscriptionController::class, 'profile'])->name('inscription.profile')->middleware([WithInscription::class]); // OK    

        // Formulário de inscrição (sem inscrição existente)
        Route::prefix('inscricao')
            ->name('step.')
            ->middleware([NoInscription::class])
            ->group(function () {
                Route::get('/dados-pessoais', [InscriptionController::class, 'personal'])->name('personal');
                Route::post('/dados-pessoais', [InscriptionController::class, 'personalStore']);

                Route::get('/certidao-nascimento', [InscriptionController::class, 'certificate'])->name('certificate');
                Route::post('/certidao-nascimento', [InscriptionController::class, 'certificateStore']);

                Route::get('/endereco', [InscriptionController::class, 'address'])->name('address');
                Route::post('/endereco', [InscriptionController::class, 'addressStore']);

                Route::get('/dados-academicos', [InscriptionController::class, 'academic'])->name('academic');
                Route::post('/dados-academicos', [InscriptionController::class, 'academicStore']);

                Route::get('/familia', [InscriptionController::class, 'family'])->name('family');
                Route::post('/familia', [InscriptionController::class, 'familyStore']);

                Route::get('/outras-informacoes', [InscriptionController::class, 'other'])->name('other');
                Route::post('/outras-informacoes', [InscriptionController::class, 'otherStore']);

                Route::get('/curso', [InscriptionController::class, 'course'])->name('course');
                Route::post('/curso', [InscriptionController::class, 'courseStore']);

                Route::get('/confirmar-dados', [InscriptionController::class, 'confirm'])->name('confirm');

                Route::post('/finalizar', [InscriptionController::class, 'inscriptionStore'])->name('finalize');

                Route::get('/erro', fn() => view('forms.failed'))->name('failed');
            });
    });

    // PDF genérico
    Route::post('/comprovante-de-inscricao', [InscriptionController::class, 'pdf'])->name('pdf');
});
