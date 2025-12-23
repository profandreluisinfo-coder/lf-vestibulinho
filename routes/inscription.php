<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\{
    InscriptionController,
};

use App\Http\Middleware\{NotAdmin, IsAdmin, NoInscription};

// 🔒 Rotas que exigem login
Route::middleware(['auth'])->group(function () {

    // 📝 Processo de inscrição
    Route::middleware([NotAdmin::class])->group(function () {

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


        // Novo caminho para fazer a inscrição (com inscrição existente)
        Route::prefix('formulario-de-inscricao')
            ->name('form.')
            ->middleware([NoInscription::class])
            ->group(function () {
                Route::get('/', [InscriptionController::class, 'create'])->name('inscription');
            });
    });


    // ==========================
    // 🧾 Inscrições
    // ==========================
    Route::middleware([IsAdmin::class])->group(function () {

        Route::prefix('inscricoes') // OK
            ->name('inscriptions.')
            ->group(function () {

                // Lista de inscrições
                Route::get('/', [InscriptionController::class, 'index'])->name('index');
                Route::post('/inscriptions/data', [InscriptionController::class, 'getInscriptionsData'])
                    ->name('get.data');

                // Lista de pessoas com deficiência
                Route::get('/pessoas-com-deficiencia', [InscriptionController::class, 'pcd'])
                    ->name('pcd');
                Route::post('/pcd-data', [InscriptionController::class, 'getPCDData'])
                    ->name('pcd.data');
                    
                // Candidatos com nome social
                Route::get('/nome-social', [InscriptionController::class, 'socialName'])
                    ->name('social.name');

                Route::get('/inscricao/pdf', [InscriptionController::class, 'exportPdf'])
                    ->name('pdf');

                Route::get('/candidato/{id}', [InscriptionController::class, 'show'])
                    ->name('show');
            });
    });
});