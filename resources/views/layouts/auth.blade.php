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
        .cl-auth-topbar { display: flex; align-items: center; justify-content: space-between; padding: var(--cl-space-6) var(--cl-space-8); }
        .cl-auth-logo { font-family: var(--cl-font-display); font-weight: 700; font-size: var(--cl-text-xl); color: var(--cl-gris-900); text-decoration: none; }
        .cl-auth-logo .accent { color: var(--cl-bleu); }
    </style>
</head>
<body>
    <div class="cl-auth-topbar">
        <a href="{{ route('accueil') }}" class="cl-auth-logo">Code<span class="accent">Learn</span></a>
        <a href="{{ route('accueil') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Retour à l'accueil</a>
    </div>

    @if (session('success') || session('error'))
        <div class="container mt-2">
            @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        </div>
    @endif

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    @stack('scripts')
</body>
</html>
