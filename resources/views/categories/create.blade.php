@extends('layouts.app')
@section('title', 'Nouvelle catégorie')
@section('content')
<h1 class="mb-4">Nouvelle catégorie</h1>
<form method="POST" action="{{ route('categories.store') }}">
    @csrf
    @include('categories._form')
    <button type="submit" class="btn btn-primary">Créer</button>
</form>
@endsection
