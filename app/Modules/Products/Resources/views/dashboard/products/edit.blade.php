@extends('products::dashboard.layout')

@section('title', 'Product')

@section('content')
    <h1>{{ __('Edit') }}</h1>
    <form action="{{ route('dashboard.products.update', $product->id) }}" method="post">
        @csrf
        @method('PUT')
        <button type="submit">{{ __('Save') }}</button>
    </form>
@endsection
