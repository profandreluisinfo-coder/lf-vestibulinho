@extends('layouts.admin')

@section('page-title', 'Agendamento de Prova')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar2-week text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Agendamento de Prova</h6>
            </div>

            <a href="{{ route('admin.exam.show') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i>Visualizar Agendamento
            </a>
        </div>

        <div id="meu-alert" class="alert alert-info d-flex align-items-start border-0 rounded-3 mt-4 p-3" role="alert">

            <div class="me-3 fs-3" aria-hidden="true">
                <i class="bi bi-info-circle-fill"></i>
            </div>

            <div class="flex-grow-1">
                <h5 class="alert-heading mb-2">Informação Importante</h5>
                <p class="mb-0">
                    Para agendar uma nova prova, clique no botão <i class="bi bi-calendar2-week me-1"></i>Agendar
                    Prova. Este procedimento irá substituir o agendamento anterior.
                </p>
                <p>Regras:</p>
                <ul>
                    <li>só roda depois do encerramento das inscrições do processo atual;</li>
                    <li>só roda quando não há análise de PNE ou de nome social pendente;</li>
                    <li>apenas inscrições deferidas do processo seletivo atual;</li>
                    <li>candidatos PNE ocupam sempre as primeiras salas, separados dos demais;</li>
                    <li>dentro de cada grupo, a ordem é alfabética (considerando o nome social aceito);</li>
                    <li>o sistema comporta no máximo dois locais de prova;</li>
                    <li>se 'Deseja dividir os locais' = 'sim', as salas a partir de 'Dividir locais a partir da sala nº:' vão para o segundo local.
                    </li>
                </ul>
                <p class="mb-0 mt-2 small opacity-75">
                    Em caso de dúvidas, entre em contato com o suporte técnico.
                </p>
            </div>
        </div>

        <form id="exam-schedule" method="POST" action="{{ route('admin.exam.store') }}">
            @csrf
            <div class="form-group mb-3">
                <label class="form-label required" for="candidates_per_room">Nº de candidatos por
                    sala:</label>
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
                <input type="number" name="split_from_room" class="form-control" id="split_from_room" min="2">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="exam_date">Data da prova:</label>
                <input type="text" name="exam_date" class="form-control" id="exam_date"
                    value="{{ \Carbon\Carbon::parse($process->exam_date)->format('d/m/Y') }}" readonly
                    style="background-color: #e6e6e6" title="Não é possível editar este campo.">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="exam_time">Hora da prova:</label>
                <input type="text" name="exam_time" class="form-control" id="exam_time" value="{{ $process->exam_time }}"
                    readonly style="background-color: #e6e6e6" title="Não é possível editar este campo.">
            </div>

            <button type="submit" class="btn btn-success btn-sm">
                <i class="bi bi-check-circle me-1"></i>Salvar
            </button>
        </form>

    </div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/admin/exam/create.js') }}"></script>
@endpush
