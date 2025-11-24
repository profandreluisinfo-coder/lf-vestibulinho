@extends('layouts.home.master')

@push('metas')
  @if (app()->environment('local'))
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
  @endif
  <meta name="description"
    content="Confirmação de e-mail para candidatos do {{ config('app.name') }} {{ $calendar->year }}">
@endpush

@section('title', config('app.name') . ' ' . $calendar->year . ' | Registro Confirmado')

@section('body-class', 'bg-light')

@section('content')

  @include('partials.videos.back-login')

  <main class="auth mt-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
          <article class="card shadow-sm">
            <header class="card-header d-flex flex-column justify-content-center align-items-center border-0 pt-4">
              <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;" aria-hidden="true"></i>
              <h2 class="h3 text-center">{{ config('app.name') }} {{ $calendar->year }}</h2>
            </header>
            <div class="card-body text-center">
              <h1 class="h4 mb-4 text-center">
                <span class="d-inline-flex align-items-center justify-content-center title">
                  <i class="bi bi-envelope-check fs-3 me-2" aria-hidden="true"></i>
                  Verificação de E-mail
                </span>
              </h1>

              <div class="alert alert-success" role="alert">
                <p class="mb-2"><strong>Prezado(a) Candidato(a):</strong></p>
                <p class="mb-0">
                  O endereço <strong class="text-primary">{{ session()->get('email') }}</strong>
                  foi validado com <strong class="text-success">sucesso</strong>!
                </p>
              </div>

              <p class="mb-3">
                <strong>Próximo passo</strong>: acesse a <strong>Área do Candidato</strong> com o e-mail
                e senha cadastrados para preencher o <strong>formulário de inscrição</strong>.
              </p>

              <div class="d-grid">
                <a href="{{ route('login') }}" class="btn btn-primary">
                  <i class="bi bi-person-fill-lock me-2" aria-hidden="true"></i>
                  Acessar Área do Candidato
                </a>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </main>

  <footer class="mini-footer mt-auto">
    @include('home.mini-footer')
  </footer>

@endsection
