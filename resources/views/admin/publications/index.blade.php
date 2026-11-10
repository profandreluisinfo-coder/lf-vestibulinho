@extends('layouts.admin')

@section('page-title', 'Publicações')

@section('content')
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h5 class="mb-1">📢 Publicações</h5>
                <p class="text-muted small mb-0">
                    Libere ou oculte as listas da página pública de Publicações. Listas ocultas aparecem como
                    "Em breve" para o candidato.
                </p>
            </div>

            @if (Route::has('site.publications.index'))
                <a href="{{ route('site.publications.index') }}" target="_blank" rel="noopener"
                    class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-box-arrow-up-right"></i> Ver página pública
                </a>
            @endif
        </div>

        <div class="alert alert-warning small" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Depois de liberada, a lista acompanha as análises <strong>em tempo real</strong>: se você deferir ou
            indeferir um pedido, a mudança aparece no site na hora. Confira as análises antes de
            liberar.
        </div>

        @foreach (collect($lists)->groupBy('group', true) as $group => $items)
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-{{ $items->first()['icon'] }} me-2"></i>{{ $group }}
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <tbody>
                            @foreach ($items as $key => $item)
                                @php
                                    $isPublic = in_array($key, $released, true);
                                    $date = isset($item['date']) ? $process?->{$item['date']} : null;
                                    $late = !$isPublic && $date && $date->lt(today());
                                @endphp

                                <tr>
                                    <td>{{ $item['label'] }}</td>
                                    <td class="text-center small {{ $late ? 'text-danger fw-semibold' : 'text-muted' }}"
                                        style="width: 190px;">
                                        Previsto: {{ $process?->formatDate($date) ?? '—' }}
                                    </td>
                                    <td class="text-center" style="width: 120px;">
                                        <span class="badge {{ $isPublic ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $isPublic ? 'Pública' : 'Oculta' }}
                                        </span>
                                    </td>
                                    <td class="text-end" style="width: 160px;">
                                        <form method="POST" action="{{ route('admin.publications.toggle', $key) }}"
                                            class="d-inline js-confirm-action"
                                            data-confirm-title="{{ $isPublic ? 'Ocultar lista?' : 'Liberar lista?' }}"
                                            data-confirm-text="{{ $isPublic
                                                ? 'Esta lista deixará de ser exibida publicamente no site.'
                                                : 'Esta lista será disponibilizada publicamente no site.' }}"
                                            data-confirm-button="{{ $isPublic ? 'Sim, ocultar' : 'Sim, liberar' }}"
                                            data-confirm-icon="{{ $isPublic ? 'warning' : 'question' }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="btn btn-sm {{ $isPublic ? 'btn-outline-secondary' : 'btn-success' }}">
                                                @if ($isPublic)
                                                    <i class="bi bi-eye-slash"></i> Ocultar
                                                @else
                                                    <i class="bi bi-broadcast"></i> Liberar
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        {{-- Acesso dos candidatos: local e resultado da prova (guardados no Processo) --}}
        @php

            $accessGroups = [
                [
                    'title' => 'Local de Prova',
                    'icon' => 'geo-alt',
                    'type' => 'location',
                    'label' => 'Local, data e hora da prova',
                    'releasedAt' => $process?->location_released_at,
                    'textOn' => 'O local da prova deixará de ser exibido aos candidatos.',
                    'textOff' => 'O local da prova será exibido aos candidatos.',
                    'dueDate' => $process?->location_publish,
                ],
                [
                    'title' => 'Resultado da Prova',
                    'icon' => 'clipboard-check',
                    'type' => 'result',
                    'label' => 'Classificação geral (classificados e não classificados)',
                    'releasedAt' => $process?->result_released_at,
                    'textOn' => 'O resultado deixará de ser exibido aos candidatos.',
                    'textOff' => 'O resultado será exibido aos candidatos.',
                    'dueDate' => $process?->result_publish,
                ],
            ];
        @endphp

        @if ($process)
            @foreach ($accessGroups as $access)
                {{-- @php
                    $isPublic = $access['releasedAt'] !== null;
                @endphp --}}
                @php
                    $isPublic = $access['releasedAt'] !== null;
                    $due = $access['dueDate'];
                    $late = !$isPublic && $due && $due->lt(today());
                @endphp

                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-{{ $access['icon'] }} me-2"></i>{{ $access['title'] }}
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td>{{ $access['label'] }}</td>
                                    <td class="text-center small {{ $late ? 'text-danger fw-semibold' : 'text-muted' }}"
                                        style="width: 190px;">
                                        Previsto: {{ $process->formatDate($due) }}
                                    </td>
                                    <td class="text-center" style="width: 120px;">

                                        <span class="badge {{ $isPublic ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $isPublic ? 'Pública' : 'Oculta' }}
                                        </span>
                                    </td>
                                    <td class="text-end" style="width: 160px;">
                                        <form method="POST" action="{{ route('admin.process.release', $access['type']) }}"
                                            class="d-inline js-confirm-action"
                                            data-confirm-title="{{ $isPublic ? 'Ocultar?' : 'Liberar?' }}"
                                            data-confirm-text="{{ $isPublic ? $access['textOn'] : $access['textOff'] }}"
                                            data-confirm-button="{{ $isPublic ? 'Sim, ocultar' : 'Sim, liberar' }}"
                                            data-confirm-icon="{{ $isPublic ? 'warning' : 'question' }}">
                                            @csrf

                                            <button type="submit"
                                                class="btn btn-sm {{ $isPublic ? 'btn-outline-secondary' : 'btn-success' }}">
                                                @if ($isPublic)
                                                    <i class="bi bi-eye-slash"></i> Ocultar
                                                @else
                                                    <i class="bi bi-broadcast"></i> Liberar
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-confirm-action').forEach(function(form) {

                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const title = form.dataset.confirmTitle;
                    const text = form.dataset.confirmText;
                    const confirmButton = form.dataset.confirmButton;
                    const icon = form.dataset.confirmIcon || 'question';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonText: confirmButton,
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                        focusCancel: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });

            });
        });
    </script>
@endpush
