<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Post;

// Site
class HomeController extends Controller
{
    public function index()
    {
        // Obter todos os cursos
        $courses = Course::all();
        
        // Apenas posts publicados
        $posts = Post::type(Post::TYPE_NOTICIA)->published()->take(4)->get();

        // Apenas faqs publicados
        $faqs = Faq::where('status', true)->orderBy('order', 'asc')->limit(5)->get();

        return view('site.home.index', compact('courses', 'posts', 'faqs'));
    }
}
