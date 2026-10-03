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
            indeferir um pedido (ou um recurso), a mudança aparece no site na hora. Confira as análises antes de
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
                                @endphp
                                <tr>
                                    <td>{{ $item['label'] }}</td>
                                    <td class="text-center" style="width: 120px;">
                                        <span class="badge {{ $isPublic ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $isPublic ? 'Pública' : 'Oculta' }}
                                        </span>
                                    </td>
                                    <td class="text-end" style="width: 160px;">
                                        <form method="POST" action="{{ route('admin.publications.toggle', $key) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('{{ $isPublic ? 'Ocultar esta lista do site?' : 'Liberar esta lista para o público?' }}');">
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
    </div>
@endsection