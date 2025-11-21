@extends('layouts.forms.master')

@section('page-title', 'Inscrição | Endereço')

@section('forms')

    <form id="inscription" class="row g-4" action="{{ route('step.address') }}" method="POST">
        @csrf

        <h5 class="fw-semibold border-bottom pb-1">Endereço</h5>

        <div class="form-group col-md-4">
            <label for="cep" class="form-label required">CEP</label> <a
                href="https://buscacepinter.correios.com.br/app/endereco/index.php"
                title="Consultar CEP através do Correios" tabindex="-1" target="_blank">Não sei meu CEP</a>
            <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" name="zip"
                value="{{ old('zip', session('step3.zip')) }}">
            @error('zip')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="rua" class="form-label required">Rua</label>
            <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street"
                value="{{ old('street', session('step3.street')) }}">
            @error('street')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-2">
            <label for="numero" class="form-label required">Número</label>
            <input type="text" class="form-control @error('number') is-invalid @enderror" id="number" name="number"
                value="{{ old('number', session('step3.number')) }}">
            @error('number')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="complemento" class="form-label">Complemento (opcional)</label>
            <input type="text" class="form-control @error('complement') is-invalid @enderror" id="complement"
                name="complement" value="{{ old('complement', session('step3.complement')) }}">
            @error('complement')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-8">
            <label for="bairro" class="form-label required">Bairro</label>
            <input type="text" class="form-control @error('burgh') is-invalid @enderror" id="burgh" name="burgh"
                value="{{ old('burgh', session('step3.burgh')) }}">
            @error('burgh')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="city" class="form-label required">Cidade</label>
            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city"
                value="{{ old('city', session('step3.city')) }}">
            @error('city')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="state" class="form-label required">Estado</label>
            <select class="form-select form-select-md @error('state') is-invalid @enderror" id="state" name="state">
                <option value="" selected>...</option>
                <option value="AC" {{ session('step3.state') == 'AC' ? 'selected' : '' }}>Acre</option>
                <option value="AL" {{ session('step3.state') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                <option value="AP" {{ session('step3.state') == 'AP' ? 'selected' : '' }}>Amapá</option>
                <option value="AM" {{ session('step3.state') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                <option value="BA" {{ session('step3.state') == 'BA' ? 'selected' : '' }}>Bahia</option>
                <option value="CE" {{ session('step3.state') == 'CE' ? 'selected' : '' }}>Ceará</option>
                <option value="DF" {{ session('step3.state') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                <option value="ES" {{ session('step3.state') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                <option value="GO" {{ session('step3.state') == 'GO' ? 'selected' : '' }}>Goiás</option>
                <option value="MA" {{ session('step3.state') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                <option value="MT" {{ session('step3.state') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                <option value="MS" {{ session('step3.state') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                <option value="MG" {{ session('step3.state') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                <option value="PA" {{ session('step3.state') == 'PA' ? 'selected' : '' }}>Pará</option>
                <option value="PB" {{ session('step3.state') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                <option value="PR" {{ session('step3.state') == 'PR' ? 'selected' : '' }}>Paraná</option>
                <option value="PE" {{ session('step3.state') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                <option value="PI" {{ session('step3.state') == 'PI' ? 'selected' : '' }}>Piauí</option>
                <option value="RJ" {{ session('step3.state') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                <option value="RN" {{ session('step3.state') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                <option value="RS" {{ session('step3.state') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                <option value="RO" {{ session('step3.state') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                <option value="RR" {{ session('step3.state') == 'RR' ? 'selected' : '' }}>Roraima</option>
                <option value="SC" {{ session('step3.state') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                <option value="SP" {{ session('step3.state') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                <option value="SE" {{ session('step3.state') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                <option value="TO" {{ session('step3.state') == 'TO' ? 'selected' : '' }}>Tocantins</option>
            </select>
            @error('state')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-12">
            <button type="button" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i>
                <a href="{{ route('step.certificate') }}" class="text-decoration-none">Voltar</a>                 
            </button>
            <button type="submit" class="btn btn-sm btn-primary ms-2">Avançar <i
                    class="bi bi-arrow-right-circle ms-2"></i></button>
        </div>
    </form>

@endsection

@push('scripts')
<script src="{{ asset('assets/cleave/masks.js') }}"></script>
<script src="{{ asset('assets/services/cep.js') }}"></script>
<script src="{{ asset('assets/rules/user/address.js') }}"></script>
@endpush