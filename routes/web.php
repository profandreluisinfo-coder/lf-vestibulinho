<?php

use Illuminate\Support\Facades\Route;

require 'public.php';
require 'general.php';
require 'user.php';
require 'inscription.php';
require 'admin.php';

// ==============
// ROTA FALLBACK
// ==============
Route::fallback(fn() => response()->view('errors.404', [], 404));
