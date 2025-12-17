<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
/**
 * Exibe a lista de candidatos inscritos (inclusive candidatos com deficiência).
 *
 * @return View
 */
    public function index(): View
    {
        $users = User::getWithoutInscription();

        return view('user.admin.index', compact('users'));
    }
}