@extends('auth::dashboard.auths.layout')

@section('title', 'Auth')

@section('content')
    <h1>{{ __('Index') }}</h1>
    <p><a href="{{ route('dashboard.auth.create') }}">{{ __('Create') }}</a></p>
    <ul>
        @foreach ($items as $auth)
            <li>
                #{{ $auth->id }}
                <a href="{{ route('dashboard.auth.edit', $auth->id) }}">{{ __('Edit') }}</a>
                <form action="{{ route('dashboard.auth.destroy', $auth->id) }}" method="post" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">{{ __('Delete') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
