@extends('auth::dashboard.admins.layout')

@section('title', 'Admin')

@section('content')
    <h1>{{ __('Edit') }}</h1>
    <form action="{{ route('dashboard.auth.update', $admin->id) }}" method="post">
        @csrf
        @method('PUT')
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
