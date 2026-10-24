<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Página principal do painel de administração contendo a lista de usuários sem inscrição.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $users = User::getWithoutInscription();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'user') {
            return alertError('Este usuário não pode ser excluído.');
        }

        // Verificar se o usuário possui inscrição associada
        if ($user?->inscription) {
            return alertError('Este usuário possui uma inscrição associada e não pode ser excluído.');
        }

        $user->delete();

        return alertSuccess(
            'Usuário excluído com sucesso!',
            'admin.users.index'
        );
    }
}
