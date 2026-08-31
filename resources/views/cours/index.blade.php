@extends('layouts.app')
@section('title', 'Cours')

@section('content')
<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--cl-space-4);
        margin-bottom: var(--cl-space-8);
    }
    .admin-header h1 {
        font-family: var(--cl-font-display);
        font-size: var(--cl-text-3xl);
        color: var(--cl-gris-900);
        margin: 0;
    }
    .btn-cta-solid {
        background: var(--cl-bleu);
        color: var(--cl-blanc);
        border: none;
        border-radius: var(--cl-radius-md);
        font-weight: 600;
        padding: 0.55rem 1.2rem;
        transition: background var(--cl-transition-base);
    }
    .btn-cta-solid:hover {
        background: var(--cl-bleu-fonce);
        color: var(--cl-blanc);
    }

    .admin-card {
        background: var(--cl-blanc);
        border-radius: var(--cl-radius-lg);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    .admin-table {
        margin-bottom: 0;
    }
    .admin-table thead th {
        font-family: var(--cl-font-display);
        font-size: var(--cl-text-sm);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--cl-blanc);
        background: var(--cl-bleu);
        border-bottom: none;
        padding: 0.9rem 1rem;
        white-space: nowrap;
    }
    .admin-table tbody tr:nth-child(even) {
        background: var(--cl-bleu-clair);
    }
    .admin-table tbody td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        color: var(--cl-gris-700);
        border-color: var(--cl-gris-200);
    }
    .admin-table tbody tr:hover {
        background: color-mix(in srgb, var(--cl-bleu) 12%, white);
    }
    .admin-table .cours-titre {
        font-weight: 600;
        color: var(--cl-gris-900);
    }
    .cours-thumb {
        width: 60px;
        height: 40px;
        object-fit: cover;
        border-radius: var(--cl-radius-md);
    }
    .cours-thumb-placeholder {
        width: 60px;
        height: 40px;
        border-radius: var(--cl-radius-md);
        background: var(--cl-bleu-clair);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--cl-bleu);
    }

    .badge-statut-publi {
        border: none;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.4em 0.9em;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.4em;
    }
    .badge-statut-publi::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }
    .badge-statut-publi.publie {
        background: #dcfce7;
        color: #15803d;
    }
    .badge-statut-publi.publie::before {
        background: #22c55e;
    }
    .badge-statut-publi.non-publie {
        background: #fee2e2;
        color: #b91c1c;
    }
    .badge-statut-publi.non-publie::before {
        background: #ef4444;
    }

    .admin-actions .btn {
        border-radius: var(--cl-radius-md);
    }

    .admin-empty {
        text-align: center;
        color: var(--cl-gris-500);
        padding: 2rem 1rem;
    }
</style>

<div class="admin-header">
    <div>
        <span class="cl-eyebrow">Administration</span>
        <h1>Cours</h1>
    </div>
    <a href="{{ route('cours.create') }}" class="btn btn-cta-solid"><i class="bi bi-plus-lg"></i> Nouveau cours</a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table admin-table align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Niveau</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cours as $c)
                    <tr>
                        <td style="width:80px">
                            @if ($c->image_couverture)
                                <img src="{{ Storage::url($c->image_couverture) }}" alt="" class="cours-thumb">
                            @else
                                <div class="cours-thumb-placeholder"><i class="bi bi-code-slash"></i></div>
                            @endif
                        </td>
                        <td class="cours-titre">{{ $c->titre }}</td>
                        <td>{{ $c->categorie->nom ?? '—' }}</td>
                        <td>{{ ucfirst($c->niveau) }}</td>
                        <td>{{ number_format($c->prix, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <form method="POST" action="{{ route('cours.toggle-statut', $c) }}" class="d-inline">
                                @csrf @method('PUT')
                                <button class="btn btn-sm badge-statut-publi {{ $c->estPublie() ? 'publie' : 'non-publie' }}"
                                        data-bs-toggle="tooltip" title="{{ $c->estPublie() ? 'Cliquer pour dépublier' : 'Cliquer pour publier' }}">
                                    {{ $c->estPublie() ? 'Publié' : 'Non publié' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-nowrap admin-actions">
                            <a href="{{ route('cours.chapitres.index', $c) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Chapitres"><i class="bi bi-list-ol"></i></a>
                            <a href="{{ route('cours.evaluations.index', $c) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Évaluations"><i class="bi bi-clipboard-check"></i></a>
                            <a href="{{ route('cours.edit', $c) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Modifier"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('cours.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce cours ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="admin-empty">Aucun cours.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $cours->links() }}
</div>
@endsection