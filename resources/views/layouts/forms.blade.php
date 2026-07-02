<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('metas')

    <title>@yield('page-title', 'Vestibulinho LF')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/css/layouts/forms/styles.css') }}">

    @stack('styles')

    @stack('head-scripts')
</head>

<body>

    <header class="forms-topbar">
        <a href="{{ route('inscription.start') }}" class="forms-brand">
            <span class="forms-brand-text"><i class="bi bi-mortarboard fs-5 me-1"></i> Vestibulinho LF <span class="forms-brand-year">2027</span></span>
        </a>

        <a href="{{ route('inscription.start') }}" class="forms-back-link">
            <i class="bi bi-arrow-left"></i> Voltar ao início
        </a>
    </header>

    <div class="wrapper">

        @include('shared.toasts')

        @include('inscription.partials.stepper')

        @yield('content')

    </div>

    @include('partials.forms.change-password')

    <footer class="forms-footer">
        &copy; {{ $year }} ala
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>

    @stack('plugins') {{-- plugins  específicos --}}

    <script src="{{ asset('assets/js/shared/toasts.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/shared/change-password.js') }}"></script>
    <script src="{{ asset('assets/js/shared/popovers.js') }}"></script>
    @stack('scripts') {{-- scripts  específicos --}}
</body>

</html>
