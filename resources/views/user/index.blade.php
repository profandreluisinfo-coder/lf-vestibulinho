@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Lista de Inscrições')

@section('dash-content')

    <div class="table-responsive">
        <table id="subscribers" class="table table-striped caption-top">
            <caption>{{ config('app.name') }} {{ $calendar?->year }} - Lista Geral de Inscritos</caption>
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
                        <th scope="row">
                            {{ $user->inscription?->id }}
                        </th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->cpf }}</td>
                        <td>
                            <a href="{{ route('users.details', Crypt::encrypt($user->id)) }}" class="text-decoration-none">
                                <i class="bi bi-search animate__animated animate__fadeIn me-2" title="Visualizar"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Nenhum inscrito encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection