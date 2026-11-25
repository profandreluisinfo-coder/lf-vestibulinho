<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;

class FaqController extends Controller
{
    /**
     * Mostra as perguntas frequentes do sistema para o administrador.
     *
     * Caso não haja nenhuma pergunta, redireciona para a página inicial do sistema
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $faqs = Faq::orderBy('order', 'asc')->get();  // Ordenar por 'order'

        return view('admin.faqs.index', [
            'categories' => $categories,
            'faqs' => $faqs
        ]);
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('admin.faqs.create', [
            'categories' => $categories
        ]);
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
            'category' => 'required|string|max:255',
            'question' => 'required',
            'answer' => [
                'required',
                function ($attribute, $value, $fail) {
                    // O editor pode enviar HTML sem conteúdo, como <p><br></p>.
                    if (!is_string($value) || trim($value) === '') {
                        return;
                    }

                    $text = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $text = preg_replace('/[\s\p{Z}\x{200B}\x{FEFF}]+/u', '', $text);
                    $hasMedia = preg_match('/<(img|iframe|video|audio|object|embed)\b/i', $value);

                    if ($text === '' && !$hasMedia) {
                        $fail('A resposta precisa ser preenchida.');
                    }
                },
            ],
        ], [
            'category.required' => 'A categoria precisa ser preenchida.',
            'question.required' => 'A pergunta precisa ser preenchida.',
            'answer.required' => 'A resposta precisa ser preenchida.',
        ]);

        // Buscar ou criar categoria
        $category = Category::firstOrCreate([
            'name' => $request->category
        ]);

        // Buscar maior ordem
        $maxOrder = Faq::max('order') ?? 0;

        // Criar FAQ
        Faq::create([
            'category_id' => $category->id,
            'question' => $request->question,
            'answer' => Purifier::clean($request->answer, 'faq'),
            'user_id' => Auth::id(),
            'order' => $maxOrder + 1,
        ]);

        return alertSuccess('FAQ criada com sucesso!', 'admin.faqs.index');
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
        $categories = Category::all();
        return view('admin.faqs.edit', compact('categories', 'faq'));
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
        // Validar dados
        $request->validate([
            'category' => 'required|string|max:255',
            'question' => 'required',
            'answer' => [
                'required',
                function ($attribute, $value, $fail) {
                    // O editor pode enviar HTML sem conteúdo, como <p><br></p>.
                    if (!is_string($value) || trim($value) === '') {
                        return;
                    }

                    $text = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $text = preg_replace('/[\s\p{Z}\x{200B}\x{FEFF}]+/u', '', $text);
                    $hasMedia = preg_match('/<(img|iframe|video|audio|object|embed)\b/i', $value);

                    if ($text === '' && !$hasMedia) {
                        $fail('A resposta precisa ser preenchida.');
                    }
                },
            ],
        ], [
            'category.required' => 'A categoria precisa ser preenchida.',
            'question.required' => 'A pergunta precisa ser preenchida.',
            'answer.required' => 'A resposta precisa ser preenchida.',
        ]);

        // Buscar ou criar categoria
        $category = Category::firstOrCreate([
            'name' => $request->category
        ]);

        // Gravar dados
        $faq->update([
            'category_id' => $category->id,
            'question' => $request->question,
            'answer' => Purifier::clean($request->answer, 'faq'),
            'user_id' => Auth::id()
        ]);

        return alertSuccess('FAQ atualizada com sucesso!', 'admin.faqs.index');
    }

    public function destroy(Faq $faq)
    {
        // $this->authorize('delete-faq', $faq);
        if (!Auth::user()->can('manage-faq', $faq)) {
            abort(403);
        }

        $faq->delete();
        // $faq->forceDelete();

        return alertSuccess('FAQ excluida com sucesso!');
    }

    public function publish(Faq $faq)
    {
        // $this->authorize('manage-faq', $faq);
        if (!Auth::user()->can('manage-faq', $faq)) {
            abort(403);
        }

        $faq->status = !$faq->status; // Alterna entre publicado (true) e não publicado (false)
        $faq->save();

        return alertSuccess('Status da FAQ atualizado com sucesso!');
    }
}
