<?php

use Illuminate\Support\Facades\Route;

require 'app.php';
require 'guest.php';
require 'auth.php';
require 'dash.php';
require 'inscription.php';

// ==============
// ROTA FALLBACK
// ==============
Route::fallback(fn() => response()->view('errors.404', [], 404));
