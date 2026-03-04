<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\{
    IsAdmin
};

use App\Http\Controllers\App\{
    ArchiveController,
    FaqController,
    CalendarController,
    CourseController,
    CallController,
    ExamController,
    ExportController,
    ImportController,
    InscriptionController,
    LocalController,
    NoticeController,
    PdfController,
    ResultController,
    SettingController
};

// 🔒 Rotas que exigem login
Route::middleware(['auth', IsAdmin::class])->group(function () {

    // ==========================
    // ❓ Perguntas Frequentes (FAQ) (OK)
    // ==========================
    Route::prefix('faq')
        ->name('app.faqs.')
        ->group(function () {
            Route::get('/', [FaqController::class, 'index'])->name('index');
            Route::post('/gravar', [FaqController::class, 'store'])->name('store');
            Route::get('/editar/{faq}', [FaqController::class, 'edit'])->name('edit');
            Route::post('/editar/{faq}', [FaqController::class, 'update']);
            Route::delete('/excluir/{faq}', [FaqController::class, 'destroy'])->name('destroy');
            Route::put('/publicar/{faq}', [FaqController::class, 'publish'])->name('publish');
            Route::put('/update-order', [FaqController::class, 'updateOrder'])->name('updateOrder');
        });

    // ==========================
    // 🗓️ Calendário (OK)
    // ==========================
    Route::prefix('calendario')
        ->name('app.calendar.')
        ->group(function () {
            Route::get('/', [CalendarController::class, 'index'])->name('index');
            Route::get('/editar', [CalendarController::class, 'edit'])->name('edit');
            Route::post('/salvar', [CalendarController::class, 'save'])->name('save');
        });

    // ==========================
    // 📄 Editais (OK)
    // ==========================
    Route::prefix('edital')
        ->name('app.notices.')
        ->group(function () {
            Route::get('/', [NoticeController::class, 'index'])->name('index');
            Route::post('/salvar', [NoticeController::class, 'store'])->name('store');
            Route::post('/editar/{notice}', [NoticeController::class, 'update']);
            Route::delete('/excluir/{notice}', [NoticeController::class, 'destroy'])->name('destroy');
            // Route::put('/publicar/{notice}', [NoticeController::class, 'publish'])->name('publish');
        });

    // ==========================
    // 🧾 Alocação para Provas OK
    // ==========================
    Route::prefix('prova')
        ->name('app.exam.')
        ->group(function () {
            Route::get('/salas', [ExamController::class, 'index'])->name('index');
            Route::get('/agendar', [ExamController::class, 'create'])->name('create');
            Route::post('/salvar', [ExamController::class, 'store'])->name('store');
        });

    // ==========================
    // 🎓 Cursos (OK)
    // ==========================
    Route::prefix('cursos')
        ->name('app.courses.')
        ->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('index');
            Route::post('/salvar', [CourseController::class, 'store'])->name('store');
            Route::get('/editar/{course}', [CourseController::class, 'edit'])->name('edit');
            Route::post('/editar/{course}', [CourseController::class, 'update']);
            Route::delete('/excluir/{course}', [CourseController::class, 'destroy'])->name('destroy');
        });

    // ==========================
    // 📚 Arquivos (Provas anteriores) OK
    // ==========================
    Route::prefix('arquivos')
        ->name('app.archives.') // pasta
        ->group(function () {
            Route::get('/', [ArchiveController::class, 'index'])->name('index'); // pasta.view
            // Route::get('/criar', [ArchiveController::class, 'create'])->name('create');
            Route::post('/salvar', [ArchiveController::class, 'store'])->name('store');
            Route::get('/editar/{archive}', [ArchiveController::class, 'edit'])->name('edit');
            Route::post('/editar/{archive}', [ArchiveController::class, 'update']);
            Route::delete('/excluir/{archive}', [ArchiveController::class, 'destroy'])->name('destroy');
            Route::put('/publicar/{archive}', [ArchiveController::class, 'publish'])->name('publish');
        });

    // ==========================
    // 📤 Exportar Dados
    // ==========================
    Route::prefix('exportar')
        ->name('export.')
        ->group(function () {
            Route::get('/candidatos', [ExportController::class, 'exportUsers'])->name('users');
        });

    // ==========================
    // 📥 Importar Dados
    // ==========================
    Route::prefix('importar')
        ->name('app.import.')
        ->group(function () {
            Route::get('/notas', [ImportController::class, 'home'])->name('home');
            Route::post('/notas', [ImportController::class, 'import']);
        });

    // ==========================
    // 📊 Alocações
    // ==========================
    Route::prefix('alocacoes')
        ->name('app.allocations.')
        ->group(function () {
            // PDFs
            Route::get('/alocacao', [PdfController::class, 'allocationsToPdf'])->name('allocation');
            Route::get('/salas', [PdfController::class, 'roomsToPdf'])->name('rooms');
            Route::get('/assinaturas', [PdfController::class, 'signaturesToPdf'])->name('signs');
        });
        
    // ==========================
    // 📊 PDFs
    // ==========================
    Route::prefix('pdf')
        ->name('pdf.')
        ->group(function () {
            // PDFs
            Route::get('/inscricoes', [PdfController::class, 'allInscriptionsToPdf'])->name('inscriptions');
        });

    // ==========================
    // 📞 Chamadas OK
    // ==========================
    Route::prefix('chamadas')
        ->name('app.calls.')
        ->group(function () {
            Route::get('/registros', [CallController::class, 'index'])->name('index');
            Route::post('/salvar', [CallController::class, 'store'])->name('store');
            Route::delete('/apagar/{callList}', [CallController::class, 'destroy'])->name('destroy');
            Route::get('/numero/{call_number}', [CallController::class, 'show'])->name('show');
            Route::patch('/{callList}/finalizar', [CallController::class, 'finalize'])->name('finalize');
            Route::get('/calls/{call_number}/excel', [CallController::class, 'excel'])->name('excel');
            Route::get('/calls/{call_number}/pdf', [CallController::class, 'pdf'])->name('pdf');
        });

    // ==========================
    // 🧾 Local de Prova OK
    // ==========================
    Route::prefix('local')
        ->name('app.local.')
        ->group(function () {
            Route::get('/', [LocalController::class, 'index'])->name('index');
            // Route::get('/criar', [LocalController::class, 'create'])->name('create');
            Route::post('/salvar', [LocalController::class, 'store'])->name('store');
            Route::get('/editar/{location}', [LocalController::class, 'edit'])->name('edit');
            Route::post('/editar/{location}', [LocalController::class, 'update']);
            Route::delete('/excluir/{location}', [LocalController::class, 'destroy'])->name('destroy');
        });

    // ==========================
    // 🏆 Resultados OK
    // ==========================
    Route::prefix('resultados')
        ->name('app.results.')
        ->group(function () {
            Route::get('/notas-e-classificacao', [ResultController::class, 'index'])->name('index');
        });

    // ==========================
    // ⚙️ Configurações do Sistema OK
    // ==========================
    Route::prefix('sistema')
        ->name('app.system.')
        ->group(function () {
            Route::get('/redefinir-dados', [SettingController::class, 'index'])->name('index');
            Route::get('/apagar-dados', [SettingController::class, 'reset'])->name('reset');

            Route::post('/liberar-acesso-calendario', [SettingController::class, 'calendar'])->name('publish.calendar');
            Route::post('/liberar-acesso-local', [SettingController::class, 'location'])->name('publish.location');
            Route::post('/liberar-acesso-resultados', [SettingController::class, 'result'])->name('publish.result');
            Route::put('/liberar-acesso-edital', [SettingController::class, 'notice'])->name('publish.notice');
        });

    // ==========================
    // 📋 Inscrições (Visualização de inscrições por Admin)
    // ==========================
    Route::prefix('inscricoes')
        ->name('app.inscriptions.')
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