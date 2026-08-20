@extends('layouts.forms')

@section('page-title', 'Inscrição - Informações Complementares')

@section('content')

    <form id="inscription" class="row g-4" action="{{ route('inscription.step.other') }}" method="POST"
        enctype="multipart/form-data">

        @csrf

        <h5 class="fw-semibold border-bottom pb-1">Informações Complementares</h5>

        <div class="form-group col-md-6">
            <label for="alergia" class="form-label required">Tem algum problema de saúde ou alergia?</label>
            <select class="form-select @error('health') is-invalid @enderror" name="health" id="health">
                @php
                    $selectedHealth = old('health', session('step7.health'));
                @endphp
                <option value="">Selecione...</option>
                <option value="1" {{ $selectedHealth == 1 ? 'selected' : '' }}>Sim</option>
                <option value="2" {{ $selectedHealth == 2 ? 'selected' : '' }}>Não</option>
            </select>
            @error('allergy')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6 {{ $selectedHealth == 1 ? '' : 'd-none' }}">
            <label for="health_issue" class="form-label required">Se SIM, qual?</label>
            <input list="healthIssues" class="form-control @error('health_issue') is-invalid @enderror" id="health_issue"
                name="health_issue" value="{{ old('health_issue', session('step7.health_issue')) }}"
                aria-describedby="msgHealthIssues">
            <small id="msgHealthIssues" class="text-muted fst-italic"><i class="bi bi-info-circle"></i> Clique para escolher
                um item da lista ou descreva.</small>

            <datalist id="healthIssues">
                @foreach ($healthIssues as $healthIssue)
                    <option value="{{ $healthIssue->description }}">
                @endforeach
            </datalist>

            @error('health_issue')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <h6 class="fw-semibold border-bottom pb-1 mt-4">Programas Sociais</h6>

        <div class="form-group col-md-6 mt-3 mb-3">
            <label for="programasSociais" class="form-label required">
                Beneficiário do Bolsa-Família do Governo Federal?
            </label>
            <select class="form-select @error('social_program') is-invalid @enderror" id="social_program"
                name="social_program">
                @php
                    $selectedProgram = old('social_program', session('step7.social_program'));
                @endphp
                <option value="">Selecione...</option>
                <option value="1" {{ $selectedProgram == 1 ? 'selected' : '' }}>Sim</option>
                <option value="2" {{ $selectedProgram == 2 ? 'selected' : '' }}>Não</option>
            </select>
            @error('social_program')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6 mt-3 mb-3 {{ $selectedProgram == 1 ? '' : 'd-none' }}">
            <label for="inscricaoSocial" class="form-label required">
                Informe o seu NIS – Número de Identificação Social
            </label>
            <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis"
                value="{{ old('nis', session('step7.nis')) }}">
            @error('nis')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-12 border-top pt-3">
            <button type="button" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i>
                <a href="{{ route('inscription.step.pcd') }}" class="text-decoration-none">Voltar</a>
            </button>
            <button type="submit" class="btn btn-sm btn-primary ms-2">Avançar <i
                    class="bi bi-arrow-right-circle ms-2"></i>
            </button>
        </div>
    </form>

@endsection

@push('plugins')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/cleave/masks.js') }}"></script>
    <script src="{{ asset('assets/js/inscription/ui/others.js') }}"></script>
    <script src="{{ asset('assets/js/inscription/rules/others.js') }}"></script>
@endpush
