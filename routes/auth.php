<?php

use App\Http\Controllers\{UserController, EmailController};
use App\Http\Controllers\Auth\{LoginController, LogoutController, PasswordController};
use App\Http\Controllers\Site\{HomeController};
use Illuminate\Support\Facades\Route;

// Autenticados
Route::middleware(['auth'])
    ->group(function () {
        Route::post('/alterar-senha', [PasswordController::class, 'updatePassword'])
            ->name('update.password');
        Route::post('/logout', LogoutController::class)
            ->name('logout');
    });

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// VERIFICAÇÃO DE EMAIL
Route::get('validate/{token}', [EmailController::class, 'verify'])
    ->name('verify');

// Recuperação de senha
Route::get('/esqueci-minha-senha', [PasswordController::class, 'forgotPassword'])
    ->name('forgot.password');
Route::post('/esqueci-minha-senha', [PasswordController::class, 'forgotPasswordAction'])
    ->middleware('throttle:3,1');

Route::get('/redefinir-senha/{token}', [PasswordController::class, 'resetPassword'])
    ->name('reset.password');
Route::post('/redefinir-senha', [PasswordController::class, 'resetPasswordAction'])
    ->name('reset.password.action')
    ->middleware('throttle:3,1');

// Não autenticados
Route::middleware(['guest'])
    ->group(function () {

        // 🔒 Autenticação
        Route::get('login', [LoginController::class, 'login'])
            ->name('login');
        Route::post('login', [LoginController::class, 'authenticate'])
            ->middleware('throttle:3,1');

        // Registro
        Route::get('registrar-se', [UserController::class, 'create'])
            ->name('register');
        Route::post('registrar-se', [UserController::class, 'store'])
            ->middleware('throttle:3,1');

        // Reenvio de verificação de e-mail
        Route::get('reenviar-email', [EmailController::class, 'resendEmail'])
            ->name('resend.email');
        Route::post('reenviar-email', [EmailController::class, 'resendEmailAction'])
            ->middleware('throttle:3,1');
    });
