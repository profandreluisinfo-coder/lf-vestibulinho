<?php

use Illuminate\Support\Facades\Route;

require 'app.php';
require 'guest.php';
require 'auth.php';
require 'dash.php';
require 'user.php';
require 'inscription.php';
require 'system.php';
require 'admin.php';

// ==============
// ROTA FALLBACK
// ==============
Route::fallback(fn() => response()->view('errors.404', [], 404));
