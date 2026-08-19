@extends('layouts.app')
@section('title', 'Évaluations - ' . $cour->titre)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Évaluations</h1>
        <p class="text-muted mb-0">Cours : {{ $cour->titre }}</p>
    </div>
    <a href="{{ route('cours.evaluations.create', $cour) }}" class="btn btn-primary">Nouvelle évaluation</a>
</div>
<table class="table table-striped">
    <thead><tr><th>Titre</th><th>Type</th><th>Chapitre</th><th>Seuil</th><th>Durée max</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse ($evaluations as $evaluation)
            <tr>
                <td>{{ $evaluation->titre }}</td>
                <td>{{ $evaluation->type_evaluation === 'examen_final' ? 'Examen final' : 'Test de chapitre' }}</td>
                <td>{{ $evaluation->chapitre->titre ?? '—' }}</td>
                <td>{{ $evaluation->seuil_reussite }}%</td>
                <td>{{ $evaluation->duree_max }} min</td>
                <td class="text-nowrap">
                    <a href="{{ route('evaluations.questions.index', $evaluation) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Questions"><i class="bi bi-question-circle"></i></a>
                    <a href="{{ route('evaluations.edit', $evaluation) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Modifier"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('evaluations.destroy', $evaluation) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette évaluation ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Supprimer"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Aucune évaluation pour ce cours.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
