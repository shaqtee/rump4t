@extends('community::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('community.name') !!}</p>
@endsection
