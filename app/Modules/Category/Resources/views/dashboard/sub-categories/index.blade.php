@extends('category::dashboard.layout')

@section('title', 'SubCategory')

@section('content')
    <h1>{{ __('Index') }}</h1>
    <p><a href="{{ route('dashboard.category.create') }}">{{ __('Create') }}</a></p>
    <ul>
        @foreach ($items as $subCategory)
            <li>
                #{{ $subCategory->id }}
                <a href="{{ route('dashboard.category.edit', $subCategory->id) }}">{{ __('Edit') }}</a>
                <form action="{{ route('dashboard.category.destroy', $subCategory->id) }}" method="post" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">{{ __('Delete') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
