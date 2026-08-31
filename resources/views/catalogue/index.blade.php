@extends('layouts.app')
@section('title', 'Catalogue des cours')

@section('content')
<style>
    .catalogue-header h1 {
        font-weight: 700;
        font-size: 1.9rem;
        font-family: var(--cl-font-display);
        background: linear-gradient(90deg, var(--cl-bleu), var(--cl-bleu-fonce));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
    }
    .catalogue-header p {
        font-size: 0.95rem;
        color: var(--cl-gris-700);
    }
    .categorie-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--cl-gris-900);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .categorie-title::before {
        content: '';
        width: 6px;
        height: 20px;
        border-radius: var(--cl-radius-md);
        background: linear-gradient(180deg, var(--cl-bleu), var(--cl-bleu-fonce));
        display: inline-block;
    }

    .cours-card {
        border: none;
        border-radius: var(--cl-radius-lg);
        overflow: hidden;
        background: var(--cl-blanc);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: box-shadow var(--cl-transition-base), transform var(--cl-transition-base);
    }
    .cours-card:hover {
        box-shadow: var(--cl-shadow-lg);
        transform: translateY(-3px);
    }
    .cours-card .card-img-top {
        height: 150px;
        object-fit: cover;
    }
    .cours-img-placeholder {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--cl-bleu-clair);
    }
    .cours-img-placeholder i {
        font-size: 2rem;
        color: var(--cl-bleu);
    }
    .cours-card .card-body {
        padding: 1.5rem;
    }
    .cours-card .card-title {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--cl-gris-900);
        margin-bottom: 0.5rem;
    }
    .badge-niveau {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.35em 0.85em;
        border-radius: 999px;
        background: color-mix(in srgb, var(--cl-orange) 15%, white);
        color: var(--cl-orange);
        margin-bottom: 0.75rem;
    }
    .cours-prix {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--cl-gris-900);
        margin-bottom: 1rem;
    }
    .btn-gradient {
        background: linear-gradient(90deg, var(--cl-bleu), var(--cl-bleu-fonce));
        color: var(--cl-blanc);
        border: none;
        font-weight: 600;
        padding: 0.5rem 1.3rem;
        border-radius: 999px;
        transition: opacity var(--cl-transition-base);
        display: inline-block;
    }
    .btn-gradient:hover {
        opacity: 0.9;
        color: var(--cl-blanc);
    }
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
        color: var(--cl-bleu);
        background: var(--cl-bleu-clair);
        border-radius: var(--cl-radius-lg);
        font-weight: 500;
    }
</style>

<div class="catalogue-header mb-5">
    <h1 class="mb-2">Catalogue des cours</h1>
    <p class="text-muted">Découvrez nos formations, classées par catégorie. Le premier chapitre de chaque cours est gratuit.</p>
</div>

@foreach ($categories as $categorie)
    @if ($categorie->cours->isNotEmpty())
        <div class="mb-5">
            <div class="categorie-title">{{ $categorie->nom }}</div>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($categorie->cours as $c)
                    <div class="col">
                        <div class="cours-card h-100">
                            @if ($c->image_couverture)
                                <img src="{{ Storage::url($c->image_couverture) }}" class="card-img-top">
                            @else
                                <div class="cours-img-placeholder">
                                    <i class="bi bi-code-slash"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $c->titre }}</h5>
                                <span class="badge-niveau">{{ ucfirst($c->niveau) }}</span>
                                <p class="cours-prix">{{ number_format($c->prix, 0, ',', ' ') }} FCFA</p>
                                <a href="{{ route('catalogue.show', $c) }}" class="btn-gradient btn-sm">
                                    @auth
                                        @if(auth()->user()->estAdministrateur())
                                            Voir les détails
                                        @else
                                            Suivre cette formation
                                        @endif
                                    @else
                                        Suivre cette formation
                                    @endauth
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endforeach

@if ($coursSansCategorie->isNotEmpty())
    <div class="mb-5">
        <div class="categorie-title">Autres formations</div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($coursSansCategorie as $c)
                <div class="col">
                    <div class="cours-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $c->titre }}</h5>
                            <p class="cours-prix">{{ number_format($c->prix, 0, ',', ' ') }} FCFA</p>
                            <a href="{{ route('catalogue.show', $c) }}" class="btn-gradient btn-sm">
                                @auth
                                    @if(auth()->user()->estAdministrateur())
                                        Voir les détails
                                    @else
                                        Suivre cette formation
                                    @endif
                                @else
                                    Suivre cette formation
                                @endauth
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if ($categories->isEmpty() && $coursSansCategorie->isEmpty())
    <div class="empty-state">
        Aucun cours disponible pour le moment.
    </div>
@endif
@endsection