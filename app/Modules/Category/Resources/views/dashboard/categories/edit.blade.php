@extends('category::dashboard.layout')

@section('title', 'Category')

@section('content')
    <h1>{{ __('Edit') }}</h1>
    <form action="{{ route('dashboard.category.update', $category->id) }}" method="post">
        @csrf
        @method('PUT')
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
