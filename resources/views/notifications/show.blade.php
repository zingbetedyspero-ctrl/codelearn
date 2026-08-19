@extends('layouts.app')
@section('title', $notification->data['titre'] ?? 'Notification')

@section('content')
<style>
    .btn-cta-solid {
        background: var(--cl-bleu);
        color: var(--cl-blanc);
        border: none;
        border-radius: var(--cl-radius-md);
        font-weight: 600;
        padding: 0.7rem 1.6rem;
        transition: background var(--cl-transition-base);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-cta-solid:hover {
        background: var(--cl-bleu-fonce);
        color: var(--cl-blanc);
    }

    .notif-detail-wrapper {
        max-width: 640px;
        margin: 0 auto;
    }

    .notif-detail-card {
        background: var(--cl-blanc);
        border-radius: var(--cl-radius-lg);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        padding: var(--cl-space-16);
    }

    .notif-detail-icone {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--cl-bleu) 15%, white);
        color: var(--cl-bleu-fonce);
        font-size: 2rem;
        margin: 0 auto var(--cl-space-8);
    }

    .notif-detail-titre {
        font-family: var(--cl-font-display);
        font-size: var(--cl-text-2xl, 1.5rem);
        color: var(--cl-gris-900);
        margin-bottom: var(--cl-space-4);
        text-align: center;
    }

    .notif-detail-date {
        color: var(--cl-gris-500);
        font-size: 0.85rem;
        margin-bottom: var(--cl-space-16);
        text-align: center;
    }

    .notif-detail-greeting {
        font-weight: 600;
        color: var(--cl-gris-900);
        margin-bottom: var(--cl-space-6);
    }

    .notif-detail-lignes p {
        color: var(--cl-gris-700);
        line-height: 1.7;
        margin-bottom: var(--cl-space-4);
    }

    .notif-detail-action {
        text-align: center;
        margin-top: var(--cl-space-16);
        padding-top: var(--cl-space-8);
        border-top: 1px solid var(--cl-gris-200);
    }

    .notif-detail-retour {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--cl-gris-500);
        text-decoration: none;
        font-size: 0.9rem;
        margin-top: var(--cl-space-8);
    }
    .notif-detail-retour:hover {
        color: var(--cl-bleu);
    }
</style>

<div class="notif-detail-wrapper">
    <div class="notif-detail-card">
        <div class="notif-detail-icone"><i class="bi bi-bell-fill"></i></div>

        <div class="notif-detail-titre">{{ $notification->data['titre'] ?? 'Notification' }}</div>
        <div class="notif-detail-date">{{ $notification->created_at->format('d/m/Y à H:i') }}</div>

        <div class="notif-detail-lignes">
            @if (! empty($notification->data['greeting']))
                <p class="notif-detail-greeting">{{ $notification->data['greeting'] }}</p>
            @endif

            @if (! empty($notification->data['details']))
                @foreach ($notification->data['details'] as $ligne)
                    <p>{{ $ligne }}</p>
                @endforeach
            @elseif (! empty($notification->data['message']))
                <p>{{ $notification->data['message'] }}</p>
            @endif
        </div>

        @if (! empty($notification->data['url']))
            <div class="notif-detail-action">
                <a href="{{ $notification->data['url'] }}" class="btn-cta-solid btn-lg">
                    {{ $notification->data['bouton'] ?? 'Accéder' }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>

    <div class="text-center">
        <a href="{{ route('notifications.index') }}" class="notif-detail-retour">
            <i class="bi bi-arrow-left"></i> Retour aux notifications
        </a>
    </div>
</div>
@endsection