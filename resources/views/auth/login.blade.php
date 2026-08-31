@extends('layouts.auth')
@section('title', 'Connexion')
@section('content')
    @include('auth._tabs', ['actif' => 'login'])
@endsection
