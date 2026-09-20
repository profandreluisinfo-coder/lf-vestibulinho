<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Models\Template;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $pathForArchive = $file->storeAs('templates', $fileNameArchive, 'public');

        // Salva no banco apenas o caminho relativo
        Template::create([
            'process_id' => $request->process_id,
            'file_path' => $pathForArchive, // ex: modelos/2025_modelo_1691778382.pdf
        ]);

        return redirect()->back()->with('success', 'Modelo importado com sucesso.');
    }

    // Route::get('editar/{template}', [TemplateController::class, 'edit'])->name('edit');
    public function edit(Template $template)
    {
        $vests = Process::all();

        return view('admin.templates.edit', [
            'template' => $template,
            'vests' => $vests
        ]);
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'process_id' => 'required|exists:processes,id',
            'file' => 'nullable|file|mimetypes:application/pdf',
        ], [
            'process_id.required' => 'O campo processo seletivo é obrigatório.',
            'process_id.exists' => 'O processo seletivo selecionado não existe.',
            'file.file' => 'O arquivo de modelo deve ser um arquivo.',
            'file.mimetypes' => 'O arquivo de modelo deve ser um PDF.',
        ]);

        // Atualiza o processo seletivo
        $template->process_id = $request->process_id;

        // Se houver um novo arquivo, substitui o antigo
        if ($request->hasFile('file')) {
            // Deleta o arquivo antigo
            Storage::disk('public')->delete($template->file_path);

            // Salva o novo arquivo
            $file = $request->file('file');
            $originalNameForArchive = str_replace(' ', '_', $file->getClientOriginalName());
            // Busca o processo selecionado para pegar o ano dele
            $vest = Process::find($request->process_id); // ajuste o nome do Model se for diferente

            $fileNameArchive = $vest->year . '_' . pathinfo($originalNameForArchive, PATHINFO_FILENAME)
                . '_' . time()
                . '.' . $file->getClientOriginalExtension();
            $pathForArchive = $file->storeAs('templates', $fileNameArchive, 'public');

            // Atualiza o caminho do arquivo no banco
            $template->file_path = $pathForArchive;
        }

        // Salva as alterações no banco
        $template->save();

        return redirect()->back()->with('success', 'Modelo atualizado com sucesso.');
    }

    // Route::delete('excluir/{template}', [TemplateController::class, 'destroy'])->name('destroy');
    public function destroy(Template $template): RedirectResponse
    {
        // Deleta o arquivo do disco
        Storage::disk('public')->delete($template->file_path);

        // Deleta o registro do banco
        $template->delete();

        return redirect()->back()->with('success', 'Modelo excluído com sucesso.');
    }

    public function publish(Template $template): RedirectResponse
    {
        DB::transaction(function () use ($template) {
            $template->status = $template->status === 'active' ? 'inactive' : 'active';
            $template->save();

            if ($template->status === 'active') {
                Template::where('id', '!=', $template->id)
                    ->where('status', 'active')
                    ->update(['status' => 'inactive']);
            }
        });

        return redirect()->back()->with('success', 'Alterado com sucesso!');
    }
}
