<?php

namespace App\Http\Controllers\Guest;

use App\Models\Faq;
use App\Models\Call;
use App\Models\Course;
use App\Models\Archive;
use App\Models\Calendar;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // $archives = Archive::getActiveArchives();

        $faqs = Faq::where('status', true)->orderBy('order', 'asc')->limit(5)->get();

        // Busca o registro (ou cria um objeto vazio caso não exista)
        // $calendar = Calendar::first() ?? new Calendar();

        // $deadline = \Carbon\Carbon::parse($calendar->inscription_end)
        //     ->endOfDay() // força para 23:59:59
        //     ->format('Y-m-d H:i:s');

        // Busca todos os arquivos de prova ativos e apenas retorna os 3 primeiros ordenados por ano decrescente
        // $files = Archive::where('status', true)->orderBy('year', 'desc')->get();

        // Obter todos os cursos
        $courses = Course::all();

        // limite a 4 perguntas
        // $faqs = Faq::where('status', true)->orderBy('order', 'asc')->get();

        // Verifica quantos registros de chamada existem
        // $calls = Call::all()->count();

        // return view('index', compact('deadline', 'files', 'courses', 'calls', 'faqs', 'archives'));
        return view('guest.home.index', compact('courses', 'faqs'));
    }

    public function faqs()
    {
        $faqs = Faq::where('status', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(fn($faq) => [
                'id'  => $faq->id,
                'cat' => $faq?->category,   // ajuste para o nome real da coluna
                'q'   => $faq->question,   // ajuste para o nome real da coluna
                'a'   => $faq->answer,     // ajuste para o nome real da coluna
            ]);

        return view('guest.home.faqs', compact('faqs'));
    }
}
