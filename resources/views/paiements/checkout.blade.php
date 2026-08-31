@extends('layouts.app')
@section('title', 'Paiement - ' . $cour->titre)

@push('styles')
<style>
    .cl-checkout-card { max-width: 520px; margin: 0 auto; }
    .cl-trust-badge { display:flex; align-items:center; gap:8px; color: var(--cl-gris-500); font-size: var(--cl-text-sm); }
</style>
@endpush

@section('content')
<div class="cl-checkout-card">
    <div class="text-center mb-4">
        <i class="bi bi-shield-lock" style="font-size:2rem; color: var(--cl-bleu);"></i>
        <h1 class="mt-2">Paiement sécurisé</h1>
    </div>

    <div class="card p-4 mb-4">
        <h6 class="text-muted mb-3">Résumé de la commande</h6>
        <div class="d-flex justify-content-between mb-2">
            <span>{{ $cour->titre }}</span>
            <span>{{ number_format($cour->prix, 0, ',', ' ') }} FCFA</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between fw-bold">
            <span>Total</span>
            <span>{{ number_format($cour->prix, 0, ',', ' ') }} FCFA</span>
        </div>
    </div>

    <div class="card p-4 text-center mb-4">
        <p class="text-muted small mb-3">Prestataire de paiement</p>
        <img src="https://cdn.fedapay.com/assets/logo.svg" alt="FedaPay" style="height:32px; margin: 0 auto;" onerror="this.style.display='none'">
        <button id="pay-btn" class="btn btn-primary btn-lg w-100 mt-3">
            <i class="bi bi-lock-fill"></i> Payer {{ number_format($cour->prix, 0, ',', ' ') }} FCFA
        </button>
    </div>

    <div class="d-flex justify-content-center gap-4">
        <div class="cl-trust-badge"><i class="bi bi-shield-check"></i> Paiement chiffré</div>
        <div class="cl-trust-badge"><i class="bi bi-arrow-repeat"></i> Accès immédiat</div>
        <div class="cl-trust-badge"><i class="bi bi-headset"></i> Support disponible</div>
    </div>
</div>

<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
<script>
    FedaPay.init('#pay-btn', {
        public_key: '{{ $publicKey }}',
        transaction: { id: '{{ $payement->transaction_id }}', token: '{{ $token }}' },
        onComplete: function (response) {
            window.location.href = "{{ route('paiements.retour', $cour) }}";
        }
    });
</script>
@endsection
