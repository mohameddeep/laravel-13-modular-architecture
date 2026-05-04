@extends('products::dashboard.layout')

@section('title', 'Product')

@section('content')
    <h1>{{ __('Index') }}</h1>
    <p><a href="{{ route('dashboard.products.create') }}">{{ __('Create') }}</a></p>
    <ul>
        @foreach ($items as $product)
            <li>
                #{{ $product->id }}
                <a href="{{ route('dashboard.products.edit', $product->id) }}">{{ __('Edit') }}</a>
                <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="post" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">{{ __('Delete') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
