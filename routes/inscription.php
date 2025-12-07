<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashController,
    UserController,
    InscriptionController,
};

use App\Http\Middleware\{NotAdmin, IsAdmin, NoInscription, WithInscription};

// 🔒 Rotas que exigem login
Route::middleware(['auth'])->group(function () {

    // 📝 Processo de inscrição
    Route::middleware([NotAdmin::class])->group(function () {

        // Area do candidato: exibe dashboard com as informações de como fazer a inscrição
        Route::get('/dashboard', [DashController::class, 'index'])->name('dashboard.index')->middleware([NoInscription::class]);
        
        // Área do candidato: exibe o perfil da inscrição existente
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

                Route::post('/inscriptions/data', [InscriptionController::class, 'getInscriptionsData'])
                    ->name('getInscriptionsData');

                Route::post('/pcd-data', [InscriptionController::class, 'getPCDData'])
                    ->name('getPCDData');

                Route::get('/', [InscriptionController::class, 'index'])->name('index');

                Route::get('/inscricao/pdf', [InscriptionController::class, 'exportPdf'])
                    ->name('pdf');

                Route::get('/pessoas-com-deficiencia', [InscriptionController::class, 'getListOfPCD'])
                    ->name('pcd');

                Route::get('/lista-geral', [InscriptionController::class, 'getListOfInscriptions'])
                    ->name('general-list');

                Route::get('/nome-social', [InscriptionController::class, 'getListOfSocialName'])
                    ->name('social-name');

                Route::get('/detalhes-do-candidato/{id}', [InscriptionController::class, 'getDetailsOfUser'])
                    ->name('details');

                Route::patch('/users/{user}/clear-social-name', [UserController::class, 'clearSocialName'])
                    ->name('clearSocialName');

                Route::patch('/users/{user}/clear-pne', [UserController::class, 'clearPne'])
                    ->name('clearPne');
            });
    });
    // PDF genérico
    Route::post('/comprovante-de-inscricao', [InscriptionController::class, 'pdf'])->name('pdf');
});
