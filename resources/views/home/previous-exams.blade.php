<section id="provas-anteriores" class="py-5">
    <div class="container">
        <h2 class="section-title text-center">Provas</h2>

        <div id="carouselAutoplaying" class="carousel carousel-dark slide position-relative" data-bs-ride="carousel"
            style="padding: 0 60px;">
            <!-- Indicadores -->
            <div class="carousel-indicators d-none">
                @for ($i = 0; $i < ceil(count($files) / 3); $i++)
                    <button type="button" data-bs-target="#carouselAutoplaying"
                        data-bs-slide-to="{{ $i }}"
                        class="{{ $i === 0 ? 'active' : '' }}"
                        aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $i + 1 }}"></button>
                @endfor
            </div>

            <div class="carousel-inner">
                @foreach ($files->chunk(3) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}" data-bs-interval="10000">
                        <div class="row g-4">
                            @foreach ($chunk as $file)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card h-100 text-center border border-1 shadow-sm position-relative"
                                        style="min-height: 250px;">
                                        @if ($file->year == $files->max('year'))
                                            <div class="badge-novo position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-success">
                                                    <i class="bi bi-star-fill me-1"></i>Mais Recente
                                                </span>
                                            </div>
                                        @endif
                                        <div
                                            class="card-body d-flex flex-column justify-content-center align-items-center">
                                            <i class="bi bi-file-pdf text-danger mb-3" style="font-size: 3rem;"></i>
                                            <h5 class="card-title">Vestibulinho {{ $file->year }}</h5>
                                            <p class="card-text text-muted">Prova completa para download</p>
                                            <a href="{{ asset('storage/' . $file->file) }}" target="_blank"
                                                class="btn btn-outline-primary mt-auto">
                                                <i class="bi bi-download me-2"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if ($chunk->count() < 3)
                                @for ($i = $chunk->count(); $i < 3; $i++)
                                    <div class="col-md-4 col-sm-6"></div>
                                @endfor
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Controles posicionados fora -->
            <button class="carousel-control-prev" type="button"
                data-bs-target="#carouselAutoplaying" data-bs-slide="prev"
                style="left: -60px; width: 50px;">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button"
                data-bs-target="#carouselAutoplaying" data-bs-slide="next"
                style="right: -60px; width: 50px;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('archives') }}" class="btn btn-sm btn-primary">Ver todas as provas</a>
            </div>
        </div>
    </div>
</section>
