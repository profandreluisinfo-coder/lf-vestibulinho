@props([
    'type' => 'info',
    'message' => '',
    'icon' => null,
    'dismissible' => false,
    'class' => ''
])

@php
    // Mapeamento de tipos para classes Bootstrap e ícones padrão
    $typeConfig = [
        'success' => [
            'class' => 'alert-success',
            'icon' => 'bi bi-check-circle',
            'title' => 'Sucesso!'
        ],
        'danger' => [
            'class' => 'alert-danger',
            'icon' => 'bi bi-exclamation-triangle',
            'title' => 'Erro!'
        ],
        'warning' => [
            'class' => 'alert-warning',
            'icon' => 'bi bi-exclamation-circle',
            'title' => 'Atenção!'
        ],
        'info' => [
            'class' => 'alert-info',
            'icon' => 'bi bi-check-circle',
            'title' => 'Informação'
        ],
        'primary' => [
            'class' => 'alert-primary',
            'icon' => 'bi bi-bell',
            'title' => 'Notificação'
        ],
        'secondary' => [
            'class' => 'alert-secondary',
            'icon' => 'bi bi-chat-dots',
            'title' => 'Mensagem'
        ]
    ];

    $config = $typeConfig[$type] ?? $typeConfig['info'];
    $iconClass = $icon ?? $config['icon'];
    
    // Classes CSS
    $alertClass = "alert {$config['class']} {$class}";
    if ($dismissible) {
        $alertClass .= ' alert-dismissible fade show';
    }
@endphp

<div {{ $attributes->merge(['class' => $alertClass]) }} role="alert">
    <div class="d-flex align-items-center">
        @if($iconClass)
            <i class="fas {{ $iconClass }} me-3 fs-5"></i>
        @endif
        
        <div class="flex-grow-1">
            @if(isset($title) || !empty($config['title']))
                <h5 class="alert-heading mb-1">
                    {{ $title ?? $config['title'] }}
                </h5>
            @endif
            
            @if($message)
                <div class="mb-0">{{ $message }}</div>
            @else
                {{ $slot }}
            @endif
        </div>
    </div>

    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    @endif
</div>