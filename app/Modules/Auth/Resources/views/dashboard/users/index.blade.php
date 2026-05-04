@extends('auth::dashboard.users.layout')

@section('title', 'User')

@section('content')
    <h1>{{ __('Index') }}</h1>
    <p><a href="{{ route('dashboard.auth.create') }}">{{ __('Create') }}</a></p>
    <ul>
        @foreach ($items as $user)
            <li>
                #{{ $user->id }}
                <a href="{{ route('dashboard.auth.edit', $user->id) }}">{{ __('Edit') }}</a>
                <form action="{{ route('dashboard.auth.destroy', $user->id) }}" method="post" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">{{ __('Delete') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
