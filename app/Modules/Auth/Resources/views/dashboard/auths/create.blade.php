@extends('auth::dashboard.auths.layout')

@section('title', 'Auth')

@section('content')
    <h1>{{ __('Create') }}</h1>
    <form action="{{ route('dashboard.auth.store') }}" method="post">
        @csrf
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
