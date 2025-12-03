<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use App\Models\ExamLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocalController extends Controller
{
    /**
     * Recupera todos os locais de exames e compartilha com a view.
     * 
     * Exibe o modal para cadastrar um novo local de exame.
     *
     * @return View A view que exibe a lista de locais de exames.
     */
    public function index()
    {
        // Obter todos as locais de prova
        $examLocations = ExamLocation::all();

        return view('local.private.index', compact('examLocations'));
    }

    /**
     * Armazene um novo local de exame.
     *
     * Essa ação valida os dados do formulário e armazena um novo local de exame.
     * A view compartilha os dados válidos com o formulário para que possam ser
     * reutilizados em caso de erro.
     *
     * @param Request $request A solicitação HTTP com os dados do formulário.
     *
     * @return RedirectResponse Uma resposta de redirecionamento para a página anterior.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'address' => 'required|max:200',
            'rooms_available' => 'required|numeric|min:1|max:40',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome deve ter no máximo :max caracteres.',
            'address.required' => 'O campo endereço é obrigatório.',
            'address.max' => 'O campo endereço deve ter no máximo :max caracteres.',
            'rooms_available.required' => 'O campo salas disponíveis é obrigatório.',
            'rooms_available.numeric' => 'O campo salas disponíveis deve ser numérico.',
            'rooms_available.min' => 'O campo salas disponíveis deve ser maior do que zero.',
            'rooms_available.max' => 'O campo salas disponíveis deve menor ou igual a :max.',
        ]);

        $examLocation = new ExamLocation();
        $examLocation->name = $this->stringToUpper($data['name']); // Converter para maiúsculas e UTF-8 para evitar problemas com acentuações e caracteres especiais.
        $examLocation->address = $this->stringToUpper($data['address']);
        $examLocation->rooms_available = $data['rooms_available'];
        $examLocation->save();

        // Verificar se a operação foi bem-sucedida
        if ($examLocation->wasRecentlyCreated) {
            return redirect()->back()->with(
                'success',
                'Local de exame cadastrado com sucesso!'
            );
        }

        return redirect()->back()->with(
            'danger',
            'Ocorreu um erro ao cadastrar o local de exame.'
        );
    }

    /**
     * Mostra a view para atualizar um local de prova.
     *
     * @param int $id ID do local de prova.
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $location = ExamLocation::find($id);

        view()->share([
            'location' => $location,
        ]);

        return view('local.private.edit');
    }

    /**
     * Atualiza um local de prova.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ID do local de prova.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $location = ExamLocation::find($id);
        $location->name = $this->stringToUpper($request->name);
        $location->address = $this->stringToUpper($request->address);
        $location->rooms_available = $request->rooms_available;
        $location->save();

        return redirect()->route('local.index')->with(
            'success',
            'Local atualizado com sucesso!'
        );
    }

    /**
     * Remove um local de prova.
     *
     * @param int $id ID do local de prova.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $examLocation = ExamLocation::findOrFail($id);

                ExamResult::where('exam_location_id', $examLocation->id)->delete();
                $examLocation->delete();
            });

            return redirect()->back()->with('success', 'Local excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Converte uma string para maiúsculas, considerando a codificação UTF-8.
     *
     * @param string $string String a ser convertida.
     *
     * @return string String convertida para maiúsculas.
     */
    private function stringToUpper($string)
    {
        return mb_strtoupper($string, 'UTF-8'); // Converter para maiúsculas e UTF-8 para evitar problemas com acentuações e caracteres especiais.
    }
}