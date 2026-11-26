@extends('layouts.admin')

@section('page-title', 'Importar Prova')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Provas'],
    ]])
@endsection

@section('content')
    <div class="container">
        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-file-earmark-zip"></i>
                </div>
                <div>
                    <h4>Provas</h4>
                    <span>
                        {{ $files->total() }}
                        {{ $files->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.archives.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Nova prova
            </a>
        </div>
        <div class="table-responsive">
            <table class="table-striped table">
                <thead class="table-success">
                    <tr>
                        <th scope="col">Ano</th>
                        <th scope="col">Prova</th>
                        <th scope="col">Gabarito</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse($files as $file)
                        <tr>
                            <th scope="row">{{ $file->year }}</th>
                            <td>
                                <a href="{{ Storage::url($file->file) }}" target="_blank">
                                    Vestibulinho {{ $file->year }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </td>
                            <td>
                                @if ($file->answer?->file)
                                    <a href="{{ Storage::url($file->answer->file) }}" target="_blank">
                                        Gabarito {{ $file->year }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="status-pill {{ $file->status ? 'status-open' : 'status-closed' }}">
                                    <i class="bi bi-{{ $file->status ? 'unlock' : 'lock' }}"></i>
                                    {{ $file->status ? 'Publicado' : 'Não publicado' }}
                                </span>
                            </td>
                            <td class="d-flex gap-1">
                                {{-- Botão de publicar (alterar status) --}}
                                <form id="archive-form-{{ $file->id }}"
                                    action="{{ route('admin.archives.publish', $file->id) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button type="button"
                                    class="btn btn-sm btn-outline-{{ $file->status ? 'warning' : 'success' }}"
                                    title="{{ $file->status ? 'Ocultar' : 'Publicar' }}"
                                    onclick="confirmFilePublish({{ $file->id }}, 'Vestibulinho {{ $file->year }}')"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-title="{{ $file->status ? 'Ocultar' : 'Publicar' }}"
                                    aria-label="{{ $file->status ? 'Ocultar' : 'Publicar' }}">
                                    <i class="bi bi-{{ $file->status ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                                <a href="{{ route('admin.archives.edit', $file->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="Editar" aria-label="Editar"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" data-bs-title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.archives.destroy', $file->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-title="Excluir"
                                    aria-label="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhuma prova cadastrada</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $files->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/archives/publish.js') }}"></script>
@endpush
