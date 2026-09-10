<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        $vests = Process::all();
        $files = Template::with('process')->get();

        return view('admin.templates.index', [
            'vests' => $vests,
            'files' => $files
        ]);
    }

    public function preview(Template $template)
    {
        $disk = Storage::disk('public');

        abort_unless($disk->exists($template->file_path), 404);

        return $disk->response($template->file_path);
    }

    public function store(Request $request)
    {
        $request->validate([
            'process_id' => 'required|exists:processes,id',
            'file' => 'required|file|mimetypes:application/pdf',
        ], [
            'process_id.required' => 'O campo processo seletivo é obrigatório.',
            'process_id.exists' => 'O processo seletivo selecionado não existe.',
            'file.required' => 'O arquivo de modelo é obrigatório.',
            'file.file' => 'O arquivo de modelo deve ser um arquivo.',
            'file.mimetypes' => 'O arquivo de modelo deve ser um PDF.',
        ]);

        // Arquivo de modelo
        $file = $request->file('file');

        // Pega o nome original do arquivo (sem espaços)
        $originalNameForArchive = str_replace(' ', '_', $file->getClientOriginalName());

        // Gera o nome final: ano_nomeoriginal_timestamp.pdf
        $fileNameArchive = $request->year . '_' . pathinfo($originalNameForArchive, PATHINFO_FILENAME)
            . '_' . time()
            . '.' . $file->getClientOriginalExtension();
        // Salva no disco 'public' na pasta modelos
        $pathForArchive = $file->storeAs('modelos', $fileNameArchive, 'public');

        // Salva no banco apenas o caminho relativo
        Template::create([
            'process_id' => $request->process_id,
            'file_path' => $pathForArchive, // ex: modelos/2025_modelo_1691778382.pdf
        ]);

        return redirect()->back()->with('success', 'Modelo importado com sucesso.');
    }
}
