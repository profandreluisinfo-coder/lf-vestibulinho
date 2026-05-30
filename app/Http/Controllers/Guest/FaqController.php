<?php

namespace App\Http\Controllers\Guest;

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
        $faqs = Faq::where('status', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(fn($faq) => [
                'id'  => $faq->id,
                'cat' => $faq->category?->normalized_category,   // ajuste para o nome real da coluna
                'q'   => $faq->question,   // ajuste para o nome real da coluna
                'a'   => $faq->answer,     // ajuste para o nome real da coluna
            ]);

        return view('guest.faqs.index', compact('faqs'));
    }
}