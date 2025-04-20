@extends('regions::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('regions.name') !!}</p>
@endsection
