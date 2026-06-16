<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        // Obter todos os cursos
        $courses = Course::all();
        
        // Apenas posts publicados
        $news = Post::type(Post::TYPE_NOTICIA)->published()->take(3)->get();

        // Apenas comunicados publicados
        $infos = Post::type(Post::TYPE_INFO)->published()->take(3)->get();

        return view('site.home.index', compact('courses', 'news', 'infos'));
    }
}
