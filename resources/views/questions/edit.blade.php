@extends('layouts.app')
@section('title', 'Modifier la question')
@section('content')
<h1 class="mb-4">Modifier la question</h1>
<form method="POST" action="{{ route('questions.update', $question) }}">
    @csrf
    @method('PUT')
    @include('questions._form', ['question' => $question])
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
@endsection
