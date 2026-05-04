@extends('category::dashboard.tests.layout')

@section('title', 'Test')

@section('content')
    <h1>{{ __('Create') }}</h1>
    <form action="{{ route('dashboard.category.store') }}" method="post">
        @csrf
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
