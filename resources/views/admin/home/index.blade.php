@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF - Painel Administrativo')

@push('styles')
    <style>
        .statistics-container .card {
            transition: box-shadow 0.2s ease-in-out, transform 0.2s ease-in-out;
        }

        .statistics-container .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-2px);
        }

        .statistics-container .card-body {
            padding: 1.75rem 1rem;
        }

        .statistics-container .card-body .fs-2 {
            font-size: 2.2rem !important;
        }

        .statistics-container .card-body p {
            font-size: 0.95rem;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h5>📝 Principais Tarefas</h5>

        @include('admin.partials.stepper')

        @include('admin.partials.progress')

        @include('admin.partials.graphics')

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    <script src="{{ asset('assets/js/admin/charts/burghs.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/courses.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/schools.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/genders.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/gender-per-course.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/chart-actions.js') }}"></script>
@endpush
