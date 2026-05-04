@extends('category::dashboard.layout')

@section('title', 'SubCategory')

@section('content')
    <h1>{{ __('Create') }}</h1>
    <form action="{{ route('dashboard.category.store') }}" method="post">
        @csrf
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
