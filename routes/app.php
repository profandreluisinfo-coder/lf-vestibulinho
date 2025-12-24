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
    LocalController,
    NoticeController,
    PdfController,
    ResultController,
    SettingController
};

// 🔒 Rotas que exigem login
Route::middleware(['auth', IsAdmin::class])->group(function () {
    // ==========================
    // ❓ Perguntas Frequentes (FAQ)
    // ==========================
    Route::prefix('faq')
        ->name('faqs.')
        ->group(function () {
            Route::get('/', [FaqController::class, 'index'])->name('admin.index');
            Route::post('/gravar', [FaqController::class, 'store'])->name('admin.store');
            Route::get('/editar/{faq}', [FaqController::class, 'edit'])->name('admin.edit');
            Route::post('/editar/{faq}', [FaqController::class, 'update']);
            Route::delete('/excluir/{faq}', [FaqController::class, 'destroy'])->name('admin.destroy');
            Route::put('/publicar/{faq}', [FaqController::class, 'publish'])->name('publish');
            Route::put('/update-order', [FaqController::class, 'updateOrder'])->name('updateOrder');
        });

    // ==========================
    // 🗓️ Calendário
    // ==========================
    Route::prefix('calendario')
        ->name('calendar.')
        ->group(function () {
            Route::get('/', [CalendarController::class, 'index'])->name('admin.index');
            Route::get('/editar', [CalendarController::class, 'edit'])->name('admin.edit');
            Route::post('/salvar', [CalendarController::class, 'save'])->name('admin.save');
        });

    // ==========================
    // 📄 Editais
    // ==========================
    Route::prefix('edital')
        ->name('notice.')
        ->group(function () {
            Route::get('/', [NoticeController::class, 'index'])->name('admin.index');
            // Route::get('/criar', [NoticeController::class, 'create'])->name('create');
            Route::post('/salvar', [NoticeController::class, 'store'])->name('store');
            // Route::get('/editar/{notice}', [NoticeController::class, 'edit'])->name('edit');admin.
            Route::post('/editar/{notice}', [NoticeController::class, 'update']);
            Route::delete('/excluir/{notice}', [NoticeController::class, 'destroy'])->name('destroy');
            Route::put('/publicar/{notice}', [NoticeController::class, 'publish'])->name('publish');
        });

    // ==========================
    // 🧾 Provas
    // ==========================
    Route::prefix('prova')
        ->name('exam.')
        ->group(function () {
            Route::get('/salas', [ExamController::class, 'index'])->name('admin.index');
            Route::get('/agendar', [ExamController::class, 'create'])->name('admin.create');
            Route::post('/salvar', [ExamController::class, 'store'])->name('admin.store');
        });

    // ==========================
    // 🎓 Cursos
    // ==========================
    Route::prefix('cursos')
        ->name('courses.')
        ->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('admin.index');
            Route::post('/salvar', [CourseController::class, 'store'])->name('admin.store');
            Route::get('/editar/{course}', [CourseController::class, 'edit'])->name('admin.edit');
            Route::post('/editar/{course}', [CourseController::class, 'update']);
            Route::delete('/excluir/{course}', [CourseController::class, 'destroy'])->name('admin.destroy');
        });

    // ==========================
    // 📚 Arquivos (Provas anteriores)
    // ==========================
    Route::prefix('arquivos')
        ->name('archives.') // pasta
        ->group(function () {
            Route::get('/', [ArchiveController::class, 'index'])->name('admin.index'); // pasta.view
            // Route::get('/criar', [ArchiveController::class, 'create'])->name('admin.create');
            Route::post('/salvar', [ArchiveController::class, 'store'])->name('admin.store');
            Route::get('/editar/{archive}', [ArchiveController::class, 'edit'])->name('admin.edit');
            Route::post('/editar/{archive}', [ArchiveController::class, 'update']);
            Route::delete('/excluir/{archive}', [ArchiveController::class, 'destroy'])->name('admin.destroy');
            Route::put('/publicar/{archive}', [ArchiveController::class, 'publish'])->name('admin.publish');
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
        ->name('import.')
        ->group(function () {
            Route::get('/notas', [ImportController::class, 'home'])->name('admin.home');
            Route::post('/notas', [ImportController::class, 'import']);
        });

    // ==========================
    // 📊 PDFs
    // ==========================
    Route::prefix('pdf')
        ->name('pdf.')
        ->group(function () {
            // PDFs
            Route::get('/alocacao', [PdfController::class, 'allocationsToPdf'])->name('allocation');
            Route::get('/salas', [PdfController::class, 'roomsToPdf'])->name('rooms');
            Route::get('/assinaturas', [PdfController::class, 'signaturesToPdf'])->name('signs');
            Route::get('/inscricoes', [PdfController::class, 'allInscriptionsToPdf'])->name('inscriptions');
        });

    // ==========================
    // 📞 Chamadas
    // ==========================
    Route::prefix('chamadas') // OK
        ->name('calls.')
        ->group(function () {
            Route::get('/criar', [CallController::class, 'create'])->name('admin.create');
            Route::post('/criar', [CallController::class, 'store'])->name('admin.store');
            Route::delete('/apagar/{callList}', [CallController::class, 'destroy'])->name('admin.destroy');
            Route::get('/numero/{call_number}', [CallController::class, 'show'])->name('admin.show');
            Route::patch('/{callList}/finalizar', [CallController::class, 'finalize'])->name('admin.finalize');
            Route::get('/calls/{call_number}/excel', [CallController::class, 'excel'])->name('admin.excel');
            Route::get('/calls/{call_number}/pdf', [CallController::class, 'pdf'])->name('admin.pdf');
        });

    // ==========================
    // 🧾 Local
    // ==========================
    Route::prefix('local')
        ->name('local.')
        ->group(function () {
            Route::get('/', [LocalController::class, 'index'])->name('admin.index');
            // Route::get('/criar', [LocalController::class, 'create'])->name('create');
            Route::post('/salvar', [LocalController::class, 'store'])->name('store');
            Route::get('/editar/{location}', [LocalController::class, 'edit'])->name('admin.edit');
            Route::post('/editar/{location}', [LocalController::class, 'update']);
            Route::delete('/excluir/{location}', [LocalController::class, 'destroy'])->name('destroy');
        });

    // ==========================
    // 🏆 Resultados
    // ==========================
    Route::prefix('resultados') // OK
        ->name('result.')
        ->group(function () {
            Route::get('/notas-e-classificacao', [ResultController::class, 'index'])->name('index');
        });

    // ==========================
    // ⚙️ Configurações do Sistema
    // ==========================
    Route::prefix('sistema') // OK
        ->name('system.')
        ->group(function () {
            Route::get('/redefinir-dados', [SettingController::class, 'index'])->name('index');
            Route::get('/apagar-dados', [SettingController::class, 'reset'])->name('reset');
            
            Route::post('/liberar-acesso-calendario', [SettingController::class, 'calendar'])->name('publish.calendar');
            Route::post('/liberar-acesso-local', [SettingController::class, 'location'])->name('publish.location');
            Route::post('/liberar-acesso-resultados', [SettingController::class, 'result'])->name('publish.result');
        });
});