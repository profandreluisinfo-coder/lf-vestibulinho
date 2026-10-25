 @extends('layouts.admin')

@section('page-title', 'Candidatos por Sala')

@section('content')
    <div class="container-fluid">

        {{-- Cabeçalho --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h5 class="mb-1">
                    <i class="bi bi-card-list me-2 text-primary"></i>
                    Candidatos por Sala
                </h5>
                <p class="text-muted small mb-0">
                    Relação de candidatos distribuídos por local e sala de prova.
                </p>
            </div>

            <a href="{{ route('admin.exam.create') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>
        </div>

        @forelse ($candidates as $location => $rooms)

            {{-- Local de prova --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt text-primary fs-5"></i>
                        <div>
                            <h6 class="mb-0">{{ $location }}</h6>
                            <small class="text-muted">
                                {{ $rooms->count() }}
                                {{ $rooms->count() === 1 ? 'sala' : 'salas' }}
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">

                    <div class="row g-3">

                        @foreach ($rooms as $room => $roomCandidates)

                            {{-- Sala --}}
                            <div class="col-12 col-xl-6">

                                <div class="card h-100 border">

                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <i class="bi bi-door-open me-2 text-secondary"></i>
                                            <strong>Sala {{ $room }}</strong>
                                        </div>

                                        <span class="badge bg-primary rounded-pill">
                                            {{ $roomCandidates->count() }}
                                            {{ $roomCandidates->count() === 1 ? 'candidato' : 'candidatos' }}
                                        </span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm align-middle mb-0">

                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 110px;">
                                                        Inscrição
                                                    </th>
                                                    <th>Nome do candidato</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($roomCandidates as $candidate)
                                                    <tr>
                                                        <td class="text-center text-muted">
                                                            {{ $candidate->inscription_id }}
                                                        </td>

                                                        <td class="text-break">
                                                            {{ $candidate->social_name ?? $candidate->name }} 
                                                            @if ($candidate?->pne == 1)<i class="bi bi-person-wheelchair text-primary" title="Pessoa com deficiência"></i>@endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>
            </div>

        @empty

            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard-x fs-1 text-muted"></i>

                    <h6 class="mt-3">Nenhum candidato alocado</h6>

                    <p class="text-muted mb-3">
                        Ainda não existem candidatos distribuídos por sala de prova.
                    </p>

                    <a href="{{ route('admin.exam.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-calendar-check me-1"></i>
                        Configurar prova
                    </a>
                </div>
            </div>

        @endforelse

    </div>
@endsection