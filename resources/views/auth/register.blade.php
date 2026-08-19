@extends('layouts.auth')
@section('title', 'Inscription')
@section('content')
    @include('auth._tabs', ['actif' => 'register'])
@endsection
