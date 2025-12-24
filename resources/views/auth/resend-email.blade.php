@extends('layouts.home.master')

@push('metas')
    @if (app()->environment('local'))
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    @endif
    <meta name="description"
        content="Reenvio de e-mail de verificação para candidatos do {{ config('app.name') }} {{ $calendar->year }}">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Reenviar E-mail de Verificação')

@section('content')

    @include('partials.videos.back-login')

    <main class="auth mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <article class="card shadow-sm">
                        <header
                            class="card-header d-flex flex-column justify-content-center align-items-center border-0 pt-4">
                            <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;" aria-hidden="true"></i>
                            <h2 class="h3 text-center">{{ config('app.name') }} {{ $calendar->year }}</h2>
                        </header>
                        <div class="card-body">
                            <h1 class="h4 mb-4 text-center">
                                <span class="d-inline-flex align-items-center title">
                                    <i class="bi bi-envelope-check me-2" aria-hidden="true"></i>
                                    Reenviar E-mail de Verificação
                                </span>
                            </h1>

                            @include('components.alerts')

                            <form id="resend-email" method="POST" action="{{ route('resend.email') }}" autocomplete="off">
                                @csrf
                                <div class="mb-3">
                                    <label for="myEmail" class="form-label">Informe seu endereço de e-mail</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror" id="myEmail"
                                        value="{{ old('email') }}" placeholder="exemplo@email.com" required
                                        autocomplete="email" aria-describedby="@error('email') emailError @enderror">
                                    @error('email')
                                        <div id="emailError" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-envelope-check me-2" aria-hidden="true"></i>
                                        Reenviar E-mail de Verificação
                                    </button>
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="{{ route('login') }}" class="text-decoration-none">Já validei meu e-mail</a>
                                </div>
                            </form>
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

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/auth/resend-email.js') }}"></script>
@endpush
