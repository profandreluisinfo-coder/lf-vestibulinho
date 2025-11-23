<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    FaqController, AuthController, HomeController, ArchiveController,
    ResultController, CallController, CalendarController
};

//
// 🏠 Rotas públicas
//
Route::middleware(['guest'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    // ====================
    // VERIFICAÇÃO DE EMAIL
    // ====================
    Route::get('/validate/{token}', [AuthController::class, 'verify'])->name('verify');

    // Recuperação de senha
    Route::get('/esqueci-minha-senha', [AuthController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/esqueci-minha-senha', [AuthController::class, 'forgotPasswordAction'])->middleware('throttle:3,1');

    Route::get('/redefinir-senha/{token}', [AuthController::class, 'resetPassword'])->name('reset.password');
    Route::post('/redefinir-senha', [AuthController::class, 'resetPasswordAction'])->name('reset.password.action')->middleware('throttle:3,1');

    // Reenvio de verificação de e-mail
    Route::get('/reenviar-email', [AuthController::class, 'resendEmail'])->name('resend.email');
    Route::post('/reenviar-email', [AuthController::class, 'resendEmailAction']);

    // FaQ
    Route::get('/perguntas-frequentes', [FaqController::class, 'list'])->name('questions');

    // Provas anteriores
    Route::get('/provas-anteriores', [ArchiveController::class, 'list'])->name('archives');

    // Resultados
    Route::get('/resultado-final', [ResultController::class, 'index'])->name('results');

    // Chamadas
    Route::get('/chamadas', [CallController::class, 'index'])->name('calls');

    // Calendário
    Route::get('/calendario-completo', [CalendarController::class, 'list'])->name('calendary');
});