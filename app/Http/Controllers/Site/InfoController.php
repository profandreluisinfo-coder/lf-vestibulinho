<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;

class InfoController extends Controller
{
    public function index()
    {
        // Apenas posts publicados
        $infos = Post::type(Post::TYPE_INFO)->published()->paginate(10);

        return view('site.info.index', compact('infos'));
    }

    public function show(string $slug)
    {
        $info = Post::type(Post::TYPE_INFO)
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $previous = Post::type(Post::TYPE_INFO)
            ->published()
            ->where('published_at', '<', $info->published_at)
            ->orderByDesc('published_at')
            ->first();

        $next = Post::type(Post::TYPE_INFO)
            ->published()
            ->where('published_at', '>', $info->published_at)
            ->orderBy('published_at')
            ->first();

        return view('site.info.show', compact(
            'info',
            'previous',
            'next'
        ));
    }
}
