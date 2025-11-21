@extends('layouts.forms.master')

@section('page-title', 'Inscrição | Certidão de Nascimento')

@section('forms')

  <form id="inscription" class="row g-4" action="{{ route('step.certificate') }}" method="POST">
    @csrf
    
    <h5 class="fw-semibold border-bottom pb-1">Certidão de Nascimento</h5>
    
    <div class="form-group">
      <p><span class="fw-semibold required">Selecione o modelo da sua Certidão de Nascimento</span></p>
      <div class="form-check">
        <input class="form-check-input @error('certificateModel') is-invalid @enderror" type="radio"
          name="certificateModel" id="radio1" value="1"
          {{ old('certificateModel', session('step2.certificateModel')) == 1 ? 'checked' : '' }}>
        <label class="form-check-label" for="certificateModel">
          Novo (Matrícula de Registro Civil com 32 dígitos)
        </label>
        <button type="button" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#newCertificateModal">
          Ver Modelo <i class="bi bi-search"></i>
        </button>
      </div>
      <div class="form-check">
        <input class="form-check-input @error('certificateModel') is-invalid @enderror" type="radio"
          name="certificateModel" id="radio2" value="2"
          {{ old('certificateModel', session('step2.certificateModel')) == 2 ? 'checked' : '' }}>
        <label class="form-check-label" for="certificateModel">
          Antigo (Folhas, Livro, Número e Município de Nascimento)
        </label>
        <button type="button" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#oldCertificateModal">
          Ver Modelo <i class="bi bi-search"></i>
        </button>
      </div>
      @error('certificateModel')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-group col-md-12 d-none" id="newCertificateModel">
      <label for="numeroCertidao" class="form-label required">Matrícula</label>
      <input type="text" class="form-control @error('new_number') is-invalid @enderror" id="new_number"
        name="new_number" value="{{ old('new_number', session('step2.new_number')) }}">
      @error('new_number')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-group col-md-4 d-none oldCertificateModel">
      <label for="folhas" class="form-label required">Folhas</label>
      <input type="text" class="form-control @error('fls') is-invalid @enderror" id="fls" name="fls"
        value="{{ old('fls', session('step2.fls')) }}">
      @error('fls')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-group col-md-4 d-none oldCertificateModel">
      <label for="livro" class="form-label required">Livro de Registro</label>
      <input type="text" class="form-control @error('book') is-invalid @enderror" id="book" name="book"
        value="{{ old('book', session('step2.book')) }}">
      @error('book')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-group col-md-4 d-none oldCertificateModel">
      <label for="numeroCertidao" class="form-label required">Termo ou Número da certidão:</label>
      <input type="text" class="form-control @error('old_number') is-invalid @enderror" id="old_number"
        name="old_number" value="{{ old('old_number', session('step2.old_number')) }}">
      @error('old_number')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-group col-md-4 d-none oldCertificateModel">
      <label for="municipio" class="form-label required">Município de Nascimento</label>
      <input type="text" class="form-control @error('municipality') is-invalid @enderror" id="municipality"
        name="municipality" value="{{ old('municipality', session('step2.municipality')) }}">
      @error('municipality')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="col-12">
      <button type="button" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-left-circle"></i>
        <a href="{{ route('step.personal') }}" class="text-decoration-none ms-2">Voltar</a>
      </button>
      <button type="submit" class="btn btn-sm btn-primary ms-2">Avançar <i
          class="bi bi-arrow-right-circle ms-2"></i>
      </button>
    </div>

  </form>

  <div class="modal fade" id="newCertificateModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">Modelo de Certidão de Nascimento (modelo novo)</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <img src="{{ asset('assets/img/certidao-nascimento-nova.webp') }}" class="img-fluid" width="800"
            alt="Certidão de Nascimento Modelo Novo">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="oldCertificateModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">Modelo de Certidão de Nascimento (modelo antigo)</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <img src="{{ asset('assets/img/certidao-nascimento-antiga.webp') }}" class="img-fluid" width="800"
            alt="Certidão de Nascimento Modelo Antigo">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script src="{{ asset('assets/interactions/certificates.js') }}"></script>
  <script src="{{ asset('assets/cleave/masks.js') }}"></script>
  <script src="{{ asset('assets/rules/user/certificate.js') }}"></script>
@endpush
