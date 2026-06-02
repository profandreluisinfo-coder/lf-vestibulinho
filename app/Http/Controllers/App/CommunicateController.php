<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Communicate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunicateController extends Controller
{
    /**
     * Lista todos os comunicados (rascunhos + publicados).
     * Rota: GET /admin/comunicados
     */
    public function index(): View
    {
        $comunicados = Communicate::with('user')
            ->latest()
            ->paginate(15);

        return view('app.communicates.index', compact('comunicados'));
    }

    /**
     * Exibe o formulário de criação.
     * Rota: GET /admin/comunicados/create
     */
    public function create(): View
    {
        return view('app.communicates.create');
    }

    /**
     * Salva o novo comunicado.
     * Rota: POST /admin/comunicados
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo'  => ['required', 'string', 'max:255'],
            'resumo'  => ['nullable', 'string'],
            'tipo'    => ['required', 'string', 'max:50'],
            'url'     => ['nullable', 'url', 'max:255'],
            'status'  => ['required', 'in:rascunho,publicado'],

            'attachments' => ['nullable', 'array'],
            'attachments.*' => [
                'file',
                'max:10240',
            ],
        ]);

        $validated['user_id'] = auth()->id();

        // Se já sai publicado, registra o horário
        if ($validated['status'] === Communicate::STATUS_PUBLICADO) {
            $validated['published_at'] = now();
        }

        $communicate = Communicate::create($validated);

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {

                $path = $file->store(
                    'communicates',
                    'public'
                );

                $communicate->attachments()->create([
                    'name'      => $file->getClientOriginalName(),
                    'path'      => $path,
                    'mime_type' => $file->getMimeType(),
                    'size'      => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('app.communicates.index')
            ->with('success', 'Comunicado criado com sucesso.');
    }

    /**
     * Exibe um comunicado específico (área admin).
     * Rota: GET /admin/comunicados/{communicate}
     */
    // public function show(Communicate $communicate): View
    // {
    //     $communicate->load('user');

    //     return view('app.communicates.show', compact('communicate'));
    // }

    /**
     * Exibe o formulário de edição.
     * Rota: GET /admin/comunicados/{communicate}/edit
     */
    public function edit(Communicate $communicate): View
    {
        $communicate->load('attachments');

        return view('app.communicates.edit', compact('communicate'));
    }

    /**
     * Atualiza o comunicado.
     * Rota: PUT /admin/comunicados/{communicate}
     */
    public function update(Request $request, Communicate $communicate): RedirectResponse
    {
        $validated = $request->validate([
            'titulo'  => ['required', 'string', 'max:255'],
            'resumo'  => ['nullable', 'string'],
            'tipo'    => ['required', 'string', 'max:50'],
            'url'     => ['nullable', 'url', 'max:255'],
            'status'  => ['required', 'in:rascunho,publicado'],
        ]);

        // Se estava como rascunho e agora está sendo publicado,
        // registra o horário de publicação
        if (
            $communicate->status === Communicate::STATUS_RASCUNHO &&
            $validated['status']  === Communicate::STATUS_PUBLICADO
        ) {
            $validated['published_at'] = now();
        }

        // Se voltou para rascunho, limpa a data de publicação
        if ($validated['status'] === Communicate::STATUS_RASCUNHO) {
            $validated['published_at'] = null;
        }

        $communicate->update($validated);

        return redirect()
            ->route('app.communicates.index')
            ->with('success', 'Comunicado atualizado com sucesso.');
    }

    /**
     * Remove o comunicado.
     * Rota: DELETE /admin/comunicados/{communicate}
     */
    public function destroy(Communicate $communicate): RedirectResponse
    {
        $communicate->delete();

        return redirect()
            ->route('app.communicates.index')
            ->with('success', 'Comunicado removido com sucesso.');
    }
}
