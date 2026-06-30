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

    <link rel="stylesheet" href="{{ asset('assets/css/layouts/inscription/styles.css') }}">

    @stack('styles')

    @stack('head-scripts')
</head>
</head>

<body>   

    <div class="mx-auto text-center">
        <a href="{{ route('inscription.start') }}" class="icon-link icon-link-hover text-primary link-underline-primary link-offset-3">
            <i class="bi bi-arrow-left"></i> Voltar para o início
        </a>
    </div>

    @include('shared.toasts')

    {{-- CONTEÚDO PRINCIPAL --}}
    @yield('content')

    @include('partials.forms.change-password')

    <div class="container small text-muted mt-3 py-2">
        &copy; {{ $year }} ala
    </div>

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
