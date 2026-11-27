@extends('layouts.admin')

@section('page-title', 'Modelos')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [
            ['label' => 'Início', 'url' => route('admin.index')],
            ['label' => 'Modelos', 'url' => route('admin.templates.index')],
            ['label' => 'Novo'],
        ],
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
                    <h4>Novo Modelo</h4>
                    <span>Crie um novo modelo de autorização de uso de nome social</span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="form-file" action="{{ route('admin.templates.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Ano do processo seletivo --}}
                    <div class="form-group mb-3">
                        <label for="process_id" class="form-label required">Processo Seletivo</label>
                        <select name="process_id" id="process_id"
                            class="form-control @error('process_id') is-invalid @enderror" aria-label="Processo Seletivo">
                            <option value="">...</option>
                            @foreach ($vests as $vest)
                                <option value="{{ $vest->id }}" {{ old('process_id') == $vest->id ? 'selected' : '' }}>
                                    Vestibulinho {{ $vest->year }}
                                </option>
                            @endforeach
                        </select>
                        @error('process_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Arquivo relacionado --}}
                    <div class="form-group mb-3">
                        <label for="file" class="form-label required">Selecione o arquivo</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                            id="file" aria-label="Selecione o arquivo">
                        @error('file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check-lg"></i>Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#form-file').validate({
                rules: {
                    process_id: {
                        required: true
                    },
                    file: {
                        required: true,
                        extension: "pdf"
                    }
                },
                messages: {
                    process_id: {
                        required: "* Por favor, selecione um processo seletivo."
                    },
                    file: {
                        required: "* Por favor, selecione um arquivo.",
                        extension: "* Por favor, selecione um arquivo válido (PDF)."
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                },
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    getTargets(element).removeClass('is-invalid');
                }
            });

            // Previne envio com Enter
            $("#form-file").on("keyup keypress", function(e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush
