@extends('layouts.app')
@section('title', 'Chapitres - ' . $cour->titre)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Chapitres</h1>
        <p class="text-muted mb-0">Cours : {{ $cour->titre }}</p>
    </div>
    <div>
        <a href="{{ route('cours.chapitres.create', $cour) }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouveau chapitre</a>
        <a href="{{ route('cours.index') }}" class="btn btn-outline-secondary">Retour aux cours</a>
    </div>
</div>
<table class="table table-striped">
    <thead><tr><th style="width:60px">Ordre</th><th>Titre</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse ($chapitres as $chapitre)
            <tr>
                <td>{{ $chapitre->ordre_affichage }}</td>
                <td>{{ $chapitre->titre }}</td>
                <td class="text-nowrap">
                    <form method="POST" action="{{ route('chapitres.monter', $chapitre) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Monter"><i class="bi bi-arrow-up"></i></button>
                    </form>
                    <form method="POST" action="{{ route('chapitres.descendre', $chapitre) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Descendre"><i class="bi bi-arrow-down"></i></button>
                    </form>
                    <a href="{{ route('chapitres.edit', $chapitre) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Modifier"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('chapitres.destroy', $chapitre) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce chapitre ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Supprimer"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3">Aucun chapitre pour ce cours.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
