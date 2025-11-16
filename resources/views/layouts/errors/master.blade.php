<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>@yield('page-title', 'Erro')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lottie-web@5.7.4/build/player/lottie.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/lottie.css') }}">
    @stack('styles')
</head>

<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

    <div class="container text-center">
        {{-- Área Lottie --}}
        <div id="lottie" class="mb-4"></div>

        {{-- Título do Erro --}}
        <h1 class="display-4">@yield('error_title', 'Erro')</h1>

        {{-- Mensagem do Erro --}}
        <p class="lead text-muted">@yield('error_message')</p>

        {{-- Link de Voltar --}}
        <a href="{{ route('home') }}" class="btn btn-outline-primary mt-3">
            <i class="bi bi-house-door"></i> Voltar à Página Inicial
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/lottie-web@5.7.4/build/player/lottie.min.js"></script>
    @stack('scripts')
</body>

</html>