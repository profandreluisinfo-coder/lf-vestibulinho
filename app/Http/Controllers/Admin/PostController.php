<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attachment;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    // -------------------------------------------------------------------------
    // index — listagem paginada com filtros
    // -------------------------------------------------------------------------

    public function index(Request $request): View
    {
        $posts = Post::with(['category', 'author'])
            ->when($request->filled('type'),      fn ($q) => $q->type($request->type))
            ->when($request->filled('published'),  fn ($q) => $q->published())
            ->when($request->filled('search'),     fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('title',  'like', "%{$request->search}%")
                  ->orWhere('resume', 'like', "%{$request->search}%");
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    // -------------------------------------------------------------------------
    // create — formulário de criação
    // -------------------------------------------------------------------------

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $types      = [Post::TYPE_NOTICIA, Post::TYPE_INFO];

        return view('admin.posts.create', compact('categories', 'types'));
    }

    // -------------------------------------------------------------------------
    // store — persistência
    // -------------------------------------------------------------------------

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->postValidationRules());

        DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('posts/images', 'public');
            }

            // O slug é gerado automaticamente pelo boot() do model ao criar
            $data['user_id']   = Auth::id();
            $data['published'] = $request->boolean('published');

            $post = Post::create($data);

            $this->storeAttachments($request, $post);
        });

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post criado com sucesso.');
    }

    public function previewNew(Request $request): View
    {
        return $this->renderPreview($request);
    }

    public function previewExisting(Request $request, Post $post): View
    {
        return $this->renderPreview($request, $post);
    }

    // -------------------------------------------------------------------------
    // show — exibição
    // -------------------------------------------------------------------------

    public function show(Post $post): View
    {
        $post->load(['category', 'author', 'attachments']);

        return view('admin.posts.show', compact('post'));
    }

    // -------------------------------------------------------------------------
    // edit — formulário de edição
    // -------------------------------------------------------------------------

    public function edit(Post $post): View
    {
        $categories = Category::orderBy('name')->get();
        $types      = [Post::TYPE_NOTICIA, Post::TYPE_INFO];

        $post->load('attachments');

        return view('admin.posts.edit', compact('post', 'categories', 'types'));
    }

    // -------------------------------------------------------------------------
    // update — persistência da edição
    // -------------------------------------------------------------------------

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate($this->postValidationRules());

        DB::transaction(function () use ($data, $request, $post) {
            if ($request->hasFile('image')) {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                $data['image'] = $request->file('image')->store('posts/images', 'public');
            }

            // Reslugifica somente se o título mudou — o boot() só age no creating
            if ($data['title'] !== $post->title) {
                $data['slug'] = $this->uniqueSlug($data['title'], $post->id);
            }

            $data['published'] = $request->boolean('published');

            $post->update($data);

            $this->storeAttachments($request, $post);
        });

        return redirect()->route('admin.posts.edit', $post)
            ->with('success', 'Post atualizado com sucesso.');
    }

    // -------------------------------------------------------------------------
    // destroy — exclusão
    // -------------------------------------------------------------------------

    public function destroy(Post $post): RedirectResponse
    {
        DB::transaction(function () use ($post) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            foreach ($post->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
            }

            $post->delete();
        });

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post excluído com sucesso.');
    }

    // -------------------------------------------------------------------------
    // destroyAttachment — remoção avulsa de um anexo
    // -------------------------------------------------------------------------

    public function destroyAttachment(Post $post, Attachment $attachment): RedirectResponse
    {
        abort_if($attachment->post_id !== $post->id, 403);

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Anexo removido com sucesso.');
    }

    // -------------------------------------------------------------------------
    // togglePublished — publicar / despublicar via PATCH rápido
    // -------------------------------------------------------------------------

    public function togglePublished(Post $post): RedirectResponse
    {
        $publishing = ! $post->published;

        $post->update([
            'published'    => $publishing,
            // published_at é datetime no model — now() ou null
            'published_at' => $publishing ? now() : null,
        ]);

        $status = $publishing ? 'publicado' : 'despublicado';

        return back()->with('success', "Post {$status} com sucesso.");
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    private function postValidationRules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'resume'        => ['nullable', 'string', 'max:255'],
            'content'       => ['required', 'string'],
            'type'          => ['required', 'in:' . Post::TYPE_NOTICIA . ',' . Post::TYPE_INFO],
            'category_id'   => ['nullable', 'exists:categories,id'],
            'url'           => ['nullable', 'url', 'max:255'],
            'published'     => ['boolean'],
            'published_at'  => ['nullable', 'date'],
            'image'         => ['nullable', 'image', 'max:2048'],
            'attachments'   => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'],
        ];
    }

    private function renderPreview(Request $request, ?Post $existingPost = null): View
    {
        $data = $request->validate($this->postValidationRules());

        $preview = new Post([
            'title'       => $data['title'],
            'slug'        => Str::slug($data['title']),
            'resume'      => $data['resume'] ?? null,
            'content'     => $data['content'],
            'url'         => $data['url'] ?? null,
            'type'        => $data['type'],
            'category_id' => $data['category_id'] ?? null,
        ]);
        $preview->published_at = $data['published_at'] ?? now();
        $preview->image = $existingPost?->image;
        $preview->setRelation('author', $existingPost?->author ?? Auth::user());

        $previewImageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $previewImageUrl = 'data:' . $image->getMimeType() . ';base64,'
                . base64_encode(file_get_contents($image->getRealPath()));
            $preview->image = 'preview';
        } elseif ($preview->image) {
            $previewImageUrl = Storage::disk('public')->url($preview->image);
        }

        $attachments = $existingPost?->attachments()->get() ?? collect();
        foreach ($request->file('attachments', []) as $file) {
            $attachments->push(new Attachment([
                'name'      => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size'      => $file->getSize(),
            ]));
        }
        $preview->setRelation('attachments', $attachments);

        return view('site.posts.show', [
            'post'            => $preview,
            'previous'        => null,
            'next'            => null,
            'isPreview'       => true,
            'previewImageUrl' => $previewImageUrl,
        ]);
    }

    /**
     * Persiste os arquivos enviados como anexos de um post.
     */
    private function storeAttachments(Request $request, Post $post): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        foreach ($request->file('attachments') as $file) {
            $post->attachments()->create([
                'category'      => $file->getClientOriginalName(),
                'path'      => $file->store('posts/attachments', 'public'),
                'mime_type' => $file->getMimeType(),
                'size'      => $file->getSize(),
            ]);
        }
    }

    /**
     * Gera slug único para o update (o boot() não age no updating).
     */
    private function uniqueSlug(string $title, int $ignoreId): string
    {
        $base  = Str::slug($title);
        $slug  = $base;
        $count = 1;

        while (Post::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
