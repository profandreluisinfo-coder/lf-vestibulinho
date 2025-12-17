{{-- === SIDEBAR (DESKTOP) === --}}
<nav class="sidebar d-none d-lg-flex flex-column p-3">
    <div class="text-center mb-4">
        <a href="{{ route('control.panel') }}" class="d-flex flex-column align-items-center text-decoration-none text-light">
            <img src="{{ asset('assets/img/logo.webp') }}" width="60" class="mb-2" alt="Logo">
            <h5 class="m-0 fw-semibold text-white">{{ config('app.name') }} {{ $calendar?->year }}</h5>
        </a>
    </div>
    @include('partials.admin.sidebar-menu')
</nav>

{{-- === SIDEBAR (OFFCANVAS MOBILE) === --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">{{ config('app.name') }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        @include('partials.admin.sidebar-menu')
    </div>
</div>