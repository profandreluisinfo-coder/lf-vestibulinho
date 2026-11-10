@extends('layouts.admin')

@section('page-title', 'Agendamento de Prova')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar2-week text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Alocar Candidatos</h6>
            </div>

            {{-- <a href="{{ route('admin.exam.show') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i>Visualizar Alocação
            </a> --}}
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                    value="{{ $process?->exam_date?->format('d/m/Y') ?? '' }}" readonly style="background-color: #e6e6e6"
                    title="Não é possível editar este campo.">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="exam_time">Hora da prova:</label>
                <input type="text" name="exam_time" class="form-control" id="exam_time"
                    value="{{ $process?->exam_time?->format('H:i') ?? '' }}" readonly style="background-color: #e6e6e6"
                    title="Não é possível editar este campo.">
            </div>

            <button type="submit" class="btn btn-success btn-sm">
                <i class="bi bi-check-circle me-1"></i>Salvar
            </button>

            <a href="{{ route('admin.exam.show') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>
        </form>

    </div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush
