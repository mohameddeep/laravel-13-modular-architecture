@extends('auth::dashboard.auths.layout')

@section('title', 'Auth')

@section('content')
    <h1>{{ __('Edit') }}</h1>
    <form action="{{ route('dashboard.auth.update', $auth->id) }}" method="post">
        @csrf
        @method('PUT')
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
