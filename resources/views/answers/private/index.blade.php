@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Gabaritos de Provas')

@section('dash-content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-file-earmark-zip me-2"></i>Gabaritos</h5>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setFile">
                <i class="bi bi-plus-circle me-1"></i> Novo
            </a>
        </div>

        @if ($answers->isNotEmpty())

        <div class="table-responsive">


            <table class="table-striped table caption-top">

                <caption>Lista de Gabaritos das Provas do Vestibulinho</caption>

                <thead class="table-success text-center">
                    <tr>
                        {{-- <th scope="col">#</th> --}}
                        <th scope="col">Ano</th>
                        <th scope="col">Gabarito</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>

                <tbody class="table-group-divider">

                    @foreach($answers as $answer)
                        
                        <tr>
                            <td scope="row">{{ $answer->archive->year }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $answer->file) }}" target="_blank">
                                    Gabarito Vestibulinho {{ $answer->archive->year }}
                                </a>
                            </td>
                            <td class="d-flex align-items-center justify-content-center gap-2">

                                {{-- Botão de excluir --}}
                                <form id="delete-answer-form-{{ $answer->id }}"
                                    action="{{ route('answer.destroy', $answer->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                    onclick="confirmAnswerDelete({{ $answer->id }}, 'Gabarito {{ $answer->archive->year }}')">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        @else

            @include('components.no-records', [
                        'message' => 'Causas de problemas com gabaritos:',
                        'submessage' => 'Provavelmente nenhuma arquivo foi cadastrado no sistema.',
                        'action' => true,
                        'actionMessage' =>
                            'Solução: Clique no botão "Novo" para iniciar o cadastro. Se o problema persistir, entre em contato com o suporte.',
                    ])

        @endif

        <div class="modal fade" id="setFile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="createAnswerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="setFileLabel"><i class="bi bi-file-earmark-zip me-1"></i>Gabarito
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">

                                <form id="form-file" action="{{ route('answer.create') }}" method="POST"
                                    enctype="multipart/form-data" novalidate>
                                    @csrf

                                    {{-- Arquivo relacionado --}}
                                    <div class="form-floating mb-3">
                                        <input type="file" name="file"
                                            class="form-control @error('file') is-invalid @enderror" id="file" value="{{ old('file') }}">
                                        <label for="file" class="form-label required">Arquivo relacionado</label>
                                    @error('file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    </div>

                                    {{-- Arquivo relacionado --}}
                                    <div class="form-floating mb-3">
                                        <select name="archive_id" id="archive_id" class="form-select @error('archive_id') is-invalid @enderror">
                                            <option value="">Selecione o vestibulinho relacionado</option>
                                            @foreach ($archives as $archive)
                                                <option value="{{ $archive->id }}">Vestibulinho {{ $archive->year }}</option>
                                            @endforeach
                                        </select>
                                        <label for="archive_id" class="form-label required">Vestibulinho</label>
                                    @error('year')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    </div>

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
    <script src="{{ asset('assets/rules/admin/answer/index.js') }}"></script>
    <script src="{{ asset('assets/swa/answer/delete.js') }}"></script>
@endpush