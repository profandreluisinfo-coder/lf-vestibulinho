@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Arquivos de Provas')

@section('dash-content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-file-earmark-zip me-2"></i>Arquivos</h5>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setFile">
                <i class="bi bi-plus-circle me-1"></i> Novo
            </a>
        </div>

        <div class="text-info fw-semibold mb-2">
            <i class="bi bi-info-circle fs-6 me-1"></i>Cada prova poderá conter (ou não) um gabarito vinculado, que poderá ser registrado no momento do cadastro da prova, ou, durante a edição da mesma.
        </div>

        <div class="table-responsive">

            <table class="table-striped table caption-top">

                <caption>Lista de Provas do Vestibulinho</caption>

                <thead class="table-success text-center">
                    <tr>
                        {{-- <th scope="col">#</th> --}}
                        <th scope="col">Ano</th>
                        <th scope="col">Prova</th>
                        <th scope="col">Gabarito</th>
                        <th scope="col">Status</th>
                        <th scope="col">Usuário</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>

                <tbody class="table-group-divider">

                    @forelse($files as $file)
                        
                        <tr>
                            <td scope="row">{{ $file->year }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $file->file) }}" target="_blank">
                                    Vestibulinho {{ $file->year }}
                                </a>
                            </td>
                            <td>
                                @if ($file->answer?->file)
                                <a href="{{ asset('storage/' . $file->answer->file) }}" target="_blank">
                                    Gabarito
                                </a>
                                @else
                                    <span class="text-danger">Não possui</span>
                                @endif
                            </td>
                            <td><span
                                    class="badge bg-{{ $file->status ? 'success' : 'warning' }}">{{ $file->status ? 'publicado' : 'não publicado' }}</span>
                            </td>
                            <td>{{ $file->user->name }}</td>
                            <td class="d-flex align-items-center justify-content-center gap-2">

                                {{-- Botão de publicar (alterar status) --}}
                                <form id="archive-form-{{ $file->id }}"
                                    action="{{ route('archive.publish', $file->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button type="button"
                                    class="btn btn-sm btn-{{ $file->status ? 'secondary' : 'success' }} l"
                                    title="{{ $file->status ? 'Não Publicar' : 'Publicar' }}"
                                    onclick="confirmFilePublish({{ $file->id }}, 'Vestibulinho {{ $file->year }}')">
                                    <i class="bi bi-{{ $file->status ? 'eye-slash' : 'eye' }} me-1"></i>
                                </button>

                                <a href="{{ route('archive.edit', $file->id) }}"
                                    class="btn btn-sm btn-primary l" title="Editar">
                                    <i class="bi bi-pencil-square me-1"></i>
                                </a>

                                <form action="{{ route('archive.destroy', $file->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger l"
                                        title="Excluir">
                                        <i class="bi bi-trash me-1"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty

                        @include('components.no-records', [
                            'message' => 'Causas de problemas com arquivos:',
                            'submessage' => 'Provavelmente nenhuma arquivo de prova foi cadastrado no sistema.',
                            'action' => true,
                            'actionMessage' =>
                                'Solução: Clique no botão "Novo" para iniciar o cadastro. Se o problema persistir, entre em contato com o suporte.',
                        ])

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="modal fade" id="setFile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="createArchiveModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="setFileLabel"><i class="bi bi-file-earmark-zip me-1"></i>Arquivar Prova
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">

                                <form id="form-file" action="{{ route('archive.create') }}" method="POST"
                                    enctype="multipart/form-data" novalidate>
                                    @csrf

                                    {{-- Ano em que a prova foi aplicada --}}
                                    <div class="form-floating mb-3">
                                        <input type="name" name="year"
                                            class="form-control @error('year') is-invalid @enderror" id="year"
                                            placeholder="Ano em que a prova foi realizada" value="{{ old('year') }}"
                                            required>
                                        <label for="year" class="form-label required">Ano em que a prova foi
                                            aplicada</label>
                                        @error('year')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Arquivo relacionado --}}
                                    <div class="form-floating mb-3">
                                        <input type="file" name="file"
                                            class="form-control @error('file') is-invalid @enderror" id="file" value="{{ old('file') }}" required>
                                        <label for="file" class="form-label required">Arquivo relacionado</label>
                                        @error('file')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Gabarito relacionado --}}
                                    <div class="form-floating mb-3">
                                        <input type="file" name="answer"
                                            class="form-control @error('answer') is-invalid @enderror" id="answer" value="{{ old('answer') }}">
                                        <label for="answer" class="form-label required">Gabarito relacionado (Se houver)</label></label>
                                    @error('answer')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="text-end">
                                        {{-- prettier-ignore --}}
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle me-1"></i>Salvar
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i
                                class="bi bi-x-circle me-1"></i>Fechar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/rules/admin/archives/index.js') }}"></script>
    <script src="{{ asset('assets/swa/archives/publish.js') }}"></script>
@endpush