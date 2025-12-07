<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ArchiveFileService;

class ArchiveController extends Controller
{
    /**
     * Exibir uma lista de todos os arquivos compactados.
     *
     * Recupera todos os arquivos do modelo de arquivo e os compartilha com a visualização
     * para renderização na página de índice do arquivo de administração.
     * 
     * Última atualização: 09/11/2025 às 13:00
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        // Obter todos os arquivos de prova
        $files = Archive::orderBy('year', 'desc')->get();

        // Passar para a view
        view()->share('files', $files);

        return view('archives.private.index');
    }

    /**
     * Armazena um novo arquivo de prova.
     *
     * Valida as informações enviadas pelo formulário e salva o arquivo no
     * disco 'public' na pasta 'archives'. Além disso, salva as informações do
     * arquivo no banco de dados.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação
        $request->validate([
            'year' => 'required|numeric|digits:4',
            'file' => 'required|file|mimetypes:application/pdf',
            'answer' => 'nullable|file|mimetypes:application/pdf',
        ], [
            'year.required' => 'O campo ano é obrigatório.',
            'year.numeric' => 'O campo ano deve ser numérico.',
            'year.digits' => 'O campo ano deve ter 4 dígitos.',
            'file.required' => 'O arquivo de prova é obrigatório.',
            'file.file' => 'O arquivo de prova deve ser um arquivo.',
            'file.mimetypes' => 'O arquivo de prova deve ser um PDF.',
            'answer.file' => 'O gabarito deve ser um arquivo.',
            'answer.mimetypes' => 'O gabarito deve ser um PDF.',
        ]);

        // Arquivo de prova
        $file = $request->file('file');

        // Pega o nome original do arquivo (sem espaços)
        $originalNameForArchive = str_replace(' ', '_', $file->getClientOriginalName());

        // Gera o nome final: ano_nomeoriginal_timestamp.pdf
        $fileNameArchive = $request->year . '_' . pathinfo($originalNameForArchive, PATHINFO_FILENAME)
            . '_' . time()
            . '.' . $file->getClientOriginalExtension();
        // Salva no disco 'public' na pasta archives
        $pathForArchive = $file->storeAs('archives', $fileNameArchive, 'public');

        // Salva no banco apenas o caminho relativo
        Archive::create([
            'file' => $pathForArchive, // ex: archives/2025_prova_1691778382.pdf
            'year' => $request->year,
            'status' => false,
            'user_id' => auth()->id(),
        ]);

        // Verificar se o gabarito foi enviado
        if ($request->hasFile('answer')) {
            $answer = $request->file('answer'); // Gabarito
            // Pega o nome original do arquivo (sem espaços)
            $originalNameForAnswer = str_replace(' ', '_', $answer->getClientOriginalName());
            // Gera o nome final: ano_nomeoriginal_timestamp.pdf
            $fileNameAnswer = $request->year . '_' . pathinfo($originalNameForAnswer, PATHINFO_FILENAME)
                . '_' . time()
                . '.' . $answer->getClientOriginalExtension();
            // Salva no disco 'public' na pasta archives
            $pathForAnswer = $answer->storeAs('archives', $fileNameAnswer, 'public');
            // Salva no banco apenas o caminho relativo
            Answer::create([
                'file' => $pathForAnswer, // ex: archives/2025_prova_1691778382.pdf
                'archive_id' => Archive::latest()->first()->id,
            ]);
        }

        return redirect()->back()->with('success', 'Arquivo cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário de edição de um arquivo de prova.
     *
     * Envia o objeto do arquivo de prova para a visualização de edição,
     * para que possa ser renderizado na página de edição do arquivo de
     * administração.
     * 
     * Última atualização: 09/11/2025 às 13:00
     *
     * @param \App\Models\Archive $archive
     * @return \Illuminate\View\View
     */
    public function edit(Archive $archive)
    {
        return view('archives.private.edit', compact('archive'));
    }

    /**
     * Atualiza as informações de um arquivo de prova existente.
     *
     * Este método valida os dados recebidos da requisição, atualiza o arquivo
     * de prova no disco e as informações no banco de dados.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Archive $archive
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request, Archive $archive, ArchiveFileService $fileService)
    {
        $request->validate([
            'year' => 'required|numeric|digits:4',
            'file' => 'nullable|file|mimetypes:application/pdf',
            'answer' => 'nullable|file|mimetypes:application/pdf',
        ]);

        // Atualiza arquivo da prova
        if ($request->hasFile('file')) {
            $archive->file = $fileService->replaceFile(
                $request->file('file'),
                $request->year,
                $archive->file // antigo
            );

            $archive->status = false;
        }

        // Atualiza dados gerais
        $archive->year = $request->year;
        $archive->user_id = auth()->id();
        $archive->save();


        // Atualiza ou cria o gabarito
        if ($request->hasFile('answer')) {

            $answerModel = $archive->answer()->firstOrCreate([
                'archive_id' => $archive->id,
            ]);

            $answerModel->file = $fileService->replaceFile(
                $request->file('answer'),
                $request->year,
                $answerModel->file // antigo
            );

            $answerModel->save();
        }

        return redirect()->route('archive.index')
            ->with('success', 'Arquivo atualizado com sucesso!');
    }

    /**
     * Exclui um arquivo de prova.
     *
     * Este método exclui um arquivo de prova da pasta 'archives' no disco
     * 'public' e, se houver um gabarito, também. Além disso, remove as informações do banco de dados.
     *
     * @param int $id ID do arquivo de prova a ser excluído.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $archive = Archive::findOrFail($id);

        Storage::disk('public')->delete($archive->file);
        Storage::disk('public')->delete($archive->answer->file ?? null);

        $archive->delete();

        return redirect()->back()->with('success', 'Arquivo excluido com sucesso!');
    }

    /**
     * Publica ou despublica um arquivo de prova.
     *
     * Este método alterna o status de um arquivo de prova da pasta 'archives' no banco de dados.
     * Se o arquivo estiver publicado, ele será despublicado e vice-versa.
     *
     * @param \App\Models\Archive $archive Arquivo de prova a ser publicado/despublicado.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publish(Archive $archive)
    {
        $archive->status = !$archive->status;
        $archive->save();

        if ($archive->answer) {
            $archive->answer->status = !$archive->answer->status;
            $archive->answer->save();
        }

        return redirect()->back()->with('success', 'Status alterado com sucesso!');
    }

    /**
     * Obter todos os arquivos de prova em ordem decrescente.
     *
     * Este método obtem todos os arquivos de prova da pasta 'archives' no banco de dados
     * que estejam publicados e os ordena em ordem decrescente de ano.
     * 
     * Os arquivos de prova são passados para a view 'archives.public.list', que exibirá os arquivos para 
     * os uários que acessarem o site do vestibulinho.
     * 
     * Última atualização: 09/11/2025 às 13:00
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        // Obter todos os arquivos de prova em ordem decrescente
        $files = Archive::where('status', true)->orderBy('year', 'desc')->get();

        // Passar para a view
        view()->share('files', $files);

        return view('archives.public.list');
    }
}
