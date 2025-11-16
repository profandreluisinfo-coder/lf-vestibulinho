@php
    $alertMap = [
        'error' => [
            'class' => 'danger',
            'title' => 'Erro!',
            'icon'  => 'exclamation-octagon-fill'
        ],
        'warning' => [
            'class' => 'warning',
            'title' => 'Atenção!',
            'icon'  => 'exclamation-triangle-fill'
        ],
        'success' => [
            'class' => 'success',
            'title' => 'Sucesso!',
            'icon'  => 'check-circle-fill'
        ],
        'info' => [
            'class' => 'info',
            'title' => 'Informação!',
            'icon'  => 'info-circle-fill'
        ],
    ];
@endphp

@foreach ($alertMap as $key => $alert)
    @if (session($key))

        <div class="alert alert-{{ $alert['class'] }} border-0 shadow-sm py-3 fade show 
                    rounded-3 animate__animated animate__fadeIn">
            
            <div class="fw-bold text-center mb-1">
                <i class="bi bi-{{ $alert['icon'] }} me-1"></i>
                {{ $alert['title'] }}
            </div>

            <div class="text-center">
                {{ session($key) }}
            </div>
        </div>

    @endif
@endforeach