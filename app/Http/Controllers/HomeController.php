<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Call;
use App\Models\Course;
use App\Models\Archive;
use App\Models\Calendar;

class HomeController extends Controller
{
    public function index()
    {
        // Busca o registro (ou cria um objeto vazio caso não exista)
        $calendar = Calendar::first() ?? new Calendar();

        $deadline = \Carbon\Carbon::parse($calendar->inscription_end)
            ->endOfDay() // força para 23:59:59
            ->format('Y-m-d H:i:s');

        // Busca todos os arquivos de prova ativos e apenas retorna os 3 primeiros ordenados por ano decrescente
        $files = Archive::where('status', true)->limit(4)->orderBy('year', 'desc')->get();
        
        // Obter todos os cursos
        $courses = Course::all();

        // limite a 4 perguntas
        $faqs = Faq::where('status', true)->orderBy('order', 'asc')->limit(4)->get();

        // Verifica quantos registros de chamada existem
        $calls = Call::all()->count();

        view()->share(
            ['deadline' => $deadline, 
            'files'     => $files, 
            'courses'   => $courses, 
            'faqs'      => $faqs, 
            'calls'     => $calls
        ]);

        return view('index');
    }
}
