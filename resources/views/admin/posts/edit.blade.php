@extends('layouts.admin')

@section('page-title', 'Editar Post | ' . config('app.name'))

{{-- =====================================================================
     ESTILOS  (reaproveita os mesmos tokens do create)
====================================================================== --}}
@push('styles')
<style>
    .form-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .form-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--color-light-mid);
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--color-light);
    }

    .form-card-header i { font-size: 18px; color: var(--color-teal); }

    .form-card-header h6 { margin: 0; font-size: 0.9rem; color: var(--color-navy); }

    .form-card-body { padding: 24px; }

    /* Preview de capa */
    #image-preview-wrap {
        position: relative;
        width: 100%;
        max-width: 320px;
    }

    #image-preview {
        width: 100%;
        border-radius: var(--radius-md);
        border: 2px solid var(--border-color);
        object-fit: cover;
        max-height: 180px;
    }

    #remove-image {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--color-danger);
        color: #fff;
        border: none;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Drop zone de anexos */
    .drop-zone {
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-md);
        padding: 28px;
        text-align: center;
        color: var(--text-muted);
        font-size: var(--font-size-sm);
        cursor: pointer;
        transition: border-color var(--transition-base), background var(--transition-base);
    }

    .drop-zone:hover,
    .drop-zone.dragover {
        border-color: var(--color-teal);
        background: var(--color-teal-light);
    }

    .drop-zone i { font-size: 28px; display: block; margin-bottom: 8px; }

    /* Lista de novos anexos */
    #attachment-list { margin-top: 12px; display: flex; flex-direction: column; gap: 6px; }

    .attachment-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: var(--color-light);
        border-radius: var(--radius-sm);
        font-size: var(--font-size-sm);
        border: 1px solid var(--color-light-mid);
    }

    .attachment-item i { color: var(--color-teal); font-size: 16px; }
    .attachment-item .att-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .attachment-item .att-size { color: var(--text-muted); font-size: var(--font-size-xs); white-space: nowrap; }
    .attachment-item .att-remove { background: none; border: none; color: var(--color-danger); cursor: pointer; font-size: 14px; padding: 0 2px; }

    /* Anexos existentes */
    .existing-attachment {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: var(--font-size-sm);
    }

    .existing-attachment i { color: var(--color-teal); font-size: 18px; flex-shrink: 0; }
    .existing-attachment .att-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--text-dark); }
    .existing-attachment .att-size { color: var(--text-muted); font-size: var(--font-size-xs); white-space: nowrap; }

    /* Status badge no cabeçalho */
    .post-status-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: var(--radius-pill);
    }

    /* Breadcrumb */
    .page-breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--font-size-sm);
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .page-breadcrumb a { color: var(--color-teal); }
    .page-breadcrumb i { font-size: 12px; }

    /* Slug preview */
    .slug-preview {
        font-size: var(--font-size-xs);
        color: var(--text-muted);
        word-break: break-all;
        margin-top: 4px;
    }

    .slug-preview strong { color: var(--color-teal-dark); }
</style>
@endpush

{{-- =====================================================================
     CONTEÚDO
====================================================================== --}}
@section('content')

