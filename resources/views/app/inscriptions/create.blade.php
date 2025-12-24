@extends('layouts.user.master')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Formulário de Inscrição')

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/dashboard/user/inscription.css') }}"> --}}
@endpush

@section('dash-content')

    @if ($calendar?->isInscriptionOpen())
        
    <div class="container">
        <h4 class="mb-4">Formulário de Inscrição</h4>
        
        <form class="row g-3" method="POST" action="">
            @csrf
            
            <!-- Dados Pessoais -->
            <h4 class="col-12 mt-4 mb-3 text-primary">Dados Pessoais</h4>
            
            <div class="col-md-6">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control @error('cpf') is-invalid @enderror" value="{{ old('cpf') }}" maxlength="11" placeholder="Somente números">
                @error('cpf')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="birth" class="form-label">Data de Nascimento</label>
                <input type="date" name="birth" id="birth" class="form-control @error('birth') is-invalid @enderror" value="{{ old('birth') }}">
                @error('birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="name" class="form-label">Nome Completo</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" maxlength="100">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="social_name" class="form-label">Nome Social</label>
                <input type="text" name="social_name" id="social_name" class="form-control @error('social_name') is-invalid @enderror" value="{{ old('social_name') }}" maxlength="100">
                @error('social_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="gender" class="form-label">Gênero</label>
                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Masculino</option>
                    <option value="2" {{ old('gender') == '2' ? 'selected' : '' }}>Feminino</option>
                    <option value="3" {{ old('gender') == '3' ? 'selected' : '' }}>Outro</option>
                    <option value="4" {{ old('gender') == '4' ? 'selected' : '' }}>Prefiro não informar</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="pne" class="form-label">Pessoa com Necessidades Especiais (PNE)</label>
                <select name="pne" id="pne" class="form-select @error('pne') is-invalid @enderror">
                    <option value="0" {{ old('pne') == '0' ? 'selected' : '' }}>Não</option>
                    <option value="1" {{ old('pne') == '1' ? 'selected' : '' }}>Sim</option>
                </select>
                @error('pne')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="phone" class="form-label">Telefone</label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" maxlength="11" placeholder="Somente números">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Documentação -->
            <h4 class="col-12 mt-4 mb-3 text-primary">Documentação</h4>
            
            <div class="col-md-4">
                <label for="nationality" class="form-label">Nacionalidade</label>
                <select name="nationality" id="nationality" class="form-select @error('nationality') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    <option value="1" {{ old('nationality') == '1' ? 'selected' : '' }}>Brasileira</option>
                    <option value="2" {{ old('nationality') == '2' ? 'selected' : '' }}>Estrangeira</option>
                </select>
                @error('nationality')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="doc_type" class="form-label">Tipo de Documento</label>
                <select name="doc_type" id="doc_type" class="form-select @error('doc_type') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    <option value="1" {{ old('doc_type') == '1' ? 'selected' : '' }}>RG</option>
                    <option value="2" {{ old('doc_type') == '2' ? 'selected' : '' }}>RNE</option>
                </select>
                @error('doc_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="doc_number" class="form-label">Número do Documento</label>
                <input type="text" name="doc_number" id="doc_number" class="form-control @error('doc_number') is-invalid @enderror" value="{{ old('doc_number') }}" maxlength="11">
                @error('doc_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            {{-- <style>
                .new-model, .old-model {
                    display: none;
                }
            </style> --}}
            <!-- Certidão de Nascimento - Escolher o Modelo -->
            <h5 class="col-12 mt-3 mb-2">Certidão de Nascimento (Escolher o Modelo)</h5>
            
            <div class="col-md-12">
                <label for="birth_certificate" class="form-label">Modelo da Certidão</label>
                <select name="birth_certificate" id="birth_certificate" class="form-select @error('birth_certificate') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    <option value="1" {{ old('birth_certificate') == '1' ? 'selected' : '' }}>Modelo Novo</option>
                    <option value="2" {{ old('birth_certificate') == '2' ? 'selected' : '' }}>Modelo Antigo</option>
                </select>
                @error('birth_certificate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Certidão de Nascimento - Modelo Novo -->
            <h5 class="col-12 mt-3 mb-2 new-model">Certidão de Nascimento (Modelo Novo)</h5>
            
            <div class="col-md-12 new-model">
                <label for="new_number" class="form-label">Número da Matrícula (32 dígitos)</label>
                <input type="text" name="new_number" id="new_number" class="form-control @error('new_number') is-invalid @enderror" value="{{ old('new_number') }}" maxlength="32">
                @error('new_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Certidão de Nascimento - Modelo Antigo -->
            <h5 class="col-12 mt-3 mb-2 old-model">Certidão de Nascimento (Modelo Antigo)</h5>
            
            <div class="col-md-3 old-model">
                <label for="fls" class="form-label">Folha</label>
                <input type="text" name="fls" id="fls" class="form-control @error('fls') is-invalid @enderror" value="{{ old('fls') }}" maxlength="4">
                @error('fls')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-3 old-model">
                <label for="book" class="form-label">Livro</label>
                <input type="text" name="book" id="book" class="form-control @error('book') is-invalid @enderror" value="{{ old('book') }}" maxlength="10">
                @error('book')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-3 old-model">
                <label for="old_number" class="form-label">Número</label>
                <input type="text" name="old_number" id="old_number" class="form-control @error('old_number') is-invalid @enderror" value="{{ old('old_number') }}" maxlength="6">
                @error('old_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-3 old-model">
                <label for="municipality" class="form-label">Município</label>
                <input type="text" name="municipality" id="municipality" class="form-control @error('municipality') is-invalid @enderror" value="{{ old('municipality') }}" maxlength="45">
                @error('municipality')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Endereço -->
            <h4 class="col-12 mt-4 mb-3 text-primary">Endereço</h4>
            
            <div class="col-md-3">
                <label for="zip" class="form-label">CEP</label>
                <input type="text" name="zip" id="zip" class="form-control @error('zip') is-invalid @enderror" value="{{ old('zip') }}" maxlength="10" placeholder="Somente números">
                @error('zip')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-7">
                <label for="street" class="form-label">Logradouro</label>
                <input type="text" name="street" id="street" class="form-control @error('street') is-invalid @enderror" value="{{ old('street') }}" maxlength="60">
                @error('street')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-2">
                <label for="number" class="form-label">Número</label>
                <input type="text" name="number" id="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" maxlength="10">
                @error('number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="complement" class="form-label">Complemento</label>
                <input type="text" name="complement" id="complement" class="form-control @error('complement') is-invalid @enderror" value="{{ old('complement') }}" maxlength="20">
                @error('complement')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="burgh" class="form-label">Bairro</label>
                <input type="text" name="burgh" id="burgh" class="form-control @error('burgh') is-invalid @enderror" value="{{ old('burgh') }}" maxlength="60">
                @error('burgh')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="city" class="form-label">Cidade</label>
                <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" maxlength="30">
                @error('city')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="state" class="form-label">Estado</label>
                <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}" maxlength="32">
                @error('state')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Dados Escolares -->
            <h4 class="col-12 mt-4 mb-3 text-primary">Escola de Ensino Fundamental</h4>
            
            <div class="col-md-6">
                <label for="school_name" class="form-label">Nome da Escola</label>
                <input type="text" name="school_name" id="school_name" class="form-control @error('school_name') is-invalid @enderror" value="{{ old('school_name') }}" maxlength="150">
                @error('school_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-3">
                <label for="school_city" class="form-label">Cidade</label>
                <input type="text" name="school_city" id="school_city" class="form-control @error('school_city') is-invalid @enderror" value="{{ old('school_city') }}" maxlength="30">
                @error('school_city')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-3">
                <label for="school_state" class="form-label">Estado</label>
                <input type="text" name="school_state" id="school_state" class="form-control @error('school_state') is-invalid @enderror" value="{{ old('school_state') }}" maxlength="32">
                @error('school_state')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-3">
                <label for="school_year" class="form-label">Ano de Conclusão</label>
                <input type="text" name="school_year" id="school_year" class="form-control @error('school_year') is-invalid @enderror" value="{{ old('school_year') }}" maxlength="4" placeholder="Ex: 2024">
                @error('school_year')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-3">
                <label for="school_ra" class="form-label">RA (Registro do Aluno)</label>
                <input type="text" name="school_ra" id="school_ra" class="form-control @error('school_ra') is-invalid @enderror" value="{{ old('school_ra') }}" maxlength="20">
                @error('school_ra')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Filiação / Responsável Legal -->
            <h4 class="col-12 mt-4 mb-3 text-primary">Filiação / Responsável Legal</h4>
            
            <div class="col-md-6">
                <label for="mother" class="form-label">Nome da Mãe</label>
                <input type="text" name="mother" id="mother" class="form-control @error('mother') is-invalid @enderror" value="{{ old('mother') }}" maxlength="60">
                @error('mother')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="mother_phone" class="form-label">Telefone da Mãe</label>
                <input type="text" name="mother_phone" id="mother_phone" class="form-control @error('mother_phone') is-invalid @enderror" value="{{ old('mother_phone') }}" maxlength="11" placeholder="Somente números">
                @error('mother_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="father" class="form-label">Nome do Pai</label>
                <input type="text" name="father" id="father" class="form-control @error('father') is-invalid @enderror" value="{{ old('father') }}" maxlength="60">
                @error('father')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="father_phone" class="form-label">Telefone do Pai</label>
                <input type="text" name="father_phone" id="father_phone" class="form-control @error('father_phone') is-invalid @enderror" value="{{ old('father_phone') }}" maxlength="11" placeholder="Somente números">
                @error('father_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="responsible" class="form-label">Responsável Legal</label>
                <input type="text" name="responsible" id="responsible" class="form-control @error('responsible') is-invalid @enderror" value="{{ old('responsible') }}" maxlength="60">
                @error('responsible')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="responsible_phone" class="form-label">Telefone do Responsável</label>
                <input type="text" name="responsible_phone" id="responsible_phone" class="form-control @error('responsible_phone') is-invalid @enderror" value="{{ old('responsible_phone') }}" maxlength="11" placeholder="Somente números">
                @error('responsible_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="degree" class="form-label">Grau de Parentesco</label>
                <select name="degree" id="degree" class="form-select @error('degree') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    <option value="1" {{ old('degree') == '1' ? 'selected' : '' }}>Avô/Avó</option>
                    <option value="2" {{ old('degree') == '2' ? 'selected' : '' }}>Tio/Tia</option>
                    <option value="3" {{ old('degree') == '3' ? 'selected' : '' }}>Irmão/Irmã</option>
                    <option value="4" {{ old('degree') == '4' ? 'selected' : '' }}>Outro</option>
                </select>
                @error('degree')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="kinship" class="form-label">Especificar Parentesco (se "Outro")</label>
                <input type="text" name="kinship" id="kinship" class="form-control @error('kinship') is-invalid @enderror" value="{{ old('kinship') }}" maxlength="45">
                @error('kinship')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-12">
                <label for="parents_email" class="form-label">E-mail dos Pais/Responsáveis</label>
                <input type="email" name="parents_email" id="parents_email" class="form-control @error('parents_email') is-invalid @enderror" value="{{ old('parents_email') }}" maxlength="60">
                @error('parents_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Informações de Saúde e Sociais -->
            <h4 class="col-12 mt-4 mb-3 text-primary">Informações de Saúde e Sociais</h4>
            
            <div class="col-md-4">
                <label for="health" class="form-label">Alergias / Condições de Saúde</label>
                <input type="text" name="health" id="health" class="form-control @error('health') is-invalid @enderror" value="{{ old('health') }}" maxlength="60">
                @error('health')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="accessibility" class="form-label">Necessidades de Acessibilidade</label>
                <input type="text" name="accessibility" id="accessibility" class="form-control @error('accessibility') is-invalid @enderror" value="{{ old('accessibility') }}" maxlength="60">
                @error('accessibility')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label for="nis" class="form-label">NIS (Número de Identificação Social)</label>
                <input type="text" name="nis" id="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" maxlength="11" placeholder="Somente números">
                @error('nis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Botões -->
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="reset" class="btn btn-secondary">Limpar</button>
            </div>
        </form>
    </div>

    @endif

@endsection

@push('plugins')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/addons/cleave-phone.br.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/rules\user\validation.js') }}"></script>
@endpush