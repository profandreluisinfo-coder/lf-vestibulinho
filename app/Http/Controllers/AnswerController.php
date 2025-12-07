<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Archive;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnswerController extends Controller
{
    public function index(): View
    {
        $answers = Answer::all();
        $archives = Archive::all();

        return view('answers.private.index', compact('answers', 'archives'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request): RedirectResponse
    {
        // Validação
        $request->validate([
            'file' => 'required|file|mimetypes:application/pdf',
            'archive_id' => 'required|exists:archives,id',
        ], [
            'file.required' => 'O arquivo de prova é obrigatório.',
            'file.file' => 'O arquivo de prova deve ser um arquivo.',
            'file.mimetypes' => 'O arquivo de prova deve ser um PDF.',
            'archive_id.required' => 'O ID do arquivo é obrigatório.',
            'archive_id.exists' => 'O ID do arquivo selecionado é inválido.',
        ]);

        $file = $request->file('file');
        $archive_yeaar = Archive::find($request->archive_id)->year;

        // Pega o nome original do arquivo (sem espaços)
        $originalName = str_replace(' ', '_', $file->getClientOriginalName());

        // Gera o nome final: ano_nomeoriginal_timestamp.pdf
        $fileName = $archive_yeaar . '_' . pathinfo($originalName, PATHINFO_FILENAME)
            . '_' . time()
            . '.' . $file->getClientOriginalExtension();

        // Salva no disco 'public' na pasta archives
        $path = $file->storeAs('archives', $fileName, 'public');

        // Salva no banco apenas o caminho relativo
        Answer::create([
            'file' => $path, // ex: archives/2025_prova_1691778382.pdf
            'archive_id' => $request->archive_id
        ]);

        return redirect()->back()->with('success', 'Gabarito cadastrado com sucesso!');
    }

    public function destroy(Answer $answer): RedirectResponse
    {
        Storage::disk('public')->delete($answer->file);
        $answer->delete();

        return redirect()->back()->with('success', 'Edital excluido com sucesso!');
    }
}
