@extends('auth::dashboard.users.layout')

@section('title', 'User')

@section('content')
    <h1>{{ __('Edit') }}</h1>
    <form action="{{ route('dashboard.auth.update', $user->id) }}" method="post">
        @csrf
        @method('PUT')
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
