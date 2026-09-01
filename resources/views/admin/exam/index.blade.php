@extends('layouts.admin')

@section('page-title', config('app.name') . ' ' . $process?->year . ' | Provas')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-card-list text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Candidatos por Sala</h6>
            </div>

            <a href="{{ route('admin.exam.create') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="accordion" id="accordionCandidates">

            @foreach ($candidates as $roomLabel => $roomCandidates)
                <div class="accordion-item">

                    <h2 class="accordion-header" id="heading-{{ md5($roomLabel) }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ md5($roomLabel) }}" aria-expanded="false"
                            aria-controls="collapse-{{ md5($roomLabel) }}">
                            {{ $roomLabel }} ({{ $roomCandidates->count() }} candidatos)
                        </button>
                    </h2>

                    <div id="collapse-{{ md5($roomLabel) }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ md5($roomLabel) }}" data-bs-parent="#accordionCandidates">

                        <div class="accordion-body">

                            <div class="table-responsive">

                                <table class="table table-sm table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Inscrição</th>
                                            <th scope="col">CPF</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Nome Social</th>
                                            <th scope="col" class="text-center">PNE</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($roomCandidates as $candidate)

                                            <tr>
                                                <td>{{ $candidate->inscription_id }}</td>
                                                <td>{{ $candidate->candidate_cpf }}</td>
                                                <td>{{ $candidate->candidate_name }}</td>
                                                <td>
                                                    @if ($candidate->candidate_social_name)
                                                        {{ $candidate->candidate_social_name }}
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($candidate->candidate_pne == 1)
                                                        SIM
                                                    @else
                                                        NÃO
                                                    @endif
                                                </td>
                                            </tr>
                                        
                                        @empty

                                            <tr>
                                                <td colspan="5" class="text-center">Nenhum candidato nesta sala.</td>
                                            </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

@endsection