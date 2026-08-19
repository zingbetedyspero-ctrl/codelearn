@extends('layouts.app')
@section('title', 'Mes tentatives')
@section('content')
<h1 class="mb-4">Historique de mes tentatives</h1>
<table class="table table-striped">
    <thead><tr><th>Cours</th><th>Évaluation</th><th>Tentative</th><th>Score</th><th>Statut</th><th>Date</th></tr></thead>
    <tbody>
        @forelse ($tentatives as $t)
            <tr>
                <td>{{ $t->evaluation->cour->titre ?? '—' }}</td>
                <td>{{ $t->evaluation->titre }}</td>
                <td>#{{ $t->numero_tentative }}</td>
                <td>{{ $t->score }}%</td>
                <td><span class="badge {{ $t->statut === 'reussi' ? 'bg-success' : 'bg-secondary' }}">{{ $t->statut }}</span></td>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="6">Aucune tentative pour l'instant.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $tentatives->links() }}
@endsection
