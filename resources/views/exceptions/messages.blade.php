@extends('layouts.app')

@section('title', env('APP_NAME') . ' ' . env('APP_YEAR') . ' | Formulário de Inscrição')

@section('content')

    <div class="alert alert-danger">
        {{ $message }}
    </div>

@endsection