@extends('layouts.admin')

@section('page-title', 'Gráficos')


@section('content')
    <div class="container">
        
        @include('partials.admin.charts')

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
