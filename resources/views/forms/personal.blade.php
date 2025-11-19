@extends('layouts.forms.master')

@section('page-title', ' Inscrição | Dados Pessoais' . ' | ' . config('app.name') . ' ' . config('app.year'))

@section('forms')

  <form id="inscription" class="row g-4" action="{{ route('step.personal') }}" method="POST">
    @csrf
    <h5 class="fw-semibold border-bottom pb-1">Dados Pessoais</h5>
    <div class="form-group col-md-3">
      <label for="cpf" class="form-label required">CPF do candidato</label>
      <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf"
        value="{{ old('cpf', session('step1.cpf')) }}">
      @error('cpf')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>
    
    <div class="form-group col-md-9">
      <label for="name" class="form-label required">Nome completo do candidato</label>
      <input name="name" id="name" class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', session('step1.name')) }}">
      @error('name')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-group col-md-3">
      <label for="gender" class="form-label required">Gênero</label>
      <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
        <option value="" selected>...</option>
        @foreach ($genders as $gender => $value)
          <option value="{{ $gender }}" {{ old('gender', session('step1.gender')) == $gender ? 'selected' : '' }}>
            {{ $value }}</option>
        @endforeach
      </select>
      @error('gender')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>
    
    <div class="form-group col-md-12">
      <div class="alert alert-warning">
      <p><span class="fw-semibold required"><i class="bi bi-exclamation-triangle me-2"></i>Deseja ser tratado(a) pelo nome social/afetivo?</span></p>
      <p>
        <strong>Nome social é:</strong> designação pela qual a pessoa travesti ou transexual se identifica e é socialmente
        reconhecida (como garantido pelo <a class="text-decoration-none"
          href="https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2016/decreto/d8727.htm" target="_blank"
          title="DECRETO Nº 8.727, DE 28 DE ABRIL DE 2016" tabindex="-1" data-bs-toggle="popover" data-bs-trigger="hover"
          data-bs-content="Dispõe sobre o uso do nome social e o reconhecimento da identidade de gênero de pessoas travestis e transexuais no âmbito da administração pública federal direta, autárquica e fundacional.">DECRETO
          Nº 8.727, DE 28 DE ABRIL DE 2016</a>).
      </p>
      <p>
        <strong>Nome afetivo é:</strong> aquele que os responsáveis legais pela criança ou adolescente pretendem tornar
        definitivo quando das alterações da respectiva certidão de nascimento. (<a class="text-decoration-none"
          href="https://www.al.sp.gov.br/repositorio/legislacao/lei/2018/lei-16785-03.07.2018.html" target="_blank"
          title="LEI Nº 16.785, DE 03 DE JULHO DE 2018" tabindex="-1" data-bs-toggle="popover" data-bs-trigger="hover"
          data-bs-content="Dispõe sobre o uso do nome afetivo nos cadastros das instituições escolares, de saúde ou de cultura e lazer para crianças e adolescentes que estejam sob guarda da família adotiva, no período anterior à destituição do pátrio poder familiar">LEI
          Nº 16.785, DE 03 DE JULHO DE 2018</a>)
      </p>
      <p>
        O(a) candidato(a) que desejar utilizar nome social/afetivo neste Processo Seletivo poderá fazê-lo, desde que obtenha <u>autorização expressa dos responsáveis legais</u>. <strong>A autorização deverá ser enviada em anexo, por meio de e-mail, para o seguinte endereço eletrônico</strong> <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br">emdrleandrofranceschini@educacaosumare.com.br</a>.
      </p>
      </div>
      
      <div class="form-check form-check-inline">
        <input class="form-check-input @error('socialNameOption') is-invalid @enderror" type="radio"
          name="socialNameOption" id="radio1" value="1"
          {{ old('socialNameOption', session('step1.socialNameOption')) == 1 ? 'checked' : '' }}>
        <label class="form-check-label" for="socialNameOption">
          Sim, quero ser tratado(a) pelo nome social/afetivo          
        </label>
      </div>

      <div class="form-check form-check-inline">
        <input class="form-check-input @error('socialNameOption') is-invalid @enderror" type="radio"
          name="socialNameOption" id="radio2" value="2"
          {{ old('socialNameOption', session('step1.socialNameOption')) != 1 ? 'checked' : '' }}>
        <label class="form-check-label" for="socialNameOption">
          Não, não ser tratado(a) pelo nome social/afetivo
        </label>
      </div>
      
      @error('socialNameOption')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>
    
    <div id="socialName" class="form-group col-md-12 d-none">
      <label for="social_name" class="form-label">Nome social/afetivo do candidato </label>
      <input name="social_name" id="social_name" class="form-control @error('social_name') is-invalid @enderror"
        value="{{ old('social_name', session('step1.social_name')) }}" aria-describedby="socialName">
        <small id="socialName" class="form-text text-muted">Leia atentamente o item 4.10 do <a href="{{ asset('storage/' . $notice->file) }}" title="Clique para abrir o edital" target="_blank">edital</a>.</small>
      @error('social_name')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-group col-md-3">
      <label for="birth" class="form-label required">Nascimento</label>
      <input type="date" class="form-control @error('birth') is-invalid @enderror" id="birth" name="birth"
        value="{{ old('birth', session('step1.birth')) }}">
      @error('birth')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>
    
    <div class="form-group col-md-3">
      <label for="nationality" class="form-label required">Nacionalidade</label>
      <select class="form-select @error('nationality') is-invalid @enderror" id="nationality" name="nationality">
        <option value="" selected tabindex="-1">...</option>
        @foreach ($nationalities as $nationality => $value)
          <option value="{{ $nationality }}"
            {{ old('nationality', session('step1.nationality')) == $nationality ? 'selected' : '' }}>
            {{ $value }}</option>
        @endforeach
      </select>
      @error('nationality')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>
    
    <div class="form-group col-md-3">
      <label for="doc_type" class="form-label required">Tipo de documento</label>
      <select class="form-select @error('doc_type') is-invalid @enderror" id="doc_type" name="doc_type">
        <option value="" selected>...</option>
        @foreach ($documents as $document => $value)
          <option value="{{ $document }}"
            {{ old('doc_type', session('step1.doc_type')) == $document ? 'selected' : '' }}>
            {{ $value }}</option>
        @endforeach
      </select>
      @error('doc_type')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>
    
    <div class="form-group col-md-3">
      <label for="doc_number" class="form-label required">Número do documento</label>
      <input type="text" class="form-control @error('doc_number') is-invalid @enderror" id="doc_number"
        name="doc_number" minlength="7" maxlength="11" value="{{ old('doc_number', session('step1.doc_number')) }}">
      @error('doc_number')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>
    
    <div class="form-group col-md-3">
      <label for="phone" class="form-label required">Telefone do candidato</label>
      <input type="text" class="form-control phone-mask @error('phone') is-invalid @enderror" id="phone"
        name="phone" value="{{ old('phone', session('step1.phone')) }}">
      @error('phone')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="col-12">
    <button type="submit" class="btn btn-primary btn-sm w-auto">Avançar <i class="bi bi-arrow-right-circle ms-2"></i></button>
    </div>
 </form>

@endsection

@push('scripts')
  <script src="{{ asset('assets/cleave/masks.js') }}"></script>
  <script src="{{ asset('assets/interactions/name.js') }}"></script>
  <script src="{{ asset('assets/interactions/popover.js') }}"></script>
  <script src="{{ asset('assets/rules/user.js') }}"></script>
@endpush
