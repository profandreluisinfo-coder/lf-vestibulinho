<?php

namespace App\Http\Controllers\App;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class NoticeController extends Controller
{
    /**
     * Obter todos os arquivos de edital
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obter todos os arquivos de edital
        $notices = Notice::all();

        // Renderizar a view com a lista de arquivos
        return view('app.notices.index', compact('notices'));
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
    public function store(Request $request)
    {
        $request->validate([
            'path' => 'required|file|mimes:pdf'
        ], [
            'path.required' => 'Carregue um arquivo.',
            'path.file' => 'O arquivo de está corrompido.',
            'path.mimes' => 'O arquivo deve ser um PDF.',
        ]);

        $file = $request->file('path');
        // dd($request->all(), $request->file('path'));

        if (!$file) {
            return alertError('Envie um arquivo válido.');
        }

        $originalName = str_replace(' ', '_', $file->getClientOriginalName());

        $fileName = pathinfo($originalName, PATHINFO_FILENAME)
            . '_' . time()
            . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('notices', $fileName, 'public');

        Notice::create([
            'file' => $path
        ]);

        return alertSuccess('Edital cadastrado com sucesso!', 'app.notices.index');
    }

    /**
     * Atualiza um arquivo de edital.
     *
     * Valida as informações enviadas pelo formulário e salva o arquivo no
     * disco 'public' na pasta 'notices'. Além disso, salva as informações do
     * arquivo no banco de dados.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Notice $notice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'file' => 'nullable|file|mimetypes:application/pdf'
        ], [
            'file.file' => 'O arquivo de prova deve ser um arquivo.',
            'file.mimetypes' => 'O arquivo de prova deve ser um PDF.',
        ]);

        $file = $request->file('file');

        // Pega o nome original do arquivo (sem espaços)
        $originalName = str_replace(' ', '_', $file->getClientOriginalName());

        // Gera o nome final: ano_nomeoriginal_timestamp.pdf
        $fileName = $request->year . '_' . pathinfo($originalName, PATHINFO_FILENAME)
            . '_' . time()
            . '.' . $file->getClientOriginalExtension();

        // Salva no disco 'public' na pasta archives
        $path = $file->storeAs('notices', $fileName, 'public');

        // Salva no banco apenas o caminho relativo
        Notice::where('id', $notice->id)->update([
            'file' => $path, // ex: notices/prova_1691778382.pdf
        ]);

        return alertSuccess('Edital atualizado com sucesso!', 'app.notices.index');
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
        Storage::disk('public')->delete($notice->file);

        $notice->delete();

        Setting::where('notice', true)->update(['notice' => false]);

        return alertSuccess('Edital excluido com sucesso!', 'app.notices.index');
    }
}
