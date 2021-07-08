@extends('layouts.app')

@section('title', 'Homepage')

@section('content')

@section('h1', 'Movie Lending Library')

<div class="list-group">
    <a href="{{route('movie.index')}}" class="list-group-item list-group-item-action">Movies</a>
    <a href="{{route('member.index')}}" class="list-group-item list-group-item-action">Members</a>
    <a href="{{route('lend.index')}}" class="list-group-item list-group-item-action">Lending</a>
    <a href="{{route('return.index')}}" class="list-group-item list-group-item-action">Returning</a>
</div>
@endsection
