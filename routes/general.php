<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController
};

//
// 👤 ⚙️
//

Route::middleware(['auth'])->group(function () {
    
    Route::post('/alterar-senha', [AuthController::class, 'updatePassword'])->name('alterar.senha'); // OK
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});