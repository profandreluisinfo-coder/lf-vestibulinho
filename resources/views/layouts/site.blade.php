<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'EM Dr Leandro Franceschini - Sumaré - SP')">
    <title>@yield('title', 'EM Dr Leandro Franceschini - Sumaré - SP')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/site/pages/index.css') }}" />

    @stack('styles')
</head>
<body>
    @include('partials.site.navbar')
    
    <main>
        @yield('content')
    </main>
    
    @include('partials.site.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/site/pages/index.js') }}"></script>
</body>
</html>