@extends('layouts.forms.master')

@section('page-title', 'Inscrição | Filiação')

@section('forms')

    <form id="inscription" class="row g-4" action="{{ route('step.family') }}" method="POST">
        @csrf
        
        <h5 class="fw-semibold border-bottom pb-1">Filiação</h5>
        
        <div class="form-group col-sm-8">
            <label for="mae" class="form-label required">Nome Completo da Mãe</label>
            <input type="text" class="form-control @error('mother') is-invalid @enderror" id="mother" name="mother"
                value="{{ old('mother', session('step5.mother')) }}">
            @error('mother')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div class="form-group col-sm-4">
            <label for="telefoneDaMae" class="form-label">Telefone</label>
            <input type="text" class="form-control phone-mask @error('mother_phone') is-invalid @enderror"
                id="mother_phone" name="mother_phone" value="{{ old('mother_phone', session('step5.mother_phone')) }}">
            @error('mother_phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div class="form-group col-sm-8">
            <label for="pai" class="form-label">Nome Completo do Pai</label>
            <input type="text" class="form-control @error('father') is-invalid @enderror" id="father" name="father"
                value="{{ old('father', session('step5.father')) }}">
            @error('father')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div class="form-group col-sm-4">
            <label for="telefoneDoPai" class="form-label">Telefone</label>
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
                <input class="form-check-input" type="radio" name="respLegalOption" id="respOption1" value="1"
                    {{ old('respLegalOption', session('step5.respLegalOption')) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="respLegalOption">Sim</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input @error('respLegalOption') is-invalid @enderror" type="radio"
                    name="respLegalOption" id="respOption2" value="2"
                    {{ old('respLegalOption', session('step5.respLegalOption')) != 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="respLegalOption">Não</label>
            </div>
            @error('respLegalOption')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group col-sm-8 respLegal d-none">
            <label for="responsavelLegal" class="form-label required">Nome Completo do Responsável Legal</label>
            <input type="text" class="form-control @error('responsible') is-invalid @enderror" id="responsible"
                name="responsible" value="{{ old('responsible', session('step5.responsible')) }}">
            @error('responsible')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group col-sm-4 respLegal d-none">
            <label for="grauDeParentesco" class="form-label required">Grau de Parentesco</label>
            <select name="degree" id="degree" class="form-select @error('degree') is-invalid @enderror">
                <option value="" selected>...</option>
                @foreach ($degrees as $degree => $value)
                    <option value="{{ $degree }}"
                        {{ old('degree', session('step5.degree')) == $degree ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('degree')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group respLegal d-none" id="other_relationship"> <!-- Agora com respLegal -->
            <label for="especifique" class="form-label required">Se OUTRO, especifique</label>
            <input type="text" class="form-control @error('kinship') is-invalid @enderror" id="kinship" name="kinship"
                value="{{ old('kinship', session('step5.kinship')) }}">
            @error('kinship')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group col-sm-4 respLegal d-none"> <!-- Adicionei respLegal e d-none -->
            <label for="telefoneDoResponsavel" class="form-label required">Telefone do Responsável</label>
            <input type="text" class="form-control phone-mask @error('responsible_phone') is-invalid @enderror"
                id="responsible_phone" name="responsible_phone"
                value="{{ old('responsible_phone', session('step5.responsible_phone')) }}">
            @error('responsible_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="border-top pt-3"></div>
        
        <div class="form-group col-sm-6">
            <label for="emailDosResponsaveis" class="form-label required">E-mail (pais ou responsável)</label>
            <input type="email" class="form-control @error('parents_email') is-invalid @enderror" id="parents_email"
                name="parents_email" value="{{ old('parents_email', session('step5.parents_email')) }}">
            @error('parents_email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group col-sm-6">
            <label for="confirmeEmail" class="form-label required">Confirme o e-mail</label>
            <input type="email" class="form-control @error('parents_email_confirmation') is-invalid @enderror"
                id="parents_email_confirmation" name="parents_email_confirmation"
                value="{{ old('parents_email_confirmation', session('step5.parents_email_confirmation')) }}">
            @error('parents_email_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-12">
            <button type="button" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i>
                <a href="{{ route('step.academic') }}" class="text-decoration-none">Voltar</a>
            </button>
            <button type="submit" class="btn btn-sm btn-primary ms-2">Avançar <i
                    class="bi bi-arrow-right-circle ms-2"></i>
            </button>
        </div>
    </form>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/family.js') }}"></script>
<script src="{{ asset('assets/cleave/masks.js') }}"></script>
<script src="{{ asset('assets/rules/user/family.js') }}"></script>
@endpush