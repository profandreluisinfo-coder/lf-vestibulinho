<section id="calendario" class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-4">
            Informações do {{ config('app.name') }} {{ $calendar?->year }}
        </h2>
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="row g-4">
                {{-- Status das Inscrições --}}
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <i class="bi {{ $statusData['icon'] }} text-{{ $statusData['color'] }} fs-3 me-3"></i>
                        <div>
                            <h6 class="fw-semibold text-{{ $statusData['color'] }} mb-1">
                                {{ $statusData['title'] }}
                            </h6>
                            <p class="text-muted small mb-2">
                                {!! $statusData['message'] !!}
                            </p>
                            @if ($statusData['show_button'])
                                <a href="{{ route('register') }}" 
                                   class="btn btn-sm btn-{{ $statusData['color'] }} px-3 text-white">
                                    Inscrever-se
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Locais de Prova --}}
                @if ($settings->location)
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill text-primary fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-semibold text-primary mb-1">Locais de Prova Disponíveis</h6>
                                <p class="text-muted small mb-2">
                                    Os locais de prova já estão disponíveis.
                                    Acesse a
                                    <a href="{{ route('login') }}"
                                        class="fw-semibold text-primary text-decoration-none">
                                        Área do Candidato
                                    </a>.
                                </p>
                                <a href="{{ route('login') }}" class="btn btn-primary-alt btn-sm px-3 text-white">
                                    Acessar
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Resultados --}}
                @if ($settings->result)
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-semibold text-success mb-1">Resultados Publicados</h6>
                                <p class="text-muted small mb-2">
                                    O resultado do processo seletivo está disponível.
                                    Veja na
                                    <a href="{{ route('results') }}"
                                        class="fw-semibold text-success text-decoration-none">
                                        página de resultados
                                    </a> ou na <a href="{{ route('login') }}" class="fw-semibold text-success text-decoration-none">Área do Candidato</a>.
                                </p>
                                <a href="{{ route('results') }}" class="btn btn-success btn-sm px-3">
                                    Ver Resultado
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Convocação para Matrícula --}}
                @if ($chamadaDisponivel)
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi {{ $matriculaIcon }} text-{{ $matriculaColor }} fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-semibold text-{{ $matriculaColor }} mb-1">{{ $matriculaTitle }}</h6>
                                <p class="text-muted small mb-2">
                                    @if ($chamadaDisponivel)
                                        A convocação para matrícula dos classificados na
                                        <strong>1ª chamada</strong> está disponível!
                                        Confira a lista e siga as instruções para matrícula.
                                    @endif
                                </p>
                                <a href="{{ route('calls') }}"
                                    class="btn btn-{{ $matriculaColor }} btn-sm px-3 text-white">
                                    Ver Convocação
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</section>
