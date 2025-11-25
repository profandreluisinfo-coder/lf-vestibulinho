@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Importar Notas')

@section('dash-content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-upload me-2"></i>Importar Notas</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('import.results') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="file" class="form-label">Selecione a planilha (.xlsx):</label>
                        <div class="alert alert-warning">
                            <strong>Atenção:</strong> A planilha deve conter obrigatoriamente os seguintes cabeçalhos, nesta
                            ordem:<br>
                            <code>inscription_id | user_id | user_cpf | user_name | user_birth | points</code><br>
                            Certifique-se de que esses nomes estão exatamente assim, sem espaços extras ou acentos.
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mb-3 float-end" data-bs-toggle="modal"
                            data-bs-target="#exampleSheet"><i class="bi bi-search me-2"></i>Ver Modelo</button>
                        <input type="file" name="file" id="file" accept=".xlsx"
                            class="form-control @error('file') is-invalid @enderror" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-upload me-2"></i>Importar Notas</button>
                </form>
            </div>
        </div>
        <!-- The Modal -->
        <div class="modal" id="exampleSheet">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="bi bi-file-earmark-excel me-2"></i>Modelo de cabeçalho de planilha de importacao de notas</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <img src="{{ asset('assets/img/modelo_importacao_notas.png') }}" class="img-fluid" alt="Modelo de cabeçalho da planilha de importacao_notas">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
