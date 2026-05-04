@extends('category::dashboard.layout')

@section('title', 'Category')

@section('content')
    <h1>{{ __('Index') }}</h1>
    <p><a href="{{ route('dashboard.category.create') }}">{{ __('Create') }}</a></p>
    <ul>
        @foreach ($items as $category)
            <li>
                #{{ $category->id }}
                <a href="{{ route('dashboard.category.edit', $category->id) }}">{{ __('Edit') }}</a>
                <form action="{{ route('dashboard.category.destroy', $category->id) }}" method="post" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">{{ __('Delete') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
