@extends('layouts.admin')

@section('page-title', 'Modelos de Autorização de Uso de Nome Social')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [['label' => 'Início', 'url' => route('admin.index')], ['label' => 'Modelos']],
    ])
@endsection

@section('content')

    <div class="container">
        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-file-earmark"></i>
                </div>
                <div>
                    <h4>Modelos</h4>
                    <span>
                        {{ $templates->total() }}
                        {{ $templates->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Novo
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-success">
                    <tr>
                        <th scope="col">Vestibulinho</th>
                        <th scope="col">Arquivo</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">

                    @forelse($templates as $template)
                        <tr>
                            <td>
                                {{ $template->process?->year ?? 'Sem processo vinculado' }}
                            </td>
                            <td>
                                <a href="{{ Storage::url($template->file_path) }}" target="_blank">
                                    Modelo {{ $template->id }} - Nome Social <i class="bi bi-box-arrow-up-right ms-2"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="status-pill {{ $template->status ? 'status-open' : 'status-closed' }}">
                                    <i class="bi bi-{{ $template->status ? 'unlock' : 'lock' }}"></i>
                                    {{ $template->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="d-flex gap-2">
                                {{-- Botão de Ativar (alterar status) --}}
                                <form id="archive-form-{{ $template->id }}"
                                    action="{{ route('admin.templates.publish', ['template' => $template]) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button type="button"
                                    class="btn btn-sm btn-outline-{{ $template->status === 'active' ? 'warning' : 'success' }}"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    data-bs-title="{{ $template->status ? 'Desativar' : 'Ativar' }}"
                                    onclick="confirmFilePublish({{ $template->id }}, 'Modelo {{ $template->id }} - Nome Social')"
                                    aria-label="{{ $template->status === 'active' ? 'Desativar' : 'Ativar' }}">
                                    <i class="bi bi-{{ $template->status === 'active' ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                                {{-- Botão de Editar --}}
                                <a href="{{ route('admin.templates.edit', ['template' => $template]) }}"
                                    class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="Editar" aria-label="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                {{-- Botão de Excluir --}}
                                <form id="delete-template-form-{{ $template->id }}" action="{{ route('admin.templates.destroy', ['template' => $template]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="Excluir" aria-label="Excluir"
                                        onclick="confirmTemplateDelete({{ $template->id }}, 'Modelo {{ $template->id }} - Nome Social')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum modelo encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/templates/publish.js') }}"></script>
    <script src="{{ asset('assets/js/admin/templates/delete.js') }}"></script>
@endpush
