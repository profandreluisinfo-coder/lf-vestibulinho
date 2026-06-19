<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $category = Category::first();

        $posts = [
            [
                'title' => 'Primeira Notícia do Portal',
                'resume' => 'Resumo da primeira notícia cadastrada no sistema.',
                'content' => '<p>Conteúdo completo da primeira notícia.</p>',
                'image' => 'posts/noticia-01.jpg',
                'url' => null,
                'type' => 'noticia',
                'category_id' => $category?->id,
                'published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Novo Comunicado para os Usuários',
                'resume' => 'Resumo do comunicado importante.',
                'content' => '<p>Conteúdo completo do comunicado.</p>',
                'image' => 'posts/comunicado-01.jpg',
                'url' => 'https://www.exemplo.com.br',
                'type' => 'comunicado',
                'category_id' => $category?->id,
                'published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Atualização do Sistema',
                'resume' => 'Resumo da atualização realizada.',
                'content' => '<p>Detalhes da atualização do sistema.</p>',
                'image' => null,
                'url' => null,
                'type' => 'noticia',
                'category_id' => $category?->id,
                'published' => false,
                'published_at' => now()->subDays(1),
            ],
        ];

        foreach ($posts as $post) {
            Post::create([
                'title' => $post['title'],
                'slug' => Str::slug($post['title']),
                'resume' => $post['resume'],
                'content' => $post['content'],
                'image' => $post['image'],
                'url' => $post['url'],
                'category_id' => $category?->id,
                'user_id' => $user->id,
                'published' => $post['published'],
                'published_at' => $post['published_at'],
            ]);
        }
    }
}