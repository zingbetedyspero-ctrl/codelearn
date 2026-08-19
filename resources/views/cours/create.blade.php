@extends('layouts.app')
@section('title', 'Nouveau cours')
@section('content')
<h1 class="mb-4">Nouveau cours</h1>
<form method="POST" action="{{ route('cours.store') }}" enctype="multipart/form-data">
    @csrf
    @include('cours._form', ['categories' => $categories])
    <button type="submit" class="btn btn-primary">Créer</button>
</form>
@endsection
