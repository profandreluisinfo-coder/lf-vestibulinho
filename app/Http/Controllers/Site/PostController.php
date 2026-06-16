<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        // Apenas posts publicados
        $posts = Post::type(Post::TYPE_NOTICIA)->published()->paginate(10);

        return view('site.posts.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::type(Post::TYPE_NOTICIA)
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $previous = Post::type(Post::TYPE_NOTICIA)
            ->published()
            ->where('published_at', '<', $post->published_at)
            ->orderByDesc('published_at')
            ->first();

        $next = Post::type(Post::TYPE_NOTICIA)
            ->published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at')
            ->first();

        return view('site.posts.show', compact(
            'post',
            'previous',
            'next'
        ));
    }
}
