<?php

namespace App\Http\Controllers\Guest;

use App\Models\Faq;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    /**
     * Mostra a lista de perguntas frequentes do sistema.
     *
     * Caso não haja nenhuma pergunta, redireciona para a página inicial do sistema.
     *
     * Compartilha a variável $faqs com a view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = Faq::where('status', true)->orderBy('order', 'asc')->get();

        if ($faqs->isEmpty()) {
            return redirect()->route('home');
        }

        return view('faqs.public.index', compact('faqs'));
    }
}