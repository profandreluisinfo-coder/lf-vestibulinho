<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
    public function index()
    {
        // Obter todos os arquivos de edital
        $notices = Notice::all();

        // Passar para a view
        view()->share('notices', $notices);

        // Renderizar a view com a lista de arquivos
        return view('notice.private.index');
    }

    public function create()
    {
        return view('notice.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimetypes:application/pdf'
        ], [
            'file.required' => 'Carregue um arquivo.',
            'file.file' => 'O arquivo de está corrompido.',
            'file.mimetypes' => 'O arquivo deve ser um PDF.',
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
        Notice::create([
            'file' => $path, // ex: archives/2025_prova_1691778382.pdf
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Edital cadastrado com sucesso!');
    }

    public function edit(Notice $notice)
    {
        return view('notice.edit', compact('notice'));
    }

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
            'file' => $path, // ex: archives/2025_prova_1691778382.pdf
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Edital atualizado com sucesso!');
    }

    public function destroy(Notice $notice)
    {
        Storage::disk('public')->delete($notice->file);
        $notice->delete();

        return redirect()->back()->with('success', 'Edital excluido com sucesso!');
    }

    public function publish(Notice $notice)
    {
        $notice->status = !$notice->status;
        $notice->save();

        return redirect()->back()->with('success', 'Status alterado com sucesso!');
    }
}