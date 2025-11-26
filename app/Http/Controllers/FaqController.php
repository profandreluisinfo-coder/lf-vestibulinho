<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Mostra as perguntas frequentes do sistema.
     *
     * Caso não haja nenhuma pergunta, redireciona para a página inicial do sistema
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = Faq::all();

        view()->share('faqs', $faqs);

        return view('faqs.private.index');
    }

    /**
     * Grava uma nova pergunta frequente no sistema.
     *
     * Valida os dados da requisição e grava uma nova pergunta frequente com os dados fornecidos.
     *
     * Redireciona para a página de listagem de perguntas frequentes com uma mensagem de sucesso.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar dados
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ], [
            'question.required' => 'A pergunta precisa ser preenchida.',
            'answer.required' => 'A resposta precisa ser preenchida.',
        ]);

        // Gravar dados
        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ criada com sucesso!');
    }

    public function edit(Faq $faq)
    {
        return view('faqs.private.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        // $this->authorize('update-faq', $faq);
        if (!auth()->user()->can('manage-faq', $faq)) {
            // abort(403);
            return redirect()->back()->with('error', 'Acesso negado! Voce não tem permissão para editar essa FAQ porque ela pertence a outro usuário.');
        }
        // Validar dados
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ], [
            'question.required' => 'A pergunta precisa ser preenchida.',
            'answer.required' => 'A resposta precisa ser preenchida.',
        ]);

        // Gravar dados
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ atualizada com sucesso!');
    }

    public function destroy(Faq $faq)
    {
        // $this->authorize('delete-faq', $faq);
        if (!auth()->user()->can('manage-faq', $faq)) {
            abort(403);
        }

        $faq->delete();
        // $faq->forceDelete();

        return redirect()->route('faq.index')->with('success', 'FAQ excluida com sucesso!');
    }

    public function list()
    {
        $faqs = Faq::where('status', true)->get();

        if ($faqs->isEmpty()) {
            return redirect()->route('home');
        }

        view()->share('faqs', $faqs);

        return view('faqs.public.list');
    }

    public function publish(Faq $faq)
    {
        // $this->authorize('manage-faq', $faq);
        if (!auth()->user()->can('manage-faq', $faq)) {
            abort(403);
        }

        $faq->status = !$faq->status; // Alterna entre publicado (true) e não publicado (false)
        $faq->save();

        return redirect()->route('faq.index')->with('success', 'Status da FAQ atualizado com sucesso!');
    }
}
