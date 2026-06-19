<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\{
    LogoutController,
    PasswordController
};

Route::middleware(['guest'])->group(function () {
    // Recuperação de senha
    Route::get('/esqueci-minha-senha', [PasswordController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/esqueci-minha-senha', [PasswordController::class, 'forgotPasswordAction'])->middleware('throttle:3,1');

    Route::get('/redefinir-senha/{token}', [PasswordController::class, 'resetPassword'])->name('reset.password');
    Route::post('/redefinir-senha', [PasswordController::class, 'resetPasswordAction'])->name('reset.password.action')->middleware('throttle:3,1');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/alterar-senha', [PasswordController::class, 'updatePassword'])->name('update.password');
    Route::post('/logout', LogoutController::class)->name('logout');
});