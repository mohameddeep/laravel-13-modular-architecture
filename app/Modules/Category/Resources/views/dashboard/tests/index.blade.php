@extends('category::dashboard.tests.layout')

@section('title', 'Test')

@section('content')
    <h1>{{ __('Index') }}</h1>
    <p><a href="{{ route('dashboard.category.create') }}">{{ __('Create') }}</a></p>
    <ul>
        @foreach ($items as $test)
            <li>
                #{{ $test->id }}
                <a href="{{ route('dashboard.category.edit', $test->id) }}">{{ __('Edit') }}</a>
                <form action="{{ route('dashboard.category.destroy', $test->id) }}" method="post" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">{{ __('Delete') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
