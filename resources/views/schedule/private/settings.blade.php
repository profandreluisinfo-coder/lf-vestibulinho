@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Provas')


@section('dash-content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-calendar2-week me-2"></i>Agendamento de Prova</h4>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setLocalModal">
            <i class="bi bi-plus-circle me-1"></i> Novo Agendamento
        </a>
    </div>

    {{-- Exibição dos dados cadastrados --}}
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-striped freezed-table mb-0 caption-top">
            <caption>{{ config('app.name') }} {{ config('app.year') }} - Lista de Alocação de Candidatos</caption>

            @if ($examInfo)
                <p><strong>Data da Prova:</strong> {{ \Carbon\Carbon::parse($examInfo->date)->format('d/m/Y') }}</p>
                <p><strong>Horário:</strong> {{ \Carbon\Carbon::parse($examInfo->time)->format('H:i') }}</p>

                <form id="location-access-form" action="{{ route('exam.access.location') }}" method="POST">
                    @csrf
                    <div class="form-check form-switch mb-3 mt-3">
                        <input class="form-check-input" type="checkbox" id="location" name="location"
                            onchange="confirmLocationAccess(this)" {{ $accesStatus->location ? 'checked' : '' }}>
                        <label class="form-check-label" for="location">
                            Acesso aos locais:
                            <span class="badge bg-{{ $accesStatus->location ? 'success' : 'danger' }} ms-2">
                                {{ $accesStatus->location ? 'Liberado' : 'Bloqueado' }}
                            </span>
                        </label>
                    </div>
                </form>
                
                @if ($accesStatus->location)
                <div class="mt-3">
                    <a href="{{ route('system.queue.monitor')}}" class="text-decoration-none" target="_blank"><i class="bi bi-display animate__animated animate__fadeIn me-2"></i>Monitoramento de Fila de Envio de E-mails</a>
                </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('exam.list') }}" class="text-decoration-none" target="_blank">
                        <i class="bi bi-search animate__animated animate__fadeIn me-2"></i> Detalhes
                    </a>
                    <span class="mx-2">|</span>
                    {{-- Relatórios --}}
                    <a href="{{ route('report.exportAllocationToPdf') }}" target="_blank" class="text-decoration-none">
                        <i class="bi bi-file-earmark-pdf-fill animate__animated animate__fadeIn me-2"></i> Relatório para simples conferência
                    </a>
                    <span class="mx-2">|</span>
                    <a href="{{ route('report.exportRoomsToPdf') }}" target="_blank"
                        class="text-decoration-none">
                        <i class="bi bi-file-earmark-pdf-fill animate__animated animate__fadeIn me-2"></i> Mural e Salas
                        (PDF)
                    </a>
                    <span class="mx-2">|</span>
                    <a href="{{ route('report.exportSignaturesSheetsToPdf') }}" target="_blank"
                        class="text-decoration-none">
                        <i class="bi bi-file-earmark-pdf-fill animate__animated animate__fadeIn me-2"></i> Assinaturas (PDF)
                    </a>
                </div>
            @endif

            <thead class="table-success text-center">
                <tr>
                    <th scope="col">Local</th>
                    <th scope="col">Sala</th>
                    <th scope="col">Candidatos</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @forelse($rooms as $room)
                    <tr>
                        <td>{{ $room->location_name }}</td>
                        <td>{{ $room->room_number }}</td>
                        <th>{{ $room->qtd }}</th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Nenhum agendamento encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal de definição de local --}}
    <div class="modal fade" id="setLocalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setLocalModalLabel"><i class="bi bi-calendar2-week me-2"></i>Agendar Prova</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="exam-schedule" method="POST" action="{{ route('exam.schedule') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label required" for="candidates_per_room">Nº de candidatos por sala:</label>
                            <input type="number" name="candidates_per_room" class="form-control" id="candidates_per_room"
                                min="1" max="50">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label required" for="split_locations">Deseja dividir os locais?</label>
                            <select name="split_locations" class="form-select form-select-md" id="split_locations">
                                <option value="no">Não</option>
                                <option value="yes">Sim</option>
                            </select>
                        </div>

                        <div class="form-group d-none mb-3" id="split_start_wrapper">
                            <label class="form-label required" for="split_from_room">Dividir locais a partir da sala
                                nº:</label>
                            <input type="number" name="split_from_room" class="form-control" id="split_from_room"
                                min="2">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label required" for="exam_date">Data da prova:</label>
                            <input type="date" name="exam_date" class="form-control" id="exam_date"
                                value="{{ $examDate }}" readonly style="background-color: #e6e6e6"
                                title="Não é possível editar este campo.">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label required" for="exam_time">Hora da prova:</label>
                            <input type="time" name="exam_time" class="form-control" id="exam_time">
                        </div>

                        <button type="submit" class="btn btn-primary">Iniciar Alocação</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/rules/schedule.js') }}"></script>
    <script src="{{ asset('assets/swa/locations/seetings.js') }}"></script>
@endpush