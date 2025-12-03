<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\{
    FaqController,
    CallController,
    ExamController,
    UserController,
    AdminController,
    CourseController,
    ExportController,
    ImportController,
    NoticeController,
    ReportController,
    SystemController,
    ArchiveController,
    RankingController,
    CalendarController,
    InscriptionController,
    QueueMonitorController
};

//
// 🏠 Rotas públicas
//
Route::middleware(['guest'])->group(function () {
    // Login de admins
    Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
});

// 🔒 Rotas que exigem login
Route::middleware(['auth', IsAdmin::class])->group(function () {

    //
    // 🛠️ Área administrativa
    //

    // Painel principal
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/painel-administrativo', [AdminController::class, 'index'])->name('painel'); // OK
        }); // Fim Painel do admin

    // ==========================
    // 🎓 Cursos
    // ==========================
    Route::prefix('cursos') // OK
        ->name('courses.')
        ->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('index');
            Route::get('/criar', [CourseController::class, 'create'])->name('create');
            Route::post('/criar', [CourseController::class, 'store']);
            Route::get('/editar/{course}', [CourseController::class, 'edit'])->name('edit');
            Route::post('/editar/{course}', [CourseController::class, 'update']);
            Route::delete('/excluir/{course}', [CourseController::class, 'destroy'])->name('destroy');
        });

    // ==========================
    // 📄 Editais
    // ==========================
    Route::prefix('edital') // OK
        ->name('notice.')
        ->group(function () {
            Route::get('/', [NoticeController::class, 'index'])->name('index');
            Route::get('/criar', [NoticeController::class, 'create'])->name('create');
            Route::post('/criar', [NoticeController::class, 'store']);
            Route::get('/editar/{notice}', [NoticeController::class, 'edit'])->name('edit');
            Route::post('/editar/{notice}', [NoticeController::class, 'update']);
            Route::delete('/excluir/{notice}', [NoticeController::class, 'destroy'])->name('destroy');
            Route::put('/publicar/{notice}', [NoticeController::class, 'publish'])->name('publish');
        });

    // ==========================
    // 📚 Arquivos (Provas anteriores)
    // ==========================
    Route::prefix('arquivos') // OK
        ->name('archive.')
        ->group(function () {
            Route::get('/', [ArchiveController::class, 'index'])->name('index');
            Route::get('/criar', [ArchiveController::class, 'create'])->name('create');
            Route::post('/criar', [ArchiveController::class, 'store']);
            Route::get('/editar/{archive}', [ArchiveController::class, 'edit'])->name('edit');
            Route::post('/editar/{archive}', [ArchiveController::class, 'update']);
            Route::delete('/excluir/{archive}', [ArchiveController::class, 'destroy'])->name('destroy');
            Route::put('/publicar/{archive}', [ArchiveController::class, 'publish'])->name('publish');
        });

    // ==========================
    // 📤 Exportar Dados
    // ==========================
    Route::prefix('exportar') // OK
        ->name('export.')
        ->group(function () {
            Route::get('/candidatos', [ExportController::class, 'exportUsers'])->name('users');
        });

    // ==========================
    // 📥 Importar Dados
    // ==========================
    Route::prefix('importar') // OK
        ->name('import.')
        ->group(function () {
            Route::get('/notas', [ImportController::class, 'index'])->name('results');
            Route::post('/notas', [ImportController::class, 'import']);
        });

    // ==========================
    // ⚙️ Configurações do Sistema
    // ==========================
    Route::prefix('sistema') // OK
        ->name('system.')
        ->group(function () {
            Route::get('/', [SystemController::class, 'index'])->name('index');
            Route::get('/redefinir-dados', [SystemController::class, 'reset'])->name('reset');
            Route::get('/filas', [QueueMonitorController::class, 'index'])->name('queue.monitor');
            Route::post('/filas/retry', [QueueMonitorController::class, 'retryFailed'])->name('queue.retry');
            Route::post('/filas/flush', [QueueMonitorController::class, 'flushFailed'])->name('queue.flush');
        });

    // ==========================
    // ❓ Perguntas Frequentes (FAQ)
    // ==========================
    Route::prefix('faq') // OK
        ->name('faq.')
        ->group(function () {
            Route::get('/', [FaqController::class, 'index'])->name('index');
            // Route::get('/criar', [FaqController::class, 'create'])->name('create');
            Route::post('/gravar', [FaqController::class, 'store'])->name('store');
            Route::get('/editar/{faq}', [FaqController::class, 'edit'])->name('edit');
            Route::post('/editar/{faq}', [FaqController::class, 'update']);
            Route::delete('/excluir/{faq}', [FaqController::class, 'destroy'])->name('destroy');
            Route::put('/publicar/{faq}', [FaqController::class, 'publish'])->name('publish');
            Route::put('/update-order', [FaqController::class, 'updateOrder'])->name('updateOrder');
        });

    // ==========================
    // 🗓️ Calendário
    // ==========================
    Route::prefix('calendario') // OK
        ->name('calendar.')
        ->group(function () {
            Route::get('/', [CalendarController::class, 'index'])->name('index');
            Route::get('/calendar/edit', [CalendarController::class, 'edit'])->name('edit');
            Route::post('/calendar', [CalendarController::class, 'store'])->name('store');
        });

    // Usuários sem inscrição
    Route::prefix('usuarios-sem-inscricao') // OK
        ->name('users.')
        ->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
        });

    // ==========================
    // 📊 Relatórios
    // ==========================
    Route::prefix('relatorios') // OK
        ->name('report.')
        ->group(function () {
            // Relatórios
            Route::get('/alocacao', [ReportController::class, 'exportAllocationToPdf'])->name('exportAllocationToPdf');
            Route::get('/salas', [ReportController::class, 'exportRoomsToPdf'])->name('exportRoomsToPdf');
            Route::get('/assinaturas', [ReportController::class, 'exportSignaturesSheetsToPdf'])->name('exportSignaturesSheetsToPdf');
        });

    // ==========================
    // 🏆 Ranking de Provas
    // ==========================
    Route::get('/notas-e-classificacao', [RankingController::class, 'index'])->name('ranking');
    Route::post('/liberar-classificacao', [RankingController::class, 'setAccessToResult'])->name('setAccessToResult');

    // ==========================
    // 📞 Chamadas
    // ==========================
    Route::prefix('chamadas') // OK
        ->name('callings.')
        ->group(function () {
            Route::get('/numero/{call_number}', [CallController::class, 'show'])->name('show');
            Route::get('/criar', [CallController::class, 'create'])->name('create');
            Route::post('/criar', [CallController::class, 'store'])->name('store');
            Route::delete('/apagar/{callList}', [CallController::class, 'destroy'])->name('destroy');
            Route::patch('/{callList}/finalizar', [CallController::class, 'finalize'])->name('finalize');
            Route::get('/calls/{call_number}/excel', [CallController::class, 'excel'])->name('excel');
            Route::get('/calls/{call_number}/pdf', [CallController::class, 'pdf'])->name('pdf');
        });

    // ==========================
    // 🧾 Provas
    // ==========================
    Route::prefix('prova') // OK
        ->name('exam.')
        ->group(function () {
            // Prova (local)
            // Route::get('/configuracoes', fn() => view('exam.private.settings'))->name('settings');
            Route::get('/salas', [ExamController::class, 'index'])->name('index');

            Route::get('/agendar', [ExamController::class, 'create'])->name('create');
            Route::post('/agendar', [ExamController::class, 'store']);

            Route::get('/local', [ExamController::class, 'locations'])->name('locations'); // Locais de prova
            Route::post('/local', [ExamController::class, 'storeLocations']);

            Route::get('editar/{id}', [ExamController::class, 'edit'])->name('update');
            Route::post('editar/{id}', [ExamController::class, 'update']);

            Route::delete('excluir/{id}', [ExamController::class, 'destroy'])->name('destroy');

            Route::post('/config/access/location', [ExamController::class, 'setAccessToLocation'])->name('access.location');
        });
        
}); // Fim Middleware de autenticado