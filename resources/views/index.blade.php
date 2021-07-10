@extends('layouts.app')

@section('title', 'Homepage')

@section('content')

@section('h1', 'Movie Lending Library')

<div class="list-group">
    <a href="{{route('movie.index')}}" class="list-group-item list-group-item-action">Movies</a>
    <a href="{{route('member.index')}}" class="list-group-item list-group-item-action">Members</a>
    <a href="{{route('lend.home')}}" class="list-group-item list-group-item-action">Lending</a>
</div>
@endsection
