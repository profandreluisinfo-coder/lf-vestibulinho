<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Post;
use Mews\Purifier\Facades\Purifier;

// Site
class HomeController extends Controller
{
    public function index()
    {
        // Obter todos os cursos
        $courses = Course::all();

        // Apenas posts publicados
        $posts = Post::noticias()->published()
            ->latest()
            ->paginate(10);

        // Apenas FAQs publicados
        $faqs = Faq::where('status', true)
            ->orderBy('order', 'asc')
            ->limit(2)
            ->get();

        // Sanitizar o HTML das respostas dos FAQs
        $faqs->each(function ($faq) {
            $faq->answer = Purifier::clean($faq->answer ?? '');
        });

        return view(
            'site.home.index',
            compact('courses', 'posts', 'faqs')
        );
    }
}
