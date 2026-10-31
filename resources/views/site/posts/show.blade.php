@extends('layouts.site')

@section('title', $post?->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/posts/show.css') }}" />
@endpush

@section('content')

    @if (!empty($isPreview))
        <div class="alert alert-warning text-center mb-0" role="status">
            <i class="bi bi-eye me-1"></i>
            Pré-visualização administrativa — esta postagem ainda não foi publicada.
        </div>
    @endif

    @php
        $isComunicado = $post->type === \App\Models\Post::TYPE_INFO;
        $typeLabel = $isComunicado ? 'Comunicado' : 'Notícia';
        $badgeClass = $isComunicado ? 'news-card-badge-navy' : 'news-card-badge-teal';
        $listaRoute = $isComunicado ? route('site.notices.index') : route('site.news.index');
        $listaLabel = $isComunicado ? 'Comunicados' : 'Notícias';
        $shareUrl = route('site.posts.show', $post->slug);
        $shareTitle = $post->title;
        $whatsappShareUrl = 'https://wa.me/?' . http_build_query(['text' => $shareTitle . ' ' . $shareUrl]);
        $facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => $shareUrl]);
        $xShareUrl = 'https://twitter.com/intent/tweet?' . http_build_query(['text' => $shareTitle, 'url' => $shareUrl]);
        $linkedinShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?' . http_build_query(['url' => $shareUrl]);
        $imageAttachments = $post->attachments->filter(fn ($attachment) => \Illuminate\Support\Str::startsWith($attachment->mime_type ?? '', 'image/'));
        $downloadAttachments = $post->attachments->reject(fn ($attachment) => \Illuminate\Support\Str::startsWith($attachment->mime_type ?? '', 'image/'));
    @endphp

    <section class="post-breadcrumb-section">
        <div class="container-lg">
            <nav class="post-breadcrumb-nav" aria-label="Navegação estrutural">
                <a href="{{ route('home') }}" class="post-breadcrumb-link">
                    <i class="bi bi-house-door" aria-hidden="true"></i>
                    <span>Início</span>
                </a>
                <i class="bi bi-chevron-right post-breadcrumb-separator" aria-hidden="true"></i>
                <a href="{{ $listaRoute }}" class="post-breadcrumb-link">{{ $listaLabel }}</a>
                <i class="bi bi-chevron-right post-breadcrumb-separator" aria-hidden="true"></i>
                <span class="post-breadcrumb-current" aria-current="page">{{ $post->title }}</span>
            </nav>
        </div>
    </section>

    <section class="post-section">
        <div class="container-lg">
            <article class="post-container">
                <header class="post-header">
                    <span class="news-card-badge {{ $badgeClass }} post-badge">
                        <i class="bi {{ $isComunicado ? 'bi-megaphone' : 'bi-journal-text' }}" aria-hidden="true"></i>
                        {{ $typeLabel }}
                    </span>

                    <h1 class="section-title post-title">{{ $post->title }}</h1>

                    <div class="post-info-container" aria-label="Informações da publicação">
                        <div class="post-meta-item">
                            <i class="bi bi-calendar3" aria-hidden="true"></i>
                            <span>
                                <strong class="post-info-label">Publicado em</strong>
                                {{ $post->published_at->translatedFormat('d \d\e F \d\e Y') }}
                            </span>
                        </div>

                        @if ($post->author)
                            <div class="post-meta-item">
                                <i class="bi bi-person-circle" aria-hidden="true"></i>
                                <span>
                                    <strong class="post-info-label">Publicado por</strong>
                                    {{ $post->author->name }}
                                </span>
                            </div>
                        @endif
                    </div>
                </header>

                @if ($post->image)
                    <figure class="post-image-container">
                        <img src="{{ $previewImageUrl ?? Storage::url($post->image) }}" alt="{{ $post->title }}">
                    </figure>
                @endif

                <div class="post-content">
                    {!! $post->content !!}
                </div>

                @if ($post->video)
                    <div class="post-video-container" aria-label="Vídeo relacionado">
                        @if ($post->video['type'] === 'iframe')
                            <iframe src="{{ $post->video['src'] }}" title="{{ $post->title }}" loading="lazy"
                                allowfullscreen></iframe>
                        @else
                            <video controls preload="metadata" src="{{ $post->video['src'] }}"></video>
                        @endif
                    </div>
                @endif

                @if (!$post->video && $post->url && \Illuminate\Support\Str::startsWith($post->url, ['http://', 'https://']))
                    <div class="post-link-container">
                        <a href="{{ $post->url }}" class="post-external-link" target="_blank" rel="noopener noreferrer">
                            <span class="post-external-link-icon"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i></span>
                            <span><strong>Acesse o conteúdo relacionado</strong><small>Você será direcionado para uma página externa</small></span>
                            <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif

                @if ($imageAttachments->isNotEmpty())
                    <section class="post-gallery" aria-labelledby="post-gallery-title">
                        <div class="post-attachments-heading">
                            <span class="post-attachments-icon"><i class="bi bi-images" aria-hidden="true"></i></span>
                            <div>
                                <h2 class="post-attachments-title" id="post-gallery-title">Galeria de imagens</h2>
                                <p>Imagens desta publicação</p>
                            </div>
                        </div>

                        <div class="post-gallery-carousel" data-post-gallery>
                            <button class="post-gallery-control" type="button" data-gallery-previous aria-label="Imagem anterior" aria-controls="post-gallery-track" disabled>
                                <i class="bi bi-chevron-left" aria-hidden="true"></i>
                            </button>
                            <div class="post-gallery-viewport" data-gallery-viewport id="post-gallery-track" tabindex="0" role="region" aria-label="Galeria de imagens. Use as setas do teclado para navegar.">
                                <ul class="post-gallery-list">
                                    @foreach ($imageAttachments as $index => $attachment)
                                        @php
                                            $galleryImageUrl = $attachment->preview_url ?? Storage::url($attachment->path);
                                        @endphp
                                        <li class="post-gallery-item">
                                            <button class="post-gallery-open" type="button" data-lightbox-index="{{ $loop->index }}" aria-label="Ampliar imagem {{ $index + 1 }}">
                                                <img src="{{ $galleryImageUrl }}" alt="Imagem {{ $index + 1 }} da publicação: {{ $post->title }}" loading="lazy">
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <button class="post-gallery-control" type="button" data-gallery-next aria-label="Próxima imagem" aria-controls="post-gallery-track" {{ $imageAttachments->count() < 2 ? 'disabled' : '' }}>
                                <i class="bi bi-chevron-right" aria-hidden="true"></i>
                            </button>
                            <span class="visually-hidden" data-gallery-status aria-live="off"></span>
                        </div>

                        <dialog class="post-lightbox" data-post-lightbox aria-labelledby="post-lightbox-title">
                            <div class="post-lightbox-toolbar">
                                <h2 class="visually-hidden" id="post-lightbox-title">Imagem ampliada da publicação</h2>
                                <span class="post-lightbox-count" data-lightbox-count aria-live="polite"></span>
                                <button class="post-lightbox-close" type="button" data-lightbox-close aria-label="Fechar imagem ampliada">
                                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="post-lightbox-stage">
                                <button class="post-lightbox-nav" type="button" data-lightbox-previous aria-label="Imagem anterior">
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                </button>
                                <img class="post-lightbox-image" data-lightbox-image alt="">
                                <button class="post-lightbox-nav" type="button" data-lightbox-next aria-label="Próxima imagem">
                                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                </button>
                            </div>
                        </dialog>
                    </section>
                @endif

                @if ($downloadAttachments->isNotEmpty())
                    <section class="post-attachments" aria-labelledby="post-attachments-title">
                        <div class="post-attachments-heading">
                            <span class="post-attachments-icon"><i class="bi bi-paperclip" aria-hidden="true"></i></span>
                            <div>
                                <h2 class="post-attachments-title" id="post-attachments-title">Arquivos para download</h2>
                                <p>Materiais e documentos desta publicação</p>
                            </div>
                        </div>

                        <ul class="post-attachments-list">
                            @foreach ($downloadAttachments as $attachment)
                                <li>
                                    @if (!empty($isPreview))
                                        <span class="post-attachment-link">
                                            <i class="bi {{ $attachment->mime_type === 'application/pdf' ? 'bi-file-earmark-pdf-fill' : 'bi-file-earmark-fill' }}" aria-hidden="true"></i>
                                            <span class="post-attachment-name">{{ $attachment->name }}</span>
                                            @if ($attachment->size)
                                                <small class="post-attachment-size">{{ number_format($attachment->size / 1024, 0, ',', '.') }} KB</small>
                                            @endif
                                        </span>
                                    @else
                                        <a href="{{ Storage::url($attachment->path) }}" class="post-attachment-link" target="_blank" rel="noopener">
                                            <i class="bi {{ $attachment->mime_type === 'application/pdf' ? 'bi-file-earmark-pdf-fill' : 'bi-file-earmark-fill' }}" aria-hidden="true"></i>
                                            <span class="post-attachment-name">{{ $attachment->name }}</span>
                                            @if ($attachment->size)
                                                <small class="post-attachment-size">{{ number_format($attachment->size / 1024, 0, ',', '.') }} KB</small>
                                            @endif
                                            <i class="bi bi-download post-attachment-download" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if (empty($isPreview))
                    <section class="post-share" aria-labelledby="post-share-title">
                        <div class="post-share-heading">
                            <h2 id="post-share-title">Compartilhe esta publicação</h2>
                            <p>Ajude outras pessoas a encontrar este conteúdo.</p>
                        </div>
                        <div class="post-share-actions">
                            <a class="post-share-button post-share-whatsapp" href="{{ $whatsappShareUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no WhatsApp">
                                <i class="bi bi-whatsapp" aria-hidden="true"></i><span>WhatsApp</span>
                            </a>
                            <a class="post-share-button post-share-facebook" href="{{ $facebookShareUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no Facebook">
                                <i class="bi bi-facebook" aria-hidden="true"></i><span>Facebook</span>
                            </a>
                            <a class="post-share-button post-share-x" href="{{ $xShareUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no X">
                                <i class="bi bi-twitter-x" aria-hidden="true"></i><span>X</span>
                            </a>
                            <a class="post-share-button post-share-linkedin" href="{{ $linkedinShareUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no LinkedIn">
                                <i class="bi bi-linkedin" aria-hidden="true"></i><span>LinkedIn</span>
                            </a>
                            <button class="post-share-button post-share-copy" type="button" data-copy-url="{{ $shareUrl }}">
                                <i class="bi bi-link-45deg" aria-hidden="true"></i><span>Copiar link</span>
                            </button>
                        </div>
                        <span class="post-share-feedback" role="status" aria-live="polite"></span>
                    </section>
                @endif

                @if ($previous || $next)
                    <nav class="post-nav-container" aria-label="Outras publicações">
                        @if ($previous)
                            <a href="{{ route('site.posts.show', $previous->slug) }}" class="post-nav-link">
                                <span class="post-nav-label"><i class="bi bi-arrow-left" aria-hidden="true"></i> Publicação anterior</span>
                                <span class="post-nav-title">{{ \Illuminate\Support\Str::limit($previous->title, 70) }}</span>
                            </a>
                        @endif

                        @if ($next)
                            <a href="{{ route('site.posts.show', $next->slug) }}" class="post-nav-link post-nav-link-next">
                                <span class="post-nav-label">Próxima publicação <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                                <span class="post-nav-title">{{ \Illuminate\Support\Str::limit($next->title, 70) }}</span>
                            </a>
                        @endif
                    </nav>
                @endif

                <footer class="post-back-container">
                    <a href="{{ $listaRoute }}" class="post-back-link">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        Voltar para {{ mb_strtolower($listaLabel) }}
                    </a>
                </footer>
            </article>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.post-share-copy').forEach((button) => {
            button.addEventListener('click', async () => {
                const feedback = button.closest('.post-share').querySelector('.post-share-feedback');

                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(button.dataset.copyUrl);
                    } else {
                        const input = document.createElement('textarea');
                        input.value = button.dataset.copyUrl;
                        input.setAttribute('readonly', '');
                        input.style.position = 'fixed';
                        input.style.opacity = '0';
                        document.body.appendChild(input);
                        input.select();
                        const copied = document.execCommand('copy');
                        input.remove();
                        if (!copied) throw new Error('Não foi possível copiar o link.');
                    }

                    feedback.textContent = 'Link copiado.';
                } catch (error) {
                    feedback.textContent = 'Não foi possível copiar. Copie o endereço da página.';
                }
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('[data-post-gallery]').forEach((gallery) => {
            const images = [...gallery.querySelectorAll('[data-lightbox-index]')].map((button) => ({
                src: button.querySelector('img').src,
                alt: button.querySelector('img').alt
            }));
            const lightbox = gallery.querySelector('[data-post-lightbox]');
            const lightboxImage = lightbox.querySelector('[data-lightbox-image]');
            const count = lightbox.querySelector('[data-lightbox-count]');
            const previous = lightbox.querySelector('[data-lightbox-previous]');
            const next = lightbox.querySelector('[data-lightbox-next]');
            let activeIndex = 0;

            const showImage = (index) => {
                activeIndex = (index + images.length) % images.length;
                lightboxImage.src = images[activeIndex].src;
                lightboxImage.alt = images[activeIndex].alt;
                count.textContent = `Imagem ${activeIndex + 1} de ${images.length}`;
                previous.hidden = next.hidden = images.length < 2;
            };

            gallery.querySelectorAll('[data-lightbox-index]').forEach((button) => {
                button.addEventListener('click', () => {
                    showImage(Number(button.dataset.lightboxIndex));
                    lightbox.showModal();
                });
            });

            previous.addEventListener('click', () => showImage(activeIndex - 1));
            next.addEventListener('click', () => showImage(activeIndex + 1));
            lightbox.querySelector('[data-lightbox-close]').addEventListener('click', () => lightbox.close());
            lightbox.addEventListener('click', (event) => {
                if (event.target === lightbox) lightbox.close();
            });
            lightbox.addEventListener('keydown', (event) => {
                if (images.length < 2) return;
                if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                    event.preventDefault();
                    showImage(activeIndex + (event.key === 'ArrowRight' ? 1 : -1));
                }
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('[data-post-gallery]').forEach((gallery) => {
            const viewport = gallery.querySelector('[data-gallery-viewport]');
            const previous = gallery.querySelector('[data-gallery-previous]');
            const next = gallery.querySelector('[data-gallery-next]');
            const slides = [...gallery.querySelectorAll('.post-gallery-item')];
            const status = gallery.querySelector('[data-gallery-status]');
            const slideStep = () => slides[0].offsetWidth + parseFloat(getComputedStyle(slides[0].parentElement).columnGap || 0);
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            let autoplayTimer;

            const updateControls = () => {
                const index = Math.round(viewport.scrollLeft / slideStep());
                const atEnd = viewport.scrollLeft + viewport.clientWidth >= viewport.scrollWidth - 1;
                previous.disabled = index <= 0;
                next.disabled = atEnd;
                status.textContent = `Imagem ${Math.min(index + 1, slides.length)} de ${slides.length}`;

                if (!autoplayTimer && viewport.scrollWidth > viewport.clientWidth + 1 && !reducedMotion.matches) {
                    autoplayTimer = window.setInterval(autoplay, 4500);
                }
            };

            const move = (direction) => {
                const index = Math.round(viewport.scrollLeft / slideStep());
                const maxScroll = viewport.scrollWidth - viewport.clientWidth;
                const target = Math.max(0, Math.min(maxScroll, (index + direction) * slideStep()));
                viewport.scrollTo({
                    left: target,
                    behavior: reducedMotion.matches ? 'auto' : 'smooth'
                });
            };

            function autoplay() {
                if (reducedMotion.matches || document.hidden || gallery.matches(':hover') || gallery.contains(document.activeElement)) {
                    return;
                }

                const maxScroll = viewport.scrollWidth - viewport.clientWidth;
                if (viewport.scrollLeft >= maxScroll - 2) {
                    viewport.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    move(1);
                }
            }

            previous.addEventListener('click', () => move(-1));
            next.addEventListener('click', () => move(1));
            viewport.addEventListener('scroll', updateControls, { passive: true });
            viewport.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                    event.preventDefault();
                    move(event.key === 'ArrowRight' ? 1 : -1);
                }
            });
            window.addEventListener('resize', updateControls);
            reducedMotion.addEventListener('change', () => {
                if (reducedMotion.matches && autoplayTimer) {
                    window.clearInterval(autoplayTimer);
                    autoplayTimer = null;
                } else {
                    updateControls();
                }
            });
            updateControls();
        });
    </script>
@endpush
