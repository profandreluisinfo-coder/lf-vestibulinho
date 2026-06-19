<?php

namespace App\Http\Controllers\Vestibulinho;

use App\Http\Controllers\Controller;
use App\Models\Communicate;
use App\Models\Course;
use App\Models\Faq;

class HomeController extends Controller
{
    public function index()
    {
        // $archives = Archive::getActiveArchives();
        // $comunicados = Communicate::publicado()->latest()->take(10)->get();

        $faqs = Faq::where('status', true)->orderBy('order', 'asc')->limit(5)->get();

        // Obter todos os cursos
        $courses = Course::all();
        
        return view('vestibulinho.guest.home.index', compact('courses', 'faqs'));
    }
}
