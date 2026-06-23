<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NoticeController extends Controller
{
    /**
     * Obter todos os arquivos de edital
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Obter todos os arquivos de edital
        $notices = Notice::all();

        // Renderizar a view com a lista de arquivos
        return view('admin.notices.index', compact('notices'));
    }

    /**
     * Salva um novo arquivo de edital.
     *
     * Valida as informações enviadas pelo formulário e salva o arquivo no
     * disco 'public' na pasta 'notices'. Além disso, salva as informações do
     * arquivo no banco de dados.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'year' => 'required|numeric|digits:4',
            'path' => 'required|file|mimes:pdf'
        ], [
            'year.required' => 'O campo ano é obrigatório.',
            'year.numeric' => 'O campo ano deve ser numérico.',
            'year.digits' => 'O campo ano deve ter 4 dígitos.',
            'path.required' => 'Carregue um arquivo.',
            'path.file' => 'O arquivo de está corrompido.',
            'path.mimes' => 'O arquivo deve ser um PDF.',
        ]);

        $file = $request->file('path');

        if (!$file) {
            return alertError('Envie um arquivo válido.');
        }

        $originalName = str_replace(' ', '_', $file->getClientOriginalName());

        $fileName = pathinfo($originalName, PATHINFO_FILENAME)
            . '_' . time()
            . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('notices', $fileName, 'public');

        Notice::create([
            'year' => $request->year,
            'file' => $path
        ]);

        return alertSuccess('Edital cadastrado com sucesso!', 'admin.notices.index');
    }


    public function update(Request $request): RedirectResponse
    {
        $notice = Notice::findOrFail($request->notice_id);

        $request->validate([
            'year' => 'nullable|numeric|digits:4',
            'path' => 'nullable|file|mimetypes:application/pdf',
        ], [
            'year.numeric' => 'O campo ano deve ser numérico.',
            'year.required' => 'O campo ano é obrigatório.',
            'year.digits' => 'O campo ano deve ter 4 dígitos.',
            'path.file' => 'O arquivo de edital deve ser um arquivo.',
            'path.mimetypes' => 'O arquivo de edital deve ser um PDF.',
        ]);

        // Se nenhum arquivo foi enviado
        if (!$request->hasFile('path')) {
            return alertWarning('Nenhum arquivo foi enviado.', 'admin.notices.index');
        }

        $file = $request->file('path');

        // Remove o arquivo antigo, se existir
        if ($selection_process?->edital && Storage::disk('public')->exists($selection_process?->edital)) {
            Storage::disk('public')->delete($selection_process?->edital);
        }

        // Nome original sem espaços
        $originalName = str_replace(
            ' ',
            '_',
            $file->getClientOriginalName()
        );

        // Nome final
        $fileName = pathinfo($originalName, PATHINFO_FILENAME)
            . '_' . time()
            . '.' . $file->getClientOriginalExtension();

        // Salva o novo arquivo
        $filePath = $file->storeAs('notices', $fileName, 'public');

        // Atualiza o registro
        $notice->update([
            'year' => $request->year,
            'file' => $filePath,
        ]);

        return alertSuccess('Edital atualizado com sucesso!', 'admin.notices.index');
    }

    /**
     * Exclui um arquivo de edital.
     *
     * Este método exclui um arquivo de edital da pasta 'notices' no disco
     * 'public' e, em seguida, remove as informações do banco de dados.
     *
     * @param \App\Models\Notice $notice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Notice $notice)
    {
        Storage::disk('public')->delete($selection_process?->edital);

        $notice->delete();

        Setting::where('notice', true)->update(['notice' => false]);

        return alertSuccess('Edital excluido com sucesso!', 'admin.notices.index');
    }
}
