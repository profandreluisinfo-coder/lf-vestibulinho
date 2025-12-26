<div class="toast-container position-fixed end-0 p-3 z-3">

    @php
        $alertMap = [
            'error' => [
                'class' => 'danger',
                'title' => 'Erro!',
                'icon' => 'exclamation-octagon-fill',
                'silent' => false,
                'persistent' => true,
                'animation' => 'shakeX',
            ],
            'warning' => [
                'class' => 'warning',
                'title' => 'Atenção!',
                'icon' => 'exclamation-triangle-fill',
                'silent' => false,
                'persistent' => false,
                'animation' => 'pulse',
            ],
            'success' => [
                'class' => 'success',
                'title' => 'Sucesso!',
                'icon' => 'check-circle-fill',
                'silent' => false,
                'persistent' => false,
                'animation' => 'bounceIn',
            ],
            'info' => [
                'class' => 'info',
                'title' => 'Informação',
                'icon' => 'info-circle-fill',
                'silent' => true,
                'persistent' => false,
                'animation' => 'fadeIn',
            ],
        ];
    @endphp

    @foreach ($alertMap as $key => $alert)
        @if (session($key) && (!$alert['silent'] || config('app.debug')))
            <div class="toast animate__animated animate__{{ $alert['animation'] }} align-items-center text-bg-{{ $alert['class'] }} 
                        border-0 shadow mb-2 show animate__animated animate__fadeInDown"
                role="alert" data-message="{{ session($key) }}" data-type="{{ $key }}" data-bs-delay="3000">

                <div class="d-flex">
                    <div class="toast-body">
                        <strong>
                            <i
                                class="bi bi-{{ $alert['icon'] }} 
                               animate__animated animate__tada me-1"></i>
                            {{ $alert['title'] }}
                        </strong>
                        <div class="small">
                            {{ session($key) }}
                        </div>
                    </div>

                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    @endforeach

</div>