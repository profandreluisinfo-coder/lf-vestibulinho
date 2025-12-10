<section id="provas-anteriores" class="py-5">

    <div class="container">
        <h2 class="section-title text-center">Provas</h2>
        <div class="row g-4 d-flex justify-content-center">
    
        @foreach ($files as $file)

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 {{ $loop->first ? 'border border-success bg-light' : '' }}">
                    <div class="card-body text-center">
                        <i class="bi bi-file-pdf text-danger mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Vestibulinho {{ $file->year }}</h5>
                        <p class="card-text">Prova completa para download</p>
                        <a href="{{ asset('storage/' . $file->file) }}" target="_blank"
                            class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-2"></i>Download
                        </a>
                    </div>
                </div>
            </div>

        @endforeach

        @if ($files->isNotEmpty())
            <div class="mt-4 text-center">
                <a href="{{ route('archives') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-search me-2"></i>Ver mais provas anteriores
                </a>
            </div>
        @endif

        </div>
    </div>
    
</section>