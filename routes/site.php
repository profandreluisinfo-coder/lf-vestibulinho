<?php

use App\Http\Controllers\Site\{HomeController, PostController, ArchiveController, ResultController, CallController, FaqController, ProcessController};
use App\Http\Controllers\Site\PublicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::name('site.')
    ->group(function () {
        // Postagens públicas
        Route::prefix('posts')
            ->name('posts.')
            ->group(function () {
                Route::get('', [PostController::class, 'index'])
                    ->name('index');
                Route::get('{slug}', [PostController::class, 'show'])
                    ->name('show');
            });

        // Provas anteriores
        Route::get('provas-anteriores', [ArchiveController::class, 'index'])
            ->name('archives.index');

        // Classificação geral
        Route::get('classificacao-geral', [ResultController::class, 'index'])
            ->name('results.index');

        // Chamadas
        Route::get('chamadas', [CallController::class, 'index'])
            ->name('calls.index');

        // Perguntas frequentes
        Route::get('perguntas-frequentes', [FaqController::class, 'index'])
            ->name('faqs.index');

        // Calendário
        Route::get('calendario', [ProcessController::class, 'show'])
            ->name('process.show');

        // Publicações
        Route::prefix('publicacoes')
            ->name('publications.')
            ->controller(PublicationController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');// NÃO ESQUECER DE LIBERAR ESTA ROTA

                // Inscrições
                Route::get('inscricoes-deferidas', 'inscriptions')
                    ->defaults('status', 'approved')->name('inscriptions.approved');
                Route::get('inscricoes-indeferidas', 'inscriptions')
                    ->defaults('status', 'rejected')->name('inscriptions.rejected');

                // Nome social
                Route::get('nome-social-deferidos', 'socialNames')
                    ->defaults('status', 'approved')->name('social-names.approved');
                Route::get('nome-social-indeferidos', 'socialNames')
                    ->defaults('status', 'rejected')->name('social-names.rejected');

                // Laudos e relatórios médicos
                Route::get('laudos-deferidos', 'medicalReports')
                    ->defaults('status', 'approved')->name('medical-reports.approved');
                Route::get('laudos-indeferidos', 'medicalReports')
                    ->defaults('status', 'rejected')->name('medical-reports.rejected');
            });
    });
