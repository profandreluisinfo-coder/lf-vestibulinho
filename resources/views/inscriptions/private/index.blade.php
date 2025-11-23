@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Inscrições')

@section('dash-content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-people me-2"></i>Candidatos Inscritos</h4>
        </div>
        {{-- Formulário de Busca --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body">
                <form method="GET" action="{{ route('inscriptions.index') }}" class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group"> <span class="input-group-text bg-light"> <i class="bi bi-search"></i>
                            </span> <input type="text" name="search" class="form-control"
                                placeholder="Buscar por inscrição, nome ou CPF..." value="{{ request('search') }}"> </div>
                    </div>
                    <div class="col-md-2 d-flex gap-2"> <button type="submit" class="btn btn-primary flex-fill"> <i
                                class="bi bi-search me-1"></i>Buscar </button>
                        @if (request('search'))
                            <a href="{{ route('inscriptions.index') }}" class="btn btn-outline-secondary"
                                title="Limpar filtros"> <i class="bi bi-x-lg"></i> </a>
                        @endif
                    </div>
                </form>
            </div>

        </div>

        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('inscriptions.pdf', ['search' => request('search')]) }}"
                class="btn btn-danger d-flex align-items-center justify-content-center" id="pdfButton" target="_blank">
                <i class="bi bi-filetype-pdf me-1"></i>
                <span>Gerar PDF</span>
            </a>

        </div>

        <div class="table-responsive">

            <table id="subscribers" class="table table-striped table-hover caption-top align-middle">
                <caption> Lista Geral de Inscritos
                    @if (request('search'))
                        - Filtrando por: "{{ request('search') }}"
                    @endif
                </caption>
                <thead class="table-success text-center">
                    <tr>
                        <th>Inscrição</th>
                        <th>Candidato</th>
                        <th>CPF</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row"> {{ $user->inscription?->id }} </th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->cpf }}</td>
                            <td> <a href="{{ route('inscriptions.details', Crypt::encrypt($user->id)) }}"
                                    class="text-decoration-none"> <i
                                        class="bi bi-search animate__animated animate__fadeIn me-2" title="Visualizar"></i>
                                </a> </td>
                    </tr> @empty <tr>
                            <td colspan="4" class="text-center py-4"> <i
                                    class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                @if (request('search'))
                                    Nenhum inscrito encontrado para "{{ request('search') }}"
                                @else
                                    Nenhum inscrito encontrado
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Links de paginação --}}
        @if ($users->hasPages())
            <div class="d-flex justify-content-center mt-4"> {{ $users->appends(request()->query())->links() }} </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('pdfButton').addEventListener('click', function(event) {
            const btn = this;

            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';

            btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Gerando PDF, aguarde...
    `;

            // Após alguns segundos, volta ao normal (PDF já estará baixando)
            setTimeout(() => {
                btn.classList.remove('disabled');
                btn.style.pointerEvents = 'auto';

                btn.innerHTML = `
            <i class="bi bi-filetype-pdf me-1"></i>
            <span>PDF</span>
        `;
            }, 3000); // 3 segundos é ideal
        });
    </script>
@endpush
