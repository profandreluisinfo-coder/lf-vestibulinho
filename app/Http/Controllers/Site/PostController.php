<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index(?string $type = null)
    {
        $posts = Post::published()
            ->with('category')
            ->when($type, fn($query) => $query->type($type))
            ->latest()
            ->paginate(10);

        return view('site.posts.index', compact('posts', 'type'));
    }

    public function show(string $slug)
    {
        $post = Post::published()
            ->with('attachments')
            ->where('slug', $slug)
            ->firstOrFail();

        $previous = Post::published()
            ->where('published_at', '<', $post->published_at)
            ->orderByDesc('published_at')
            ->first();

        $next = Post::published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at')
            ->first();

        return view('site.posts.show', compact('post', 'previous', 'next'));
    }
}
