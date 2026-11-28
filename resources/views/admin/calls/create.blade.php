@extends('layouts.admin')

@section('page-title', 'Convocação para matrícula')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [
            ['label' => 'Início', 'url' => route('admin.index')],
            ['label' => 'Convocações', 'url' => route('admin.calls.index')],
            ['label' => 'Nova convocação'],
        ],
    ])
@endsection

@section('content')
    {{-- Cabeçalho --}}
    <div class="page-header">
        <div class="page-header-title">
            <div class="icon-wrap">
                <i class="bi bi-broadcast-pin"></i>
            </div>
            <div>
                <h4>Convocação para matrícula</h4>
                <span>
                    Defina a quantidade de candidatos a serem convocados e a data e hora da convocação
                </span>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.calls.store') }}" method="POST" id="newCallForm">
                @csrf
                <div class="form-group mb-3">
                    <label for="number" class="form-label required">Número da chamada</label>
                    <input type="number" name="number" id="number" min="1"
                        class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}">
                    <div class="form-text">Números de chamada podem se repetir.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="limit" class="form-label required">Quantidade de candidatos</label>
                    <input type="number" name="limit" id="limit" min="1"
                        class="form-control @error('limit') is-invalid @enderror" value="{{ old('limit') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="pcd_limit" class="form-label">
                        Quantidade de PcDs nesta chamada
                        <button type="button" class="btn btn-link p-0 align-baseline" data-bs-toggle="modal"
                            data-bs-target="#pcdLimitModal" aria-label="Ajuda sobre a quantidade de PcDs">
                            <i class="bi bi-info-circle"></i>
                        </button>
                    </label>
                    <input type="number" name="pcd_limit" id="pcd_limit" min="0"
                        class="form-control @error('pcd_limit') is-invalid @enderror" value="{{ old('pcd_limit', 0) }}">
                </div>
                <div class="form-group mb-3">
                    <label for="date" class="form-label required">Data de comparecimento</label>
                    <input type="date" name="date" id="date"
                        class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="time" class="form-label required">Hora de comparecimento</label>
                    <input type="time" name="time" id="time"
                        class="form-control @error('time') is-invalid @enderror" value="{{ old('time') }}">
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i>Salvar</button>
                </div>
            </form>
            <div class="modal fade" id="pcdLimitModal" tabindex="-1" aria-labelledby="pcdLimitModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pcdLimitModalLabel">
                                <i class="bi bi-info-circle me-1"></i> Quantidade de PcDs nesta chamada
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body text-justify">
                            <p>Neste campo, você define o total de vagas reservadas para PcDs na chamada. Esse total já
                                inclui os PcDs com laudo deferido que entram automaticamente pela classificação. As vagas
                                que sobrarem são preenchidas manualmente.</p>

                            <p>Se houver empate na última posição, todos os empatados entram. Por isso, o total pode ficar
                                maior que o número definido.</p>

                            <p class="mb-0"><strong>Exemplo:</strong> em uma chamada com 45 candidatos, você informa 5
                                neste campo. Um PcD deferido já está na classificação e entra automaticamente. Os outros 4
                                são definidos manualmente. Resultado: 40 candidatos de Ampla Concorrência e 5 de PcD.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-check-lg me-1"></i>Entendi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Validação Jquery Validate --}}
    <script>
        $(document).ready(function() {
            $('#newCallForm').validate({
                rules: {
                    number: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    limit: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    pcd_limit: {
                        digits: true,
                        min: 0
                    },
                    date: {
                        required: true
                    },
                    time: {
                        required: true
                    }
                },
                messages: {
                    number: {
                        required: "* O número da chamada é obrigatório",
                        digits: "* O número da chamada deve ser um número inteiro",
                        min: "* O número da chamada deve ser maior que 0"
                    },
                    limit: {
                        required: "* A quantidade de candidatos é obrigatória",
                        digits: "* A quantidade de candidatos deve ser um número inteiro",
                        min: "* A quantidade de candidatos deve ser maior que 0"
                    },
                    pcd_limit: {
                        digits: "* A quantidade de PcDs deve ser um número inteiro",
                        min: "* A quantidade de PcDs deve ser maior ou igual a 0"
                    },
                    date: {
                        required: "* A data de comparecimento é obrigatória"
                    },
                    time: {
                        required: "* A hora de comparecimento é obrigatória"
                    }
                },
                submitHandler: function(form) {
                    const $btn = $(form).find('button[type="submit"]');
                    $btn.prop("disabled", true).html(
                        `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Aguarde...`
                    );
                    form.submit();
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $("#newCallForm").on("keyup keypress", function(e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush
