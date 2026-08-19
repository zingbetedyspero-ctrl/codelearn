@extends('layouts.app')
@section('title', 'Vérifier un certificat')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4">Vérifier un certificat</h1>
        <form method="GET" action="{{ route('certificats.verifier.resultat') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="code" value="{{ $code ?? '' }}" placeholder="Ex : CL-XXXX-XXXX" class="form-control">
                <button class="btn btn-primary">Vérifier</button>
            </div>
        </form>

        @isset($code)
            @if ($certificat)
                <div class="alert alert-success">
                    <h5><i class="bi bi-patch-check-fill"></i> Certificat valide</h5>
                    <p class="mb-1"><strong>Apprenant :</strong> {{ $certificat->inscription->payement->user->nomComplet() }}</p>
                    <p class="mb-1"><strong>Cours :</strong> {{ $certificat->inscription->payement->cour->titre }}</p>
                    <p class="mb-0"><strong>Score :</strong> {{ $certificat->score_final }}%</p>
                </div>
            @else
                <div class="alert alert-danger">Aucun certificat ne correspond à ce code.</div>
            @endif
        @endisset
    </div>
</div>
@endsection
