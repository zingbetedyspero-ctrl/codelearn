@extends('layouts.app')
@section('title', 'Notifications')

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

    .admin-card {
        background: var(--cl-blanc);
        border-radius: var(--cl-radius-lg);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .notif-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--cl-space-6);
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid var(--cl-gris-200);
        text-decoration: none;
        color: inherit;
        transition: background var(--cl-transition-base);
    }
    .notif-row:last-child { border-bottom: none; }
    .notif-row:hover { background: color-mix(in srgb, var(--cl-bleu) 12%, white); }
    .notif-row.non-lue { background: var(--cl-bleu-clair); }
    .notif-row:nth-child(even):not(.non-lue) { background: var(--cl-bleu-clair); }
    .notif-row:nth-child(even):not(.non-lue):hover { background: color-mix(in srgb, var(--cl-bleu) 12%, white); }

    .notif-icone {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--cl-bleu) 15%, white);
        color: var(--cl-bleu-fonce);
        font-size: 1.1rem;
    }
    .notif-row.non-lue .notif-icone {
        background: var(--cl-bleu);
        color: var(--cl-blanc);
    }

    .notif-corps { flex: 1; min-width: 0; }
    .notif-titre {
        font-weight: 600;
        color: var(--cl-gris-900);
        margin-bottom: 2px;
    }
    .notif-message {
        color: var(--cl-gris-700);
        font-size: 0.9rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .notif-meta {
        text-align: right;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .notif-date {
        color: var(--cl-gris-500);
        font-size: 0.8rem;
    }

    .badge-nouveau {
        display: inline-block;
        background: color-mix(in srgb, var(--cl-bleu) 15%, white);
        color: var(--cl-bleu-fonce);
        font-weight: 700;
        font-size: 0.75rem;
        padding: 0.3em 0.7em;
        border-radius: 999px;
        margin-bottom: 0.3rem;
    }
    .notif-row.non-lue .badge-nouveau {
        background: var(--cl-bleu);
        color: var(--cl-blanc);
    }

    .admin-empty {
        text-align: center;
        color: var(--cl-gris-500);
        padding: 3rem 1rem;
    }
    .admin-empty i {
        font-size: 2rem;
        display: block;
        margin-bottom: 0.5rem;
        color: var(--cl-gris-300, #D1D5DB);
    }
</style>

<div class="admin-header">
    <div>
        <span class="cl-eyebrow">Activité</span>
        <h1>Notifications</h1>
    </div>
</div>

<div class="admin-card">
    @forelse ($notifications as $notification)
        <a href="{{ route('notifications.show', $notification->id) }}"
           class="notif-row {{ is_null($notification->read_at) ? 'non-lue' : '' }}">
            <div class="notif-icone"><i class="bi bi-bell"></i></div>
            <div class="notif-corps">
                <div class="notif-titre">{{ $notification->data['titre'] ?? 'Notification' }}</div>
                <div class="notif-message">{{ $notification->data['message'] ?? '' }}</div>
            </div>
            <div class="notif-meta">
                @if (is_null($notification->read_at))
                    <span class="badge-nouveau">Nouveau</span><br>
                @endif
                <span class="notif-date">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
        </a>
    @empty
        <div class="admin-empty">
            <i class="bi bi-bell-slash"></i>
            Aucune notification pour le moment.
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $notifications->links() }}
</div>
@endsection