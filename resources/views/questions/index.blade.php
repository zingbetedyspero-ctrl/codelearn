@extends('layouts.app')
@section('title', 'Questions - ' . $evaluation->titre)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Questions</h1>
        <p class="text-muted mb-0">Évaluation : {{ $evaluation->titre }} ({{ $evaluation->cour->titre }})</p>
    </div>
    <a href="{{ route('evaluations.questions.create', $evaluation) }}" class="btn btn-primary">Nouvelle question</a>
</div>
@forelse ($questions as $question)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <span class="badge {{ $question->estQcm() ? 'bg-info' : 'bg-secondary' }}">{{ $question->estQcm() ? 'QCM' : 'Question ouverte' }}</span>
                    <span class="text-muted ms-2">{{ $question->temps_reponse }}s · {{ $question->bareme }} pts</span>
                </div>
                <div>
                    <a href="{{ route('questions.edit', $question) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Modifier"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('questions.destroy', $question) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette question ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Supprimer"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
            <p class="mt-3 mb-2">{{ $question->enonce }}</p>
            @if ($question->estQcm())
                <ul class="list-unstyled mb-0">
                    @foreach ($question->optionsReponse as $option)
                        <li>@if ($option->is_correct)<strong class="text-success">✓ {{ $option->option_texte }}</strong>@else{{ $option->option_texte }}@endif</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@empty
    <p>Aucune question pour cette évaluation.</p>
@endforelse
@endsection
