@extends('auth::dashboard.users.layout')

@section('title', 'User')

@section('content')
    <h1>{{ __('Create') }}</h1>
    <form action="{{ route('dashboard.auth.store') }}" method="post">
        @csrf
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
