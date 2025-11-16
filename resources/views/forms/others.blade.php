@extends('layouts.forms.master')

@section('page-title', 'Inscrição | Outras Informações')

@section('forms')

    <form id="inscription" class="row g-4" action="{{ route('step.other') }}" method="POST">
        @csrf
        <h5 class="fw-semibold border-bottom pb-1">Informações Complementares</h5>
        <div class="form-group col-md-6">
            <label for="alergia" class="form-label required">Tem algum problema de saúde ou alergia?</label>
            <select class="form-select @error('health') is-invalid @enderror" name="health" id="health">
                <option value="" selected>...</option>
                @php
                    $selectedHealth = old('health', session('step6.health'));
                @endphp
                @foreach ($options as $option => $value)
                    <option value="{{ $option }}" {{ $selectedHealth == $option ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('allergy')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6 {{ $selectedHealth == 1 ? '' : 'd-none' }}">
            <label for="health_issue" class="form-label required">Se SIM, qual?</label>
            <input list="healthIssues" class="form-control @error('health_issue') is-invalid @enderror" id="health_issue" name="health_issue" value="{{ old('health_issue', session('step6.health_issue')) }}" aria-describedby="msgHealthIssues">
            <small id="msgHealthIssues" class="form-text text-muted">Escolha um item da lista ou digite o problema de saúde ou alergia.</small>

            <datalist id="healthIssues">
                @foreach ($healthIssues as $key => $label)
                    <option value="{{ $label }}">
                @endforeach
            </datalist>

            @error('health_issue')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>


        <h6 class="fw-semibold border-bottom pb-1 mt-4">Educação Especial</h6>

        <div class="form-group col-md-6 mt-3 mb-3">
            <label for="acessibilidade" class="form-label required">
                Elegível aos serviços da educação especial?
            </label>
            <select class="form-select @error('accessibility') is-invalid @enderror" id="accessibility" name="accessibility">
                <option value="" selected>...</option>
                @php
                    $selectedAccessibility = old('accessibility', session('step6.accessibility'));
                @endphp
                @foreach ($options as $option => $value)
                    <option value="{{ $option }}" {{ $selectedAccessibility == $option ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('accessibility')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6 mt-3 mb-3 {{ $selectedAccessibility == 1 ? '' : 'd-none' }}">
            <label for="descrricaoAcessibilidade" class="form-label required">Especifique</label>
            <input list="disabilities" class="form-control @error('accessibility_description') is-invalid @enderror"
                id="accessibility_description" name="accessibility_description"
                value="{{ old('accessibility_description', session('step6.accessibility_description')) }}" aria-describedby="msgDisabilities">
            <small id="msgDisabilities" class="form-text text-muted">Escolha um item da lista ou descreva.</small>

            <datalist id="disabilities">
                @foreach ($disabilities as $key => $label)
                    <option value="{{ $label }}">
                @endforeach
            </datalist>

            @error('accessibility_description')
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
                <option value="" selected>...</option>
                @php
                    $selectedProgram = old('social_program', session('step6.social_program'));
                @endphp
                @foreach ($options as $option => $value)
                    <option value="{{ $option }}" {{ $selectedProgram == $option ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('social_program')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6 mt-3 mb-3 {{ $selectedProgram == 1 ? '' : 'd-none' }}">
            <label for="inscricaoSocial" class="form-label required">
                Se SIM, informe o seu NIS – Número de Identificação Social
            </label>
            <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis"
                value="{{ old('nis', session('step6.nis')) }}">
            @error('nis')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-center border-top pt-3">
            <button type="button" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i>
                <a href="{{ route('step.family') }}" class="text-decoration-none">Voltar</a>
            </button>
            <button type="submit" class="btn btn-sm btn-primary ms-2">Avançar <i
                    class="bi bi-arrow-right-circle ms-2"></i></button>
        </div>
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('assets/cleave/masks.js') }}"></script>
    <script src="{{ asset('assets/js/others.js') }}"></script>
    <script src="{{ asset('assets/rules/others.js') }}"></script>
@endpush
