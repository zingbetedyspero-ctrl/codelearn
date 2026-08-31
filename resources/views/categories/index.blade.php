@extends('layouts.app')
@section('title', 'Catégories')

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
    .admin-table .categorie-nom {
        font-weight: 600;
        color: var(--cl-gris-900);
    }

    .badge-nb-cours {
        background: color-mix(in srgb, var(--cl-bleu) 15%, white);
        color: var(--cl-bleu-fonce);
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0.35em 0.75em;
        border-radius: 999px;
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
        <h1>Catégories</h1>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-cta-solid"><i class="bi bi-plus-lg"></i> Nouvelle catégorie</a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Nb. cours</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $categorie)
                    <tr>
                        <td class="categorie-nom">{{ $categorie->nom }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($categorie->description, 60) }}</td>
                        <td><span class="badge-nb-cours">{{ $categorie->cours_count }}</span></td>
                        <td class="admin-actions">
                            <a href="{{ route('categories.edit', $categorie) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Modifier"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('categories.destroy', $categorie) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="admin-empty">Aucune catégorie.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>
@endsection