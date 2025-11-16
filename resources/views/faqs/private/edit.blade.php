@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Editar FaQ')

@section('dash-content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-question-circle me-2"></i>Editar FaQ</h4>
        </div>


        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('faq.edit', $faq->id) }}" method="POST" id="edit-faq-form-{{ $faq->id }}">
                    @csrf
                    <div class="mb-3">
                        <label for="question" class="form-label required">Pergunta:</label>
                        <input type="text" class="form-control" id="question" name="question"
                            value="{{ $faq->question }}">
                    </div>
                    <div class="mb-3">
                        <label for="answer" class="form-label required">Resposta:</label>
                        <textarea class="form-control" id="answer" name="answer" rows="3">{{ $faq->answer }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gravar</button>
                </form>
            </div>
        </div>
    </div>
@endsection