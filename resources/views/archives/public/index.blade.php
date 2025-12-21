@extends('layouts.home.master')

@push('metas')
    <meta name="description" content="Área de provas anteriores do {{ config('app.name') }}">
@endpush

@section('page-title', config('app.name') . ' | Provas Anteriores')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/exam/public/styles.css') }}">
@endpush

@section('body-class', 'bg-light')

@section('has-footer', 'has-footer')

@section('content')

    @include('home.navbar')

    @php
        $recenteId = $archives->first()->id;
    @endphp

    <section id="previous-exams" class="bg-light my-5 py-5">

        <div class="container">

            <div class="card">

                <div class="card-header"><i class="bi bi-file-earmark-pdf"></i> Provas e Gabaritos</div>

                <div class="card-body">

                    <div class="table-responsive py-3">

                        <table class="table table-hover align-middle text-center">
                            <caption class="text-muted text-end border-top pt-2">(*) A prova não possui gabarito associado.</caption>
                            <thead class="table-success">
                                <th>#</th>
                                <th>Ano</th>
                                <th>Prova</th>
                                <th>Gabarito</th>
                            </thead>

                            <tbody class="table-group-divider">

                                @forelse ($archives as $archive)
                                    <tr class="{{ $archive->id === $recenteId ? 'table-active fw-bold' : '' }}">
                                        <td><i class="bi bi-file-earmark-pdf fs-5 text-danger"></i></td>
                                        <th scope="row">
                                            @if ($archive->id === $recenteId)
                                                <i class="bi bi-star-fill text-warning me-1" title="Prova mais recente"></i>
                                            @endif
                                            {{ $archive->year }}
                                            @if ($archive->id === $recenteId)
                                                <span class="badge bg-success ms-2">Mais recente</span>
                                            @endif
                                        </th>
                                        <td>
                                            <a href="{{ asset('storage/' . $archive->file) }}" target="_blank">
                                                <i class="bi bi-download me-2"></i> Download
                                            </a>
                                        </td>
                                        <td>
                                            @if ($archive->answer?->file)
                                                <a href="{{ asset('storage/' . $archive->answer?->file) }}" target="_blank">
                                                    <i class="bi bi-download me-2"></i> Download
                                                </a>
                                            @else
                                                <span class="text-muted">*</span>
                                            @endif
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="4">Nenhuma prova encontrada</td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </section>

    @include('home.footer')

@endsection