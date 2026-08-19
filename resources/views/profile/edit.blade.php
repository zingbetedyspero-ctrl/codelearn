@extends('layouts.app')
@section('title', 'Mon profil')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4">Mon profil</h1>
        <form method="POST" action="{{ route('profile.update') }}" class="mb-5">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" class="form-control @error('prenom') is-invalid @enderror">
                    @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" class="form-control @error('nom') is-invalid @enderror">
                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" class="form-control @error('telephone') is-invalid @enderror">
                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
        <hr>
        <h2 class="mb-3 mt-4">Changer le mot de passe</h2>
        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                <div class="form-text">Au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial (ex : @#$%!?&amp;).</div>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <button type="submit" class="btn btn-secondary">Changer le mot de passe</button>
        </form>
    </div>
</div>
@endsection
