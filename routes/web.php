<?php

use Illuminate\Support\Facades\Route;

include __DIR__.'/site.php';
include __DIR__.'/admin.php';
include __DIR__.'/guest.php';
include __DIR__.'/auth.php';
include __DIR__.'/register.php';

Route::fallback(fn() => response()->view('errors.404', [], 404));