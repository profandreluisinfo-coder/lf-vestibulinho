@extends('layouts.user.master')
{{-- ESTE É O LAYOUT PADRÃO DOS FORMULÁRIOS. ELE EXTENDE O LAYOUT DO USUÁRIO --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/user/inscription.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/steps/hsteps.css') }}">
@endpush

@push('head-scripts')
    <script src="{{ asset('assets/js/reloadif.js') }}"></script>
@endpush

@section('dash-content')
    <section>
        <article>
            <div class="row justify-content-center">
                <div class="col-central col-12">
                    <div class="text-danger fst-italic mb-1 text-end">
                        <span class="required"></span> Indica um campo obrigatório
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 py-3">
                    <div class="card shadow-lg">
                        <div class="card-header text-white">
                            <h6 class="mb-0"><span
                                    class="badge rounded-pill {{ $bg }} me-2">{{ $step }}</span>
                                {{ $title }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-lg-3 mb-3 p-3">
                                    @include('partials.forms.steps')
                                    @php
                                        $totalSteps = 8;
                                        $completed = 0;

                                        for ($i = 1; $i <= $totalSteps; $i++) {
                                            if (session()->has('step' . $i . '_done')) {
                                                $completed++;
                                            }
                                        }

                                        $progress = $completed / $totalSteps;
                                    @endphp
                                    @include('partials.forms.hsteps')
                                </div>
                                <div class="forms col-md-8 col-lg-9 p-3">

                                    @include('partials.forms.progress-bar')

                                    @yield('forms') <!-- Aqui vai o formulário -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>
@endsection

@push('plugins')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/addons/cleave-phone.br.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush