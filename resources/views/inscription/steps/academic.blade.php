@extends('layouts.forms')

@section('page-title', 'Inscrição - Dados Escolares')

@section('content')

    <form id="inscription" class="row g-4" action="{{ route('inscription.step.academic') }}" method="POST">
        @csrf

        <h5 class="fw-semibold border-bottom pb-1">Dados Escolares</h5>

        <div class="form-group col-md-12">
            <label for="escola" class="form-label required">Escola onde concluiu (concluirá) o Ensino
                Fundamental</label>
            <input list="schools" name="school_name" class="form-control @error('school_name') is-invalid @enderror"
                id="school_name" value="{{ old('school_name', session('step4.school_name')) }}"
                aria-describedby="schoolHelp">
            <small id="schoolHelp" class="text-muted fst-italic"><i class="bi bi-info-circle"></i> Escolha um item da lista, ou, digite o nome da
                sua escola.</small>

            <datalist id="schools">
                @foreach ($schools as $school)
                    <option value="{{ $school->name }}">
                @endforeach
            </datalist>

            @error('school_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="registroAcademico" class="form-label required">Registro Acadêmico - RA </label>
            <input type="text" name="school_ra" id="school_ra"
                class="form-control @error('school_ra') is-invalid @enderror"
                value="{{ old('school_ra', session('step4.school_ra')) }}">
            @error('school_ra')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="school_year" class="form-label required">Ano em que concluiu (concluirá) o Ensino Fundamental</label>
            <input type="number" name="school_year" id="school_year"
                class="form-control @error('school_year') is-invalid @enderror"
                value="{{ old('school_year', session('step4.school_year')) }}" min="2025">
            @error('school_year')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="cidade" class="form-label required">Cidade</label>
            <input type="text" name="school_city" class="form-control @error('school_city') is-invalid @enderror"
                id="school_city" value="{{ old('school_city', session('step4.school_city')) }}">
            @error('school_city')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="estado" class="form-label required">Estado</label>
            <select class="form-select form-select-md @error('school_state') is-invalid @enderror" name="school_state"
                id="school_state">
                <option value="" selected>...</option>
                <option value="AC" {{ session('step4.school_state') == 'AC' ? 'selected' : '' }}>Acre</option>
                <option value="AL" {{ session('step4.school_state') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                <option value="AP" {{ session('step4.school_state') == 'AP' ? 'selected' : '' }}>Amapá</option>
                <option value="AM" {{ session('step4.school_state') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                <option value="BA" {{ session('step4.school_state') == 'BA' ? 'selected' : '' }}>Bahia</option>
                <option value="CE" {{ session('step4.school_state') == 'CE' ? 'selected' : '' }}>Ceará</option>
                <option value="DF" {{ session('step4.school_state') == 'DF' ? 'selected' : '' }}>Distrito Federal
                </option>
                <option value="ES" {{ session('step4.school_state') == 'ES' ? 'selected' : '' }}>Espírito Santo
                </option>
                <option value="GO" {{ session('step4.school_state') == 'GO' ? 'selected' : '' }}>Goiás</option>
                <option value="MA" {{ session('step4.school_state') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                <option value="MT" {{ session('step4.school_state') == 'MT' ? 'selected' : '' }}>Mato Grosso
                </option>
                <option value="MS" {{ session('step4.school_state') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul
                </option>
                <option value="MG" {{ session('step4.school_state') == 'MG' ? 'selected' : '' }}>Minas Gerais
                </option>
                <option value="PA" {{ session('step4.school_state') == 'PA' ? 'selected' : '' }}>Pará</option>
                <option value="PB" {{ session('step4.school_state') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                <option value="PR" {{ session('step4.school_state') == 'PR' ? 'selected' : '' }}>Paraná</option>
                <option value="PE" {{ session('step4.school_state') == 'PE' ? 'selected' : '' }}>Pernambuco
                </option>
                <option value="PI" {{ session('step4.school_state') == 'PI' ? 'selected' : '' }}>Piauí</option>
                <option value="RJ" {{ session('step4.school_state') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro
                </option>
                <option value="RN" {{ session('step4.school_state') == 'RN' ? 'selected' : '' }}>Rio Grande do
                    Norte
                </option>
                <option value="RS" {{ session('step4.school_state') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul
                </option>
                <option value="RO" {{ session('step4.school_state') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                <option value="RR" {{ session('step4.school_state') == 'RR' ? 'selected' : '' }}>Roraima</option>
                <option value="SC" {{ session('step4.school_state') == 'SC' ? 'selected' : '' }}>Santa Catarina
                </option>
                <option value="SP" {{ session('step4.school_state') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                <option value="SE" {{ session('step4.school_state') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                <option value="TO" {{ session('step4.school_state') == 'TO' ? 'selected' : '' }}>Tocantins</option>
            </select>
            @error('school_state')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-12 border-top pt-3">
            <button type="button" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i>
                <a href="{{ route('inscription.step.address') }}" class="text-decoration-none">Voltar</a>
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
    <script src="{{ asset('assets/js/inscription/rules/academic.js') }}"></script>
@endpush