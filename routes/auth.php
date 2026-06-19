<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\{
    LoginController,
    LogoutController,
    PasswordController
};

use App\Http\Controllers\Site\{
    HomeController, 
    RegisterController, 
    EmailController
};

Route::middleware(['auth'])->group(function () {
    Route::post('/alterar-senha', [PasswordController::class, 'updatePassword'])->name('update.password');
    Route::post('/logout', LogoutController::class)->name('logout');
});

Route::middleware(['guest'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    // 🔒 Autenticação
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate'])->middleware('throttle:3,1');

    // Registro
    Route::get('registrar', [RegisterController::class, 'register'])->name('register');
    Route::post('registrar', [RegisterController::class, 'store'])->name('register.store');

    // VERIFICAÇÃO DE EMAIL
    Route::get('validate/{token}', [EmailController::class, 'verify'])->name('verify');

    // Reenvio de verificação de e-mail
    Route::get('reenviar-email', [EmailController::class, 'resendEmail'])->name('resend.email');
    Route::post('reenviar-email', [EmailController::class, 'resendEmailAction']);

    // Recuperação de senha
    Route::get('/esqueci-minha-senha', [PasswordController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/esqueci-minha-senha', [PasswordController::class, 'forgotPasswordAction'])->middleware('throttle:3,1');

    Route::get('/redefinir-senha/{token}', [PasswordController::class, 'resetPassword'])->name('reset.password');
    Route::post('/redefinir-senha', [PasswordController::class, 'resetPasswordAction'])->name('reset.password.action')->middleware('throttle:3,1');
});