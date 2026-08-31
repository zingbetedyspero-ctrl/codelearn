<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CodeLearn')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/design-system.css') }}" rel="stylesheet">
    @stack('styles')
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .cl-main-content {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('accueil') }}">Code<span class="cl-brand-accent">Learn</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <div class="navbar-nav me-auto ms-4">
                    <a class="nav-link" href="{{ route('accueil') }}">Accueil</a>
                    <a class="nav-link" href="{{ route('catalogue.index') }}">Catalogue</a>
                    @auth
                        @if (auth()->user()->estAdministrateur())
                            <a class="nav-link" href="{{ route('admin.apprenants.index') }}">Apprenants</a>
                            <a class="nav-link" href="{{ route('cours.index') }}">Cours</a>
                            <a class="nav-link" href="{{ route('categories.index') }}">Catégories</a>
                            <a class="nav-link" href="{{ route('statistiques.admin') }}">Statistiques</a>
                            <a class="nav-link" href="{{ route('admin.journal.index') }}">Journal</a>
                        @else
                            <a class="nav-link" href="{{ route('statistiques.apprenant') }}">Mes progrès</a>
                            <a class="nav-link" href="{{ route('certificats.index') }}">Mes certificats</a>
                        @endif
                    @endauth
                </div>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-secondary btn-sm position-relative" data-bs-toggle="dropdown">
                                <i class="bi bi-bell"></i>
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 320px;">
                                @forelse (auth()->user()->notifications()->latest()->take(8)->get() as $notif)
                                    <li>
                                        <a href="{{ route('notifications.show', $notif->id) }}"
                                           class="dropdown-item small {{ $notif->read_at ? 'text-muted' : 'fw-bold' }}">
                                            {{ $notif->data['titre'] ?? '' }}<br>
                                            <span class="text-muted fw-normal">{{ $notif->data['message'] ?? '' }}</span>
                                        </a>
                                    </li>
                                @empty
                                    <li class="dropdown-item small text-muted">Aucune notification.</li>
                                @endforelse
                                <li><hr class="dropdown-divider"></li>
                                <li><a href="{{ route('notifications.index') }}" class="dropdown-item small text-center">Voir toutes les notifications</a></li>
                            </ul>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="nav-link">{{ auth()->user()->nomComplet() }}</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">Connexion</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Inscription gratuite</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if (session('success') || session('error'))
        <div class="container mt-4">
            @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        </div>
    @endif

    <main class="cl-main-content {{ View::hasSection('full-width') ? '' : 'container mt-4' }}">
        @yield('content')
    </main>

    <footer class="mt-5 py-5" style="background: var(--cl-gris-900); color: var(--cl-gris-300);">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="text-white">Code<span style="color: var(--cl-orange);">Learn</span></h5>
                    <p class="small">La plateforme e-learning pour apprendre à coder, se tester et obtenir une certification reconnue.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-white">Liens</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('catalogue.index') }}" class="text-decoration-none" style="color: var(--cl-gris-300);">Catalogue des cours</a></li>
                        <li><a href="{{ route('register') }}" class="text-decoration-none" style="color: var(--cl-gris-300);">S'inscrire</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-white">CodeLearn</h6>
                    <p class="small">© {{ date('Y') }} CodeLearn. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>