@extends('layouts.admin.master')

@section('page-title', 'Inscrição Finalizada')

@section('dash-content')
    
    <div class="wrapper">
        <div class="card shadow-lg">
            <div class="card-body text-center">

                {{-- ALERTA --}}
                @if (session('status'))
                    <div class="alert alert-{{ session('status.alert-type') }} alert-dismissible fade show" role="alert">
                        {{ session('status.message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                {{-- ÍCONE --}}
                <h5 class="mb-4">
                    @if (session('status.alert-type') === 'danger')
                        <i class="bi bi-exclamation-triangle text-danger display-4 animate__animated animate__fadeIn"></i>
                    @endif
                </h5>

                {{-- TÍTULO --}}
                <h2 class="mb-3">
                    @if (session('status.alert-type') === 'danger')
                        Ocorreu um erro na inscrição
                    @endif
                </h2>

                {{-- MENSAGEM PRINCIPAL --}}
                    <p>
                        Infelizmente sua inscrição <strong>não foi concluída</strong>.
                    </p>
                    <p>
                        Motivo: <strong>{{ session('status.message') }}</strong>
                    </p>
                    <p>
                        Por favor, revise os dados e tente novamente. Se o problema persistir, entre em contato com o
                        suporte.
                    </p>

                {{-- BOTÃO --}}
                <a href="{{ route('login') }}" class="btn btn-outiline-danger btn-sm mt-3">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>

            </div>
        </div>
    </div>

@endsection