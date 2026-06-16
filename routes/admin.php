<?php

use App\Http\Controllers\Admin\{
    ArchiveController,
    FaqController,
    CalendarController,
    CommunicateController,
    CommunicateAttachmentController,
    CourseController,
    CallController,
    DeferralController,
    ExamController,
    ExportController,
    ImportController,
    InscriptionController,
    LocalController,
    NoticeController,
    ResultController,
    SettingController,
    UserController,
};

use App\Http\Controllers\PdfController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    IsAdmin::class
])->group(function () {

    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            /*
    |--------------------------------------------------------------------------
    | Inscrições
    |--------------------------------------------------------------------------
    */
            Route::prefix('inscricoes')
                ->name('inscriptions.')
                ->middleware([IsAdmin::class])
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
            /*
    |--------------------------------------------------------------------------
    | Comunicados
    |--------------------------------------------------------------------------
    */

            Route::prefix('comunicados')
                ->name('communicates.')
                ->group(function () {

                    Route::get('/', [CommunicateController::class, 'index'])->name('index');
                    Route::get('criar', [CommunicateController::class, 'create'])->name('create');
                    Route::post('salvar', [CommunicateController::class, 'store'])->name('store');

                    Route::get('visualizar/{communicate}', [CommunicateController::class, 'show'])->name('show');

                    Route::get('editar/{communicate}', [CommunicateController::class, 'edit'])->name('edit');
                    Route::put('editar/{communicate}', [CommunicateController::class, 'update'])->name('update');

                    Route::delete('excluir/{communicate}', [CommunicateController::class, 'destroy'])->name('destroy');

                    Route::delete(
                        'anexos/{attachment}',
                        [CommunicateAttachmentController::class, 'destroy']
                    )->name('attachments.destroy');
                });

            /*
    |--------------------------------------------------------------------------
    | FAQ
    |--------------------------------------------------------------------------
    */

            Route::prefix('faq')
                ->name('faqs.')
                ->group(function () {

                    Route::get('/', [FaqController::class, 'index'])->name('index');
                    Route::post('gravar', [FaqController::class, 'store'])->name('store');

                    Route::get('editar/{faq}', [FaqController::class, 'edit'])->name('edit');
                    Route::put('editar/{faq}', [FaqController::class, 'update'])->name('update');

                    Route::delete('excluir/{faq}', [FaqController::class, 'destroy'])->name('destroy');

                    Route::put('publicar/{faq}', [FaqController::class, 'publish'])->name('publish');
                    Route::put('update-order', [FaqController::class, 'updateOrder'])->name('updateOrder');
                });

            /*
    |--------------------------------------------------------------------------
    | Calendário
    |--------------------------------------------------------------------------
    */

            Route::prefix('calendario')
                ->name('calendar.')
                ->group(function () {

                    Route::get('detalhes', [CalendarController::class, 'show'])->name('show');
                    Route::get('editar', [CalendarController::class, 'edit'])->name('edit');
                    Route::put('atualizar', [CalendarController::class, 'update'])->name('update');
                });

            /*
    |--------------------------------------------------------------------------
    | Editais
    |--------------------------------------------------------------------------
    */

            Route::prefix('edital')
                ->name('notices.')
                ->group(function () {

                    Route::get('/', [NoticeController::class, 'index'])->name('index');
                    Route::post('salvar', [NoticeController::class, 'store'])->name('store');
                    Route::put('atualizar', [NoticeController::class, 'update'])->name('update');
                    Route::delete('excluir/{notice}', [NoticeController::class, 'destroy'])->name('destroy');
                });

            /*
    |--------------------------------------------------------------------------
    | Provas
    |--------------------------------------------------------------------------
    */

            Route::prefix('prova')
                ->name('exam.')
                ->group(function () {

                    Route::get('salas', [ExamController::class, 'index'])->name('index');
                    Route::get('agendar', [ExamController::class, 'create'])->name('create');
                    Route::post('salvar', [ExamController::class, 'store'])->name('store');
                });

            /*
    |--------------------------------------------------------------------------
    | Cursos
    |--------------------------------------------------------------------------
    */

            Route::prefix('cursos')
                ->name('courses.')
                ->group(function () {

                    Route::get('/', [CourseController::class, 'index'])->name('index');
                    Route::post('salvar', [CourseController::class, 'store'])->name('store');

                    Route::get('editar/{course}', [CourseController::class, 'edit'])->name('edit');
                    Route::put('atualizar/{course}', [CourseController::class, 'update'])->name('update');

                    Route::delete('excluir/{course}', [CourseController::class, 'destroy'])->name('destroy');
                });

            /*
    |--------------------------------------------------------------------------
    | Arquivos
    |--------------------------------------------------------------------------
    */

            Route::prefix('arquivos')
                ->name('archives.')
                ->group(function () {

                    Route::get('/', [ArchiveController::class, 'index'])->name('index');

                    Route::post('salvar', [ArchiveController::class, 'store'])->name('store');

                    Route::get('editar/{archive}', [ArchiveController::class, 'edit'])->name('edit');
                    Route::put('atualizar/{archive}', [ArchiveController::class, 'update'])->name('update');

                    Route::delete('excluir/{archive}', [ArchiveController::class, 'destroy'])->name('destroy');

                    Route::put('publicar/{archive}', [ArchiveController::class, 'publish'])->name('publish');
                });

            /*
    |--------------------------------------------------------------------------
    | Exportação
    |--------------------------------------------------------------------------
    */

            Route::prefix('exportar')
                ->name('export.')
                ->group(function () {

                    Route::get('candidatos', [ExportController::class, 'exporToExcel'])->name('excel');
                });

            /*
    |--------------------------------------------------------------------------
    | Importação
    |--------------------------------------------------------------------------
    */

            Route::prefix('importar')
                ->name('import.')
                ->group(function () {

                    Route::get('notas', [ImportController::class, 'home'])->name('home');
                    Route::post('notas', [ImportController::class, 'import'])->name('import');
                });

            /*
    |--------------------------------------------------------------------------
    | Alocações
    |--------------------------------------------------------------------------
    */

            Route::prefix('alocacoes')
                ->name('allocations.')
                ->group(function () {

                    Route::get('alocacao', [PdfController::class, 'allocationsToPdf'])->name('allocation');
                    Route::get('salas', [PdfController::class, 'roomsToPdf'])->name('rooms');
                    Route::get('assinaturas', [PdfController::class, 'signaturesToPdf'])->name('signs');
                });

            /*
    |--------------------------------------------------------------------------
    | PDFs
    |--------------------------------------------------------------------------
    */

            Route::prefix('pdf')
                ->name('pdf.')
                ->group(function () {

                    Route::get('inscricoes', [PdfController::class, 'allInscriptionsToPdf'])->name('inscriptions');
                });

            /*
    |--------------------------------------------------------------------------
    | Chamadas
    |--------------------------------------------------------------------------
    */

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

            /*
    |--------------------------------------------------------------------------
    | Local de Prova
    |--------------------------------------------------------------------------
    */

            Route::prefix('local')
                ->name('local.')
                ->group(function () {

                    Route::get('/', [LocalController::class, 'index'])->name('index');

                    Route::post('salvar', [LocalController::class, 'store'])->name('store');

                    Route::get('editar/{location}', [LocalController::class, 'edit'])->name('edit');
                    Route::put('editar/{location}', [LocalController::class, 'update'])->name('update');

                    Route::delete('excluir/{location}', [LocalController::class, 'destroy'])->name('destroy');
                });

            /*
    |--------------------------------------------------------------------------
    | Resultados
    |--------------------------------------------------------------------------
    */

            Route::prefix('resultados')
                ->name('results.')
                ->group(function () {

                    Route::get('notas-e-classificacao', [ResultController::class, 'index'])->name('index');
                });

            /*
    |--------------------------------------------------------------------------
    | Sistema
    |--------------------------------------------------------------------------
    */

            Route::prefix('sistema')
                ->name('system.')
                ->group(function () {

                    Route::get('redefinir-dados', [SettingController::class, 'index'])->name('index');

                    Route::post('resetar', [SettingController::class, 'reset'])->name('reset');

                    Route::post('liberar-acesso-calendario', [SettingController::class, 'calendar'])->name('publish.calendar');
                    Route::post('liberar-acesso-local', [SettingController::class, 'location'])->name('publish.location');
                    Route::post('liberar-acesso-resultados', [SettingController::class, 'result'])->name('publish.result');

                    Route::put('liberar-acesso-edital', [SettingController::class, 'notice'])->name('publish.notice');
                });

            /*
    |--------------------------------------------------------------------------
    | Deferimentos
    |--------------------------------------------------------------------------
    */

            Route::prefix('deferimentos')
                ->name('deferrals.')
                ->group(function () {

                    Route::patch('def/{user}/accept-authorization', [DeferralController::class, 'acceptAuthorization'])
                        ->name('accept.authorization');

                    Route::patch('def/{user}/reject-authorization', [DeferralController::class, 'rejectAuthorization'])
                        ->name('reject.authorization');

                    Route::patch('def/{user}/accept-report', [DeferralController::class, 'acceptReport'])
                        ->name('accept.report');

                    Route::patch('def/{user}/reject-report', [DeferralController::class, 'rejectReport'])
                        ->name('reject.report');
                });

            /*
    |--------------------------------------------------------------------------
    | Usuários
    |--------------------------------------------------------------------------
    */

            Route::prefix('usuarios')
                ->name('users.')
                ->group(function () {

                    Route::get('/', [UserController::class, 'index'])->name('index');
                });
        });
});
