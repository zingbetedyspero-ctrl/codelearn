@extends('layouts.app')
@section('title', 'Journal anti-fraude')
@section('content')
<h1 class="mb-4">Journal anti-fraude</h1>
<p class="text-muted">Détections de changement d'onglet et de copier-coller pendant les évaluations (RG08).</p>
<table class="table table-striped">
    <thead><tr><th>Apprenant</th><th>Action</th><th>Date</th></tr></thead>
    <tbody>
        @forelse ($journaux as $j)
            <tr>
                <td>{{ $j->user->nomComplet() }}</td>
                <td>
                    <span class="badge {{ $j->type_action === 'copier_coller' ? 'bg-danger' : 'bg-secondary' }}">
                        {{ str_replace('_', ' ', $j->type_action) }}
                    </span>
                </td>
                <td>{{ $j->created_at->format('d/m/Y H:i:s') }}</td>
            </tr>
        @empty
            <tr><td colspan="3">Aucune activité suspecte enregistrée.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $journaux->links() }}
@endsection
