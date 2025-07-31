@extends('landing.layout.app')

@section('title', 'Apotik Sakura')

@section('content')
    @include('landing.components.masthead')
    @include('landing.components.beranda')
    @include('landing.components.product')
    @include('landing.components.team')
    @include('landing.components.contact')
@endsection
