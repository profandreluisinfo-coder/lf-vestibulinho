<?php

namespace App\Http\Controllers\Vestibulinho;

use App\Models\Faq;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    /**
     * Mostra a lista de perguntas frequentes do sistema publicamente.
     * Caso não haja nenhuma pergunta, redireciona para a página inicial do sistema.
     * Compartilha a variável $faqs com a view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = Faq::with('category')
            ->where('status', true)
            ->orderBy('order')
            ->get()
            ->map(fn($faq) => [
                'id'  => $faq->id,
                'cat' => $faq->category?->normalized_name,
                'q'   => $faq->question,
                'a'   => $faq->answer,
            ]);

        // Converte para JSON mantendo HTML não escapado
        $faqsJson = json_encode($faqs->toArray(), 2048 | 64); // JSON_UNESCAPED_HTML | JSON_UNESCAPED_SLASHES

        return view('vestibulinho.guest.faqs.index', compact('faqsJson'));
    }
}
