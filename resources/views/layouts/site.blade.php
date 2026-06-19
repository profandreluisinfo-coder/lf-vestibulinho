<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ── Título da página ──────────────────────────────────────
         Nas views filhas: @section('title', 'Meu Título')
         O sufixo com o nome da escola é acrescentado automaticamente.
    ──────────────────────────────────────────────────────────── --}}
    <title>@yield('title', 'Vestibulinho LF ' . config('app.year')) — EM Dr. Leandro Franceschini</title>

    {{-- ── CSS base (sempre carregados) ─────────────────────────── --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/css/site/app.css') }}" />

    {{-- ── CSS específico da página ──────────────────────────────
        Definido nas views filhas:
    ──────────────────────────────────────────────────────────── --}}
    @stack('styles')
</head>

<body>

    {{-- ── Navbar ──────────────────────────────────────────────── --}}
    @include('partials.site.navbar')

    {{-- ── Alertas / Toasts (disponíveis em todas as páginas) ──── --}}
    @include('alerts.toasts')

    {{-- ── Conteúdo principal ───────────────────────────────────
         Nas views filhas: @section('content') … @endsection
    ──────────────────────────────────────────────────────────── --}}
    @yield('content')

    {{-- ── Footer ──────────────────────────────────────────────── --}}
    @include('partials.site.footer')

    {{-- ── JS base (sempre carregados) ──────────────────────────── --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/guest/home/app.js') }}"></script>
    
    {{-- ── JS específico da página ──────────────────────────────
         Nas views filhas:
         @push('scripts')
             <script src="{{ asset('assets/js/guest/home/index.js') }}"></script>
         @endpush
    ──────────────────────────────────────────────────────────── --}}
    @stack('scripts')

</body>

</html>