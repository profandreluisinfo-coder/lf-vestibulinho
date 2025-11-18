@extends('layouts.forms.master')

@section('page-title', 'Inscrição | Curso Pretendido')

@section('forms')

  <form id="inscription" action="{{ route('step.course') }}" method="POST">
    @csrf
    <h6 class="fw-semibold border-bottom pb-1">Pesquisa de intenção de curso:</h6>
    <div class="alert alert-warning d-flex flex-column flex-md-row align-items-center" role="alert">
      <i class="bi bi-exclamation-triangle-fill fs-4 me-4"></i>
      <div class="text-justify">
        <strong>Atenção:</strong> A escolha do curso realizada nesta etapa tem caráter indicativo, sendo utilizada apenas
        para fins estatísticos e de planejamento interno. <u>A matrícula dos candidatos aprovados será realizada conforme
          a disponibilidade de vagas, podendo o candidato optar por qualquer curso que ainda possua vagas disponíveis,
        independentemente da opção indicada na inscrição</u>.
      </div>
    </div>
    <div class="form-group col-md-12 mb-4 mt-4">
      <select class="form-select form-select-md @error('course_id') is-invalid @enderror" id="course_id" name="course_id">
        <option value="" selected>...</option>
        @forelse ($courses as $course)
          <option value="{{ $course->id }}"
            {{ old('course_id', session('step7.course_id')) == $course->id ? 'selected' : '' }}>
            {{ $course->description }}
          </option>
        @empty
          <option value="">Nenhum curso disponível</option>
        @endforelse
      </select>
      @error('course_id')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="col-12">
      <button type="button" class="btn btn-sm btn-secondary w-auto">
        <i class="bi bi-arrow-left-circle me-2"></i>
        <a href="{{ route('step.other') }}" class="text-decoration-none">Voltar</a>
      </button>
      <button type="submit" class="btn btn-sm btn-primary ms-2 w-auto">Avançar <i
          class="bi bi-arrow-right-circle ms-2"></i></button>
    </div>
  </form>

@endsection

@push('scripts')
  <script src="{{ asset('assets/rules/course.js') }}"></script>
@endpush