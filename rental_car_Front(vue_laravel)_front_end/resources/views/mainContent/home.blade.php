@extends('layouts.app')

@section('content')
    <home-component user-name={{auth()->user()->name}}></home-component>
@endsection
