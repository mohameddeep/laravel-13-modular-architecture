@extends('products::dashboard.layout')

@section('title', 'Product')

@section('content')
    <h1>{{ __('Create') }}</h1>
    <form action="{{ route('dashboard.products.store') }}" method="post">
        @csrf
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
