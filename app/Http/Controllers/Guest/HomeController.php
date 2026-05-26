<?php

namespace App\Http\Controllers\Guest;

use App\Models\Faq;
use App\Models\Course;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // $archives = Archive::getActiveArchives();

        $faqs = Faq::where('status', true)->orderBy('order', 'asc')->limit(5)->get();

        // Obter todos os cursos
        $courses = Course::all();
        
        return view('guest.home.index', compact('courses', 'faqs'));
    }
}
