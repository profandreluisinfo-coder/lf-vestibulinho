<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Vestibulinho {{ $process?->year }} - @yield('page-title')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Bootstrap & Ícones --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Estilos adicionais --}}
    @stack('datatable-styles')

    {{-- Estilos --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin/styles.css') }}">

    @stack('styles')

    @stack('head-scripts')
</head>

<body>
    <!-- Sidebar -->
    @include('partials.admin.sidebar')

    <!-- Overlay para mobile -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Topbar -->
    @include('partials.admin.topbar')

    <!-- Conteúdo -->
    <main class="main-content">

        @include('shared.toasts')

        @yield('content')

        {{-- Modal Alterar Senha --}}
        @include('partials.forms.change-password')

    </main>

    {{-- Offcanvas Menu --}}
    @include('partials.admin.offcanvas')

    {{-- === PLUGGINS === --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>

    {{-- === PLUGGINS ESPECÍFICOS === --}}
    @stack('plugins')

    {{-- === JS === --}}
    <script src="{{ asset('assets/js/admin/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/shared/toasts.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/shared/change-password.js') }}"></script>
    <script src="{{ asset('assets/js/shared/popovers.js') }}"></script>

    {{-- === JS ESPECÍFICOS === --}}
    @stack('scripts')

    @if (session('open_modal') === 'password')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
            });
        </script>
    @endif

    @if (request()->routeIs('admin.export.excel'))
        <script src="{{ asset('assets/js/admin/export/handler.js') }}"></script>
    @endif

</body>

</html>