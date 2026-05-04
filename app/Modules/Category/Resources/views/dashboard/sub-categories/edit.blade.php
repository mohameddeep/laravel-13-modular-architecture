@extends('category::dashboard.layout')

@section('title', 'SubCategory')

@section('content')
    <h1>{{ __('Edit') }}</h1>
    <form action="{{ route('dashboard.category.update', $subCategory->id) }}" method="post">
        @csrf
        @method('PUT')
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
