@extends('category::dashboard.tests.layout')

@section('title', 'Test')

@section('content')
    <h1>{{ __('Edit') }}</h1>
    <form action="{{ route('dashboard.category.update', $test->id) }}" method="post">
        @csrf
        @method('PUT')
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
