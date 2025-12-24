@extends('layouts.home.master')

@push('metas')
  @if (app()->environment('local'))
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
  @endif
  <meta name="description"
    content="Verificação de e-mail para candidatos do {{ config('app.name') }} {{ $calendar->year }}">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Verificação de E-mail')

@section('body-class', 'bg-light')

@section('content')

  @include('partials.videos.back-login')

  <main class="auth mt-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <article class="card shadow-sm">
            <header class="card-header d-flex flex-column justify-content-center align-items-center border-0 pt-4">
              <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;" aria-hidden="true"></i>
              <h2 class="h3 text-center">{{ config('app.name') }} {{ $calendar->year }}</h2>
            </header>

            <div class="card-body">
              <h1 class="h4 mb-4 text-center">
                <span class="d-inline-flex align-items-center title">
                  <i class="bi bi-envelope-check fs-3 me-2" aria-hidden="true"></i>
                  Verificação de E-mail
                </span>
              </h1>

              <div class="alert alert-success" role="alert">
                <p class="mb-2"><strong>Prezado(a) Candidato(a):</strong></p>
                <p class="mb-0 text-justify">
                  Um <em>link</em> de verificação foi enviado para o endereço de e-mail informado no
                  momento do cadastro. Solicitamos que verifique sua caixa de entrada, incluindo a pasta
                  de <em>spam</em>, e siga as instruções contidas na mensagem para confirmar seu endereço de e-mail. O
                  acesso à
                  <strong>Área do Candidato</strong> será liberado somente após a conclusão dessa confirmação.
                </p>
              </div>

              <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="btn btn-primary">
                  <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>
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
    @include('guest.home.mini-footer')
  </footer>

@endsection
