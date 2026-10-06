@extends('layouts.forms')

@section('page-title', 'Filiação')

@section('content')

    @php
        // Valor atual da opção de responsável legal (old tem prioridade sobre a sessão)
        $respLegal = old('respLegalOption', session('step5.respLegalOption'));
    @endphp

    {{-- ATENÇÃO: confira se esta rota é a de POST (familyStore) --}}
    <form id="inscription" class="row g-4 inscription-form" action="{{ route('inscription.step.family') }}" method="POST"
        novalidate>
        @csrf

        <h5 class="fw-semibold border-bottom pb-1">Filiação</h5>

        <div class="form-group col-sm-8">
            <label for="mother" class="form-label">Nome Completo da Mãe</label>
            <input type="text" class="form-control @error('mother') is-invalid @enderror" id="mother" name="mother"
                value="{{ old('mother', session('step5.mother')) }}">
            @error('mother')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-sm-4">
            <label for="mother_phone" class="form-label">Telefone</label>
            <input type="text" class="form-control phone-mask @error('mother_phone') is-invalid @enderror"
                id="mother_phone" name="mother_phone" value="{{ old('mother_phone', session('step5.mother_phone')) }}">
            @error('mother_phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-sm-8">
            <label for="father" class="form-label">Nome Completo do Pai</label>
            <input type="text" class="form-control @error('father') is-invalid @enderror" id="father" name="father"
                value="{{ old('father', session('step5.father')) }}">
            @error('father')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-sm-4">
            <label for="father_phone" class="form-label">Telefone</label>
            <input type="text" class="form-control phone-mask @error('father_phone') is-invalid @enderror"
                id="father_phone" name="father_phone" value="{{ old('father_phone', session('step5.father_phone')) }}">
            @error('father_phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <h6 class="fw-semibold border-bottom pb-1">Responsável Legal</h6>

        <div class="form-group">
            <p><span class="fw-semibold required">Deseja informar um responsável legal, curador ou tutor?</span></p>
            <div class="form-check form-check-inline">
                <input class="form-check-input @error('respLegalOption') is-invalid @enderror" type="radio"
                    name="respLegalOption" id="respOption1" value="1" {{ $respLegal == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="respOption1">Sim</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input @error('respLegalOption') is-invalid @enderror" type="radio"
                    name="respLegalOption" id="respOption2" value="2" {{ $respLegal != 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="respOption2">Não</label>
            </div>
            {{-- d-block: o feedback não é "irmão" do input dentro do form-check, então precisa ser forçado --}}
            @error('respLegalOption')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-sm-8 respLegal d-none">
            <label for="responsible" class="form-label required">Nome Completo do Responsável Legal</label>
            <input type="text" class="form-control @error('responsible') is-invalid @enderror" id="responsible"
                name="responsible" value="{{ old('responsible', session('step5.responsible')) }}">
            @error('responsible')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-sm-4 respLegal d-none">
            <label for="degree_id" class="form-label required">Grau de Parentesco</label>
            <select name="degree_id" id="degree_id" class="form-select @error('degree_id') is-invalid @enderror">
                <option value="">...</option>
                @foreach ($degrees as $degree)
                    <option value="{{ $degree->id }}"
                        {{ old('degree_id', session('step5.degree_id')) == $degree->id ? 'selected' : '' }}>
                        {{ $degree->description }}
                    </option>
                @endforeach
            </select>
            @error('degree_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group respLegal d-none" id="other_relationship">
            <label for="kinship" class="form-label required">Se OUTRO, especifique</label>
            <input type="text" class="form-control @error('kinship') is-invalid @enderror" id="kinship" name="kinship"
                value="{{ old('kinship', session('step5.kinship')) }}">
            @error('kinship')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-sm-4 respLegal d-none">
            <label for="responsible_phone" class="form-label required">Telefone do Responsável</label>
            <input type="text" class="form-control phone-mask @error('responsible_phone') is-invalid @enderror"
                id="responsible_phone" name="responsible_phone"
                value="{{ old('responsible_phone', session('step5.responsible_phone')) }}">
            @error('responsible_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="border-top pt-3"></div>

        <div class="form-group col-sm-6">
            <label for="parents_email" class="form-label required">E-mail (pais ou responsável)</label>
            <input type="email" class="form-control @error('parents_email') is-invalid @enderror" id="parents_email"
                name="parents_email" value="{{ old('parents_email', session('step5.parents_email')) }}">
            @error('parents_email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-sm-6">
            <label for="parents_email_confirmation" class="form-label required">Confirme o e-mail</label>
            <input type="email" class="form-control @error('parents_email_confirmation') is-invalid @enderror"
                id="parents_email_confirmation" name="parents_email_confirmation"
                value="{{ old('parents_email_confirmation', session('step5.parents_email_confirmation')) }}">
            @error('parents_email_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 border-top pt-3">
            <a href="{{ route('inscription.step.academic') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i> Voltar
            </a>
            <button type="submit" class="btn btn-primary btn-sm w-auto">
                <span class="btn-text">
                    <i class="bi bi-arrow-right-circle me-2"></i> Avançar
                </span>
                <span class="btn-spinner d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Processando...
                </span>
            </button>
        </div>
    </form>

@endsection

@push('plugins')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
@endpush

{{-- Ordem importa: primeiro a UI (mostrar/ocultar/limpar), depois máscaras e, por fim, as regras --}}
@push('scripts')
    <script src="{{ asset('assets/js/ui/inscription/family.js') }}"></script>
    <script src="{{ asset('assets/js/cleave/masks.js') }}"></script>
    <script src="{{ asset('assets/js/rules/inscription/family.js') }}"></script>
@endpush