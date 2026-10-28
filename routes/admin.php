<?php

use App\Http\Controllers\Admin\{AdminController, AppealController, ArchiveController, CallController, CourseController, AnalyseController, ExamController, ExportController, FaqController, ImportController, InscriptionController, LocalController, NoticeController, PostController, ProcessController, PublicationController, ReportController, SettingController, ChartController, TemplateController, UserController};
use App\Http\Controllers\Admin\CourseProcessController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PdfController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// Login de admins
Route::middleware(['guest'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('login', [LoginController::class, 'loginForAdmin'])
            ->name('auth')
            ->middleware('throttle:3,1');
    });

Route::prefix('admin')
    ->middleware(['auth', IsAdmin::class])
    ->name('admin.')
    ->group(function () {

        Route::get('inicio', [AdminController::class, 'index'])
            ->name('index');

        Route::get('graficos', [ChartController::class, 'index'])
            ->name('charts.index');

        Route::prefix('inscricoes')
            ->name('inscriptions.')
            ->group(function () {
                Route::get('inscricoes', [InscriptionController::class, 'index'])
                    ->name('index');
                Route::post('inscriptions/data', [InscriptionController::class, 'getData'])
                    ->name('get.data');
                Route::get('detalhes/{user}', [InscriptionController::class, 'show'])
                    ->name('show');
                Route::get('pessoas-com-deficiencia', [InscriptionController::class, 'pcds'])
                    ->name('pcds');
                Route::post('pcd-data', [InscriptionController::class, 'getPcd'])
                    ->name('pcd.data');
                Route::get('nome-social', [InscriptionController::class, 'lgbts'])
                    ->name('lgbts');
                
                Route::get('editar/{user}', [InscriptionController::class, 'edit'])->name('edit');
                Route::put('editar/{user}', [InscriptionController::class, 'update'])->name('update');
                Route::get('relatorio', [InscriptionController::class, 'pdf'])
                    ->name('pdf');
            });

        Route::post('posts/preview', [PostController::class, 'previewNew'])
            ->name('posts.preview.new');
        Route::post('posts/{post}/preview', [PostController::class, 'previewExisting'])
            ->name('posts.preview');
        Route::resource('posts', PostController::class);
        Route::delete('posts/{post}/attachments/{attachment}', [PostController::class, 'destroyAttachment'])
            ->name('posts.attachments.destroy');
        Route::patch('posts/{post}/toggle', [PostController::class, 'togglePublished'])
            ->name('posts.toggle');

        // Perguntas frequentes
        Route::prefix('faq')
            ->name('faqs.')
            ->group(function () {
                Route::get('/', [FaqController::class, 'index'])
                    ->name('index');
                Route::post('gravar', [FaqController::class, 'store'])
                    ->name('store');
                Route::get('editar/{faq}', [FaqController::class, 'edit'])
                    ->name('edit');
                Route::put('editar/{faq}', [FaqController::class, 'update'])
                    ->name('update');
                Route::delete('excluir/{faq}', [FaqController::class, 'destroy'])
                    ->name('destroy');
                Route::put('publicar/{faq}', [FaqController::class, 'publish'])
                    ->name('publish');
                Route::put('update-order', [FaqController::class, 'updateOrder'])
                    ->name('updateOrder');
            });

        // Processo Seltivo
        Route::prefix('processo-seletivo')
            ->name('process.')
            ->group(function () {
                Route::get('detalhes', [ProcessController::class, 'show'])
                    ->name('show');
                Route::get('editar', [ProcessController::class, 'edit'])
                    ->name('edit');
                Route::put('atualizar', [ProcessController::class, 'update'])
                    ->name('update');
                Route::put('ativar/{sp}', [ProcessController::class, 'activate'])
                    ->name('activate');
                Route::post('liberacao/{type}', [ProcessController::class, 'toggleRelease'])
                    ->whereIn('type', ['location', 'result'])
                    ->name('release');
            });

        // Edital
        Route::prefix('edital')
            ->name('notices.')
            ->group(function () {
                Route::get('/', [NoticeController::class, 'index'])
                    ->name('index');
                Route::post('salvar', [NoticeController::class, 'store'])
                    ->name('store');
                Route::put('atualizar', [NoticeController::class, 'update'])
                    ->name('update');
                Route::delete('excluir/{notice}', [NoticeController::class, 'destroy'])
                    ->name('destroy');
            });

        // Provas
        Route::prefix('prova')
            ->name('exam.')
            ->group(function () {
                Route::get('salas', [ExamController::class, 'show'])
                    ->name('show');
                Route::get('agendar', [ExamController::class, 'create'])
                    ->name('create');
                Route::post('salvar', [ExamController::class, 'store'])
                    ->name('store');
            });

        // Modelos de documentos
        Route::prefix('modelos')
            ->name('templates.')
            ->group(function () {
                Route::get('/', [TemplateController::class, 'index'])
                    ->name('index');
                Route::get('visualizar/{template}', [TemplateController::class, 'preview'])
                    ->name('preview');
                Route::post('salvar', [TemplateController::class, 'store'])
                    ->name('store');
                Route::get('editar/{template}', [TemplateController::class, 'edit'])
                    ->name('edit');
                Route::put('atualizar/{template}', [TemplateController::class, 'update'])
                    ->name('update');
                Route::delete('excluir/{template}', [TemplateController::class, 'destroy'])
                    ->name('destroy');
                Route::put('publicar/{template}', [TemplateController::class, 'publish'])
                    ->name('publish');
            });

        // Cursos: dados permanentes
        Route::prefix('cursos')
            ->name('courses.')
            ->controller(CourseController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('salvar', 'store')->name('store');
                Route::get('editar/{course}', 'edit')->name('edit');
                Route::put('atualizar/{course}', 'update')->name('update');
                Route::delete('excluir/{course}', 'destroy')->name('destroy');
            });

        // Cursos por vestibulinho: vagas e associações
        Route::prefix('cursos-vestibulinhos')
            ->name('course-processes.')
            ->controller(CourseProcessController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('criar', 'create')->name('create');
                Route::post('salvar', 'store')->name('store');
                Route::get('editar/{courseProcess}', 'edit')->name('edit');
                Route::put('atualizar/{courseProcess}', 'update')->name('update');
                Route::delete('excluir/{courseProcess}', 'destroy')->name('destroy');
            });

        // Arquivos
        Route::prefix('arquivos')
            ->name('archives.')
            ->group(function () {
                Route::get('/', [ArchiveController::class, 'index'])
                    ->name('index');
                Route::post('salvar', [ArchiveController::class, 'store'])
                    ->name('store');
                Route::get('editar/{archive}', [ArchiveController::class, 'edit'])
                    ->name('edit');
                Route::put('atualizar/{archive}', [ArchiveController::class, 'update'])
                    ->name('update');
                Route::delete('excluir/{archive}', [ArchiveController::class, 'destroy'])
                    ->name('destroy');
                Route::put('publicar/{archive}', [ArchiveController::class, 'publish'])
                    ->name('publish');
            });

        // Publicações (liberação das listas públicas)
        Route::prefix('publicacoes')
            ->name('publications.')
            ->group(function () {
                Route::get('/', [PublicationController::class, 'index'])
                    ->name('index');
                Route::patch('{list}', [PublicationController::class, 'toggle'])
                    ->name('toggle');
            });

        // Exportação
        Route::prefix('exportar')
            ->name('export.')
            ->group(function () {
                Route::get('candidatos', [ExportController::class, 'exporToExcel'])
                    ->name('excel');
                Route::get('cursos/pdf', [AdminController::class, 'exportCoursesPdf'])
                    ->name('courses.pdf');

                Route::get('cursos/excel', [AdminController::class, 'exportCoursesExcel'])
                    ->name('courses.excel');

                Route::get('sexos/pdf', [AdminController::class, 'exportGendersPdf'])
                    ->name('genders.pdf');

                Route::get('sexos/excel', [AdminController::class, 'exportGendersExcel'])
                    ->name('genders.excel');

                Route::get('sexo-por-curso/pdf', [AdminController::class, 'exportGenderPerCoursePdf'])
                    ->name('gender-course.pdf');

                Route::get('sexo-por-curso/excel', [AdminController::class, 'exportGenderPerCourseExcel'])
                    ->name('gender-course.excel');

                Route::get('bairros/pdf', [AdminController::class, 'exportBurghsPdf'])
                    ->name('burghs.pdf');

                Route::get('bairros/excel', [AdminController::class, 'exportBurghsExcel'])
                    ->name('burghs.excel');

                Route::get('escolas/pdf', [AdminController::class, 'exportSchoolsPdf'])
                    ->name('schools.pdf');

                Route::get('escolas/excel', [AdminController::class, 'exportSchoolsExcel'])
                    ->name('schools.excel');
            });

        // Importação
        Route::prefix('importar')
            ->name('import.')
            ->group(function () {
                Route::get('notas', [ImportController::class, 'home'])
                    ->name('home');
                Route::post('notas', [ImportController::class, 'import'])
                    ->name('import');
            });

        // Alocações (PDFs)
        Route::prefix('alocacoes')
            ->name('allocations.')
            ->group(function () {
                Route::get('{type}', [PdfController::class, 'listsToPdf'])
                    ->whereIn('type', ['geral', 'salas', 'assinaturas'])
                    ->name('lists.pdf');
            });

        // Chamadas
        Route::prefix('chamadas')
            ->name('calls.')
            ->group(function () {
                Route::get('registros', [CallController::class, 'index'])->name('index');
                Route::post('salvar', [CallController::class, 'store'])->name('store');
                Route::delete('apagar/{callList}', [CallController::class, 'destroy'])->name('destroy');
                Route::get('numero/{call_number}', [CallController::class, 'show'])->name('show');
                Route::patch('{callList}/finalizar', [CallController::class, 'finalize'])->name('finalize');
                Route::get('numero/{call_number}/excel', [CallController::class, 'excel'])->name('excel');
                Route::get('numero/{call_number}/pdf', [CallController::class, 'pdf'])->name('pdf');
            });

        // Local
        Route::prefix('local')
            ->name('local.')
            ->group(function () {
                Route::get('/', [LocalController::class, 'index'])->name('index');
                Route::post('salvar', [LocalController::class, 'store'])->name('store');
                Route::get('editar/{location}', [LocalController::class, 'edit'])->name('edit');
                Route::put('editar/{location}', [LocalController::class, 'update'])->name('update');
                Route::delete('excluir/{location}', [LocalController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('relatorios')
            ->name('reports.')
            ->group(function () {
                Route::get('geral', [ReportController::class, 'general'])
                    ->name('general');
                Route::get('geral/pdf', [ReportController::class, 'generalPdf'])
                    ->name('general.pdf');
                Route::get('notas-e-classificacao', [ReportController::class, 'classification'])
                    ->name('classification'); // admin.reports.classification
            });
        // Análises
        Route::prefix('analises')
            ->name('analyses.')
            ->group(function () {

                // Candidatos que solicitaram o uso de Nome Social
                Route::get('nome-social/{user}/deferir', [AnalyseController::class, 'showAcceptAuthorization'])
                    ->name('accept.social.name.form');

                Route::get('nome-social/{user}/indeferir', [AnalyseController::class, 'showRejectAuthorization'])
                    ->name('reject.social.name.form');

                Route::patch('nome-social/{user}/deferir', [AnalyseController::class, 'acceptAuthorization'])
                    ->name('accept.social.name');

                Route::patch('nome-social/{user}/indeferir', [AnalyseController::class, 'rejectAuthorization'])
                    ->name('reject.social.name');

                // Pessoas que apresentaram laudo/relatório médico
                Route::get('relatorio/{user}/deferir', [AnalyseController::class, 'showAcceptReport'])
                    ->name('accept.report.form');

                Route::get('relatorio/{user}/indeferir', [AnalyseController::class, 'showRejectReport'])
                    ->name('reject.report.form');

                Route::patch('relatorio/{user}/deferir', [AnalyseController::class, 'acceptReport'])
                    ->name('accept.report');

                Route::patch('relatorio/{user}/indeferir', [AnalyseController::class, 'rejectReport'])
                    ->name('reject.report');

                Route::get('pdf/{type}/{id}', [AnalyseController::class, 'pdf'])
                    ->whereIn('type', ['pcd', 'lgbt'])
                    ->whereNumber('id')
                    ->name('pdf');
            });

        // Usuários
        Route::prefix('usuarios')
            ->name('users.')
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])
                    ->name('index');
                Route::delete('excluir/{user}', [UserController::class, 'destroy'])
                    ->name('destroy');
            });

        // Recursos (PcD e Nome Social)
        Route::prefix('recursos')
            ->name('appeals.')
            ->group(function () {
                Route::get('/', [AppealController::class, 'index'])
                    ->name('index');

                Route::get('registrar/{user}/{type}', [AppealController::class, 'create'])
                    ->whereIn('type', ['pne', 'lgbt'])
                    ->name('create');

                Route::post('registrar/{user}/{type}', [AppealController::class, 'store'])
                    ->whereIn('type', ['pne', 'lgbt'])
                    ->name('store');

                Route::get('{appeal}', [AppealController::class, 'show'])
                    ->whereNumber('appeal')
                    ->name('show');

                Route::get('{appeal}/pdf', [AppealController::class, 'pdf'])
                    ->whereNumber('appeal')
                    ->name('pdf');

                Route::delete('{appeal}', [AppealController::class, 'destroy'])
                    ->whereNumber('appeal')
                    ->name('destroy');

                Route::patch('{appeal}/deferir', [AppealController::class, 'accept'])
                    ->name('accept');

                Route::patch('{appeal}/indeferir', [AppealController::class, 'reject'])
                    ->name('reject');
            });

        // Sistema
        Route::prefix('sistema')
            ->name('system.')
            ->group(function () {
                Route::get('redefinir-dados', [SettingController::class, 'index'])
                    ->name('index');
                Route::post('resetar', [SettingController::class, 'reset'])
                    ->name('reset');
                Route::post('backups/gerar', [SettingController::class, 'createBackup'])
                    ->name('backups.store');
                Route::get('backups', [SettingController::class, 'listBackups'])
                    ->name('backups.index');
                Route::get('backups/{filename}/download', [SettingController::class, 'downloadBackup'])
                    ->name('backups.download');
                Route::delete('backups/{filename}', [SettingController::class, 'deleteBackup'])
                    ->name('backups.delete');
            });
    });
