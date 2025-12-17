<?php

namespace App\Http\Controllers\App;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $faqs = Faq::orderBy('order', 'asc')->get();  // Ordenar por 'order'

        return view('faqs.admin.index', compact('faqs'));
    }

    /**
     * Grava uma nova pergunta frequente no sistema.
     *
     * Valida os dados da requisição e grava uma nova FAQ com a ordem mais alta que a atual.
     *
     * Redireciona para a página de listagem de FAQs com uma mensagem de sucesso.
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

        // Buscar o maior número de ordem atual
        $maxOrder = Faq::max('order') ?? 0;

        // Gravar dados
        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'user_id' => auth()->user()->id,
            'order' => $maxOrder + 1,  // Nova FAQ vai para o final
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ criada com sucesso!');
    }

    /**
     * Atualiza a ordem das FAQs via drag and drop
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:faqs,id'
        ]);

        foreach ($request->order as $index => $faqId) {
            Faq::where('id', $faqId)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Ordem atualizada com sucesso!']);
    }

    /**
     * Mostra a view para editar uma FAQ
     *
     * @param Faq $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        return view('faqs.admin.edit', compact('faq'));
    }

    /**
     * Atualiza uma FAQ existente no sistema.
     *
     * Valida os dados da requisição e atualiza a FAQ com os novos dados.
     *
     * Redireciona para a página de listagem de FAQs com uma mensagem de sucesso.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Faq $faq
     * @return \Illuminate\Http\Response
     */
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
