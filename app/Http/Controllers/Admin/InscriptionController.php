<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class InscriptionController extends Controller
{
    /**
     * Exibe a lista de candidatos com inscrição ativa.
     *
     * @return View
     */
    public function index(): View
    {
        // Agora apenas retorna a view, os dados virão via AJAX
        return view('admin.inscriptions.index');
    }

    /**
     * Retorna os dados paginados para o DataTables via AJAX
     *
     * Os dados serão exibidos na tabela de inscrições ativas no dashboard, na view "inscriptions.index".
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        // Captura os parâmetros do DataTables
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');

        // Mapeamento de colunas para ordenação
        $columns = ['inscriptions.id', 'users.name', 'users.cpf'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'inscriptions.id';

        // Query base
        $query = User::whereHas('inscription')
            ->with('inscription')
            ->join('inscriptions', 'users.id', '=', 'inscriptions.user_id')
            ->select('users.*', 'inscriptions.id as inscription_id');

        // Aplicar busca se houver
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'like', "%{$searchValue}%")
                    ->orWhere('users.cpf', 'like', "%{$searchValue}%")
                    ->orWhere('inscriptions.id', 'like', "%{$searchValue}%");
            });
        }

        // Total de registros (sem filtro)
        $totalRecords = User::whereHas('inscription')->count();

        // Total de registros filtrados
        $totalFiltered = $query->count();

        // Aplicar ordenação e paginação
        $users = $query->orderBy($orderColumn, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // Formatar dados para o DataTables
        $data = $users->map(function ($user) {
            $name = ($user->lgbt && $user->lgbt->status == 'accepted') ? $user->lgbt->name . ' (LGBTQIA+)' : $user->name;
            return [
                'inscription_id' => $user->inscription?->id ?? $user->inscription_id,
                'name' => $name,
                'cpf' => $user->cpf,
                'user_id' => $user->id,
                'actions' => view('admin.inscriptions.inscription-actions', compact('user'))->render()
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    /**
     * Exibe a ficha de inscrição de um candidato especificado.
     *
     * @param string $id O ID do candidato, criptografado.
     *
     * @return View A view com a ficha de inscri o do candidato.
     */
    public function show($id): View
    {
        $id = Crypt::decrypt($id);

        $user = User::find($id);

        return view('admin.inscriptions.show')->with('user', $user);
    }

    /**
     * Exibe a lista de candidatos com deficiência.
     *
     * @return View
     */
    public function pcd(): View
    {
        // Query base
        $users = User::whereHas('pne')
            ->whereHas('inscription')
            ->with(['inscription', 'pne'])
            ->get();
            
        // Não carrega mais os dados aqui, apenas retorna a view vazia
        return view('admin.inscriptions.pcd', [
            'users' => $users
        ]);
    }

    public function lgbts(): View
    {
        $users = User::whereHas('lgbt')
            ->whereHas('inscription')
            ->with(['inscription', 'lgbt'])
            ->get();
        
        return view('admin.inscriptions.lgbts',[
            'users' => $users
        ]);
    }

    
}