<div class="container-fluid py-4 px-3 px-md-4">

    {{-- Breadcrumb --}}
    <nav class="page-breadcrumb">
        <a href="{{ route('admin.posts.index') }}">Notícias e Comunicados</a>
        <i class="bi bi-chevron-right"></i>
        <span>Editar: {{ Str::limit($post->title, 40) }}</span>
    </nav>

    <form action="{{ route('admin.posts.update', $post) }}"
          method="POST"
          enctype="multipart/form-data"
          id="post-form"
          novalidate>
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ============================================================
                 COLUNA PRINCIPAL
            ============================================================= --}}
            <div class="col-12 col-lg-8">

                {{-- Conteúdo editorial --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="bi bi-pencil-square"></i>
                        <h6>Conteúdo</h6>
                    </div>
                    <div class="form-card-body">

                        {{-- Título --}}
                        <div class="mb-3">
                            <label for="title" class="form-label required">Título</label>
                            <input type="text"
                                id="title"
                                name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $post->title) }}"
                                maxlength="255"
                                required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- Slug preview --}}
                            <p class="slug-preview">
                                <i class="bi bi-link-45deg"></i>
                                /posts/<strong id="slug-preview-text">{{ $post->slug }}</strong>
                            </p>
                        </div>

                        {{-- Resumo --}}
                        <div class="mb-3">
                            <label for="resume" class="form-label">Resumo</label>
                            <input type="text"
                                id="resume"
                                name="resume"
                                class="form-control @error('resume') is-invalid @enderror"
                                value="{{ old('resume', $post->resume) }}"
                                maxlength="255">
                            @error('resume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Conteúdo --}}
                        <div class="mb-0">
                            <label for="content" class="form-label required">Conteúdo</label>
                            <textarea
                                id="content"
                                name="content"
                                class="form-control @error('content') is-invalid @enderror"
                                rows="10"
                                required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Imagem de capa --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="bi bi-image"></i>
                        <h6>Imagem de Capa</h6>
                    </div>
                    <div class="form-card-body">

                        <div id="image-preview-wrap">
                            <img id="image-preview"
                                src="{{ $post->image ? Storage::url($post->image) : '' }}"
                                alt="Preview da capa"
                                style="{{ $post->image ? '' : 'display:none' }}">
                            @if($post->image)
                                <button type="button" id="remove-image" title="Remover imagem">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            @endif
                        </div>

                        <div class="{{ $post->image ? 'mt-3' : '' }}">
                            <input type="file"
                                id="image"
                                name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Selecione uma nova imagem para substituir a atual. Máximo 2 MB.</div>
                        </div>

                    </div>
                </div>

                {{-- Anexos existentes --}}
                @if($post->attachments->isNotEmpty())
                    <div class="form-card mb-4">
                        <div class="form-card-header">
                            <i class="bi bi-paperclip"></i>
                            <h6>Anexos existentes</h6>
                        </div>
                        <div class="form-card-body">
                            <div class="d-flex flex-column gap-2">
                                @foreach($post->attachments as $attachment)
                                    <div class="existing-attachment" id="att-{{ $attachment->id }}">
                                        <i class="bi bi-file-earmark-text"></i>
                                        <span class="att-name" title="{{ $attachment->name }}">
                                            {{ $attachment->name }}
                                        </span>
                                        <span class="att-size">
                                            {{ $attachment->mime_type }}
                                            @if($attachment->size)
                                                · {{ number_format($attachment->size / 1024, 1) }} KB
                                            @endif
                                        </span>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="removeAttachment({{ $attachment->id }}, '{{ route('admin.posts.attachments.destroy', [$post, $attachment]) }}')"
                                            title="Remover anexo">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Novos anexos --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-plus-circle"></i>
                        <h6>Adicionar Anexos</h6>
                    </div>
                    <div class="form-card-body">

                        <div class="drop-zone" id="drop-zone">
                            <i class="bi bi-cloud-arrow-up"></i>
                            Arraste arquivos aqui ou <strong>clique para selecionar</strong>
                        </div>

                        <input type="file"
                            id="attachments"
                            name="attachments[]"
                            class="d-none"
                            multiple>
                        <div class="form-text mb-2">Múltiplos arquivos. Máximo 10 MB por arquivo.</div>

                        <div id="attachment-list"></div>

                    </div>
                </div>

            </div>

            {{-- ============================================================
                 COLUNA LATERAL
            ============================================================= --}}
            <div class="col-12 col-lg-4">

                {{-- Publicação --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="bi bi-send"></i>
                        <h6>Publicação</h6>
                        <span class="ms-auto post-status-badge {{ $post->published ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $post->published ? 'Publicado' : 'Rascunho' }}
                        </span>
                    </div>
                    <div class="form-card-body">

                        <div class="mb-3">
                            <label class="form-label required">Tipo</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="type" id="type-noticia"
                                        value="noticia"
                                        {{ old('type', $post->type) === 'noticia' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type-noticia">
                                        <i class="bi bi-newspaper me-1 text-primary"></i> Notícia
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="type" id="type-comunicado"
                                        value="comunicado"
                                        {{ old('type', $post->type) === 'comunicado' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type-comunicado">
                                        <i class="bi bi-megaphone me-1 text-warning"></i> Comunicado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    role="switch"
                                    id="published"
                                    name="published"
                                    value="1"
                                    {{ old('published', $post->published) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="published">
                                    Publicado
                                </label>
                            </div>
                        </div>

                        <div class="mb-0" id="published-at-wrap">
                            <label for="published_at" class="form-label">Data de publicação</label>
                            <input type="date"
                                id="published_at"
                                name="published_at"
                                class="form-control @error('published_at') is-invalid @enderror"
                                value="{{ old('published_at', $post->published_at?->toDateString()) }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Classificação --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="bi bi-tag"></i>
                        <h6>Classificação</h6>
                    </div>
                    <div class="form-card-body">

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Categoria</label>
                            <select name="category_id"
                                id="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">— Sem categoria —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $post->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label for="url" class="form-label">URL externa</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input type="url"
                                    id="url"
                                    name="url"
                                    class="form-control @error('url') is-invalid @enderror"
                                    value="{{ old('url', $post->url) }}"
                                    placeholder="https://...">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Metadados --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="bi bi-info-circle"></i>
                        <h6>Informações</h6>
                    </div>
                    <div class="form-card-body">
                        <dl class="mb-0" style="font-size: var(--font-size-sm); display:flex; flex-direction:column; gap:8px;">
                            <div class="d-flex justify-content-between">
                                <dt class="fw-normal text-muted">Autor</dt>
                                <dd class="mb-0 fw-semibold">{{ $post->author->name ?? '—' }}</dd>
                            </div>
                            <div class="d-flex justify-content-between">
                                <dt class="fw-normal text-muted">Criado em</dt>
                                <dd class="mb-0">{{ $post->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="d-flex justify-content-between">
                                <dt class="fw-normal text-muted">Atualizado</dt>
                                <dd class="mb-0">{{ $post->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Ações --}}
                <div class="d-flex flex-column gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-floppy me-1"></i> Salvar alterações
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-1"></i> Voltar
                    </a>
                    <button type="button"
                        class="btn btn-outline-danger w-100"
                        onclick="confirmDelete('{{ route('admin.posts.destroy', $post) }}')">
                        <i class="bi bi-trash3 me-1"></i> Excluir post
                    </button>
                </div>

            </div>
        </div>

    </form>
</div>

{{-- Form oculto para DELETE --}}
<form id="delete-form" method="POST" style="display:none">
    @csrf @method('DELETE')
</form>

@endsection

{{-- =====================================================================
     SCRIPTS
====================================================================== --}}
@push('scripts')
<script>
// ── Slug preview dinâmico ─────────────────────────────────────────────
const titleInput    = document.getElementById('title');
const slugPreview   = document.getElementById('slug-preview-text');
const originalTitle = @json($post->title);

titleInput.addEventListener('input', function () {
    if (this.value.trim() === originalTitle.trim()) {
        slugPreview.textContent = @json($post->slug);
        return;
    }
    const slug = this.value
        .toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-');
    slugPreview.textContent = slug || '...';
});

// ── Preview de imagem de capa ─────────────────────────────────────────
const imageInput  = document.getElementById('image');
const previewWrap = document.getElementById('image-preview-wrap');
const previewImg  = document.getElementById('image-preview');
const removeBtn   = document.getElementById('remove-image');

imageInput.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src          = e.target.result;
            previewImg.style.display = '';
            if (!removeBtn) {
                const btn = document.createElement('button');
                btn.type      = 'button';
                btn.id        = 'remove-image';
                btn.title     = 'Remover imagem';
                btn.innerHTML = '<i class="bi bi-x-lg"></i>';
                btn.addEventListener('click', clearImage);
                previewWrap.appendChild(btn);
            }
        };
        reader.readAsDataURL(this.files[0]);
    }
});

if (removeBtn) {
    removeBtn.addEventListener('click', clearImage);
}

function clearImage() {
    imageInput.value         = '';
    previewImg.src           = '';
    previewImg.style.display = 'none';
    const btn = document.getElementById('remove-image');
    if (btn) btn.remove();
}

// ── Drop zone de novos anexos ─────────────────────────────────────────
const dropZone   = document.getElementById('drop-zone');
const fileInput  = document.getElementById('attachments');
const attList    = document.getElementById('attachment-list');
let   selectedFiles = [];

dropZone.addEventListener('click', () => fileInput.click());
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    addFiles(Array.from(e.dataTransfer.files));
});

fileInput.addEventListener('change', function () { addFiles(Array.from(this.files)); });

function addFiles(files) {
    files.forEach(f => {
        if (!selectedFiles.find(sf => sf.name === f.name && sf.size === f.size)) {
            selectedFiles.push(f);
        }
    });
    syncFileInput();
    renderAttachmentList();
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    syncFileInput();
    renderAttachmentList();
}

function syncFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}

function renderAttachmentList() {
    attList.innerHTML = '';
    selectedFiles.forEach((f, i) => {
        const ext  = f.name.split('.').pop().toUpperCase();
        const size = f.size < 1024 * 1024
            ? (f.size / 1024).toFixed(1) + ' KB'
            : (f.size / 1024 / 1024).toFixed(1) + ' MB';

        const div = document.createElement('div');
        div.className = 'attachment-item';
        div.innerHTML = `
            <i class="bi bi-file-earmark-text"></i>
            <span class="att-name" title="${f.name}">${f.name}</span>
            <span class="att-size">${ext} · ${size}</span>
            <button type="button" class="att-remove" onclick="removeFile(${i})" title="Remover">
                <i class="bi bi-x-lg"></i>
            </button>`;
        attList.appendChild(div);
    });
}

// ── Toggle data de publicação ─────────────────────────────────────────
const publishedSwitch = document.getElementById('published');
const publishedAtWrap = document.getElementById('published-at-wrap');

function togglePublishedAt() {
    publishedAtWrap.style.display = publishedSwitch.checked ? 'block' : 'none';
}

publishedSwitch.addEventListener('change', togglePublishedAt);
togglePublishedAt();

// ── Remoção de anexo existente (AJAX) ─────────────────────────────────
function removeAttachment(id, url) {
    Swal.fire({
        title: 'Remover anexo?',
        text: 'O arquivo será excluído permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'var(--color-danger)',
        cancelButtonColor: 'var(--color-muted)',
        confirmButtonText: 'Remover',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: '_method=DELETE',
        })
        .then(res => {
            if (res.ok) {
                document.getElementById('att-' + id)?.remove();
                Swal.fire({ icon: 'success', title: 'Removido!', timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Erro ao remover o anexo.' });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Falha na conexão.' }));
    });
}

// ── Confirmar exclusão do post ────────────────────────────────────────
function confirmDelete(url) {
    Swal.fire({
        title: 'Excluir post?',
        text: 'Esta ação é irreversível. Todos os anexos também serão removidos.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'var(--color-danger)',
        cancelButtonColor: 'var(--color-muted)',
        confirmButtonText: '<i class="bi bi-trash3 me-1"></i> Excluir',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = url;
            form.submit();
        }
    });
}
</script>
@endpush
