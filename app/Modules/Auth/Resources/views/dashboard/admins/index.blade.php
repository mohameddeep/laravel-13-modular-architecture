@extends('auth::dashboard.admins.layout')

@section('title', 'Admin')

@section('content')
    <h1>{{ __('Index') }}</h1>
    <p><a href="{{ route('dashboard.auth.create') }}">{{ __('Create') }}</a></p>
    <ul>
        @foreach ($items as $admin)
            <li>
                #{{ $admin->id }}
                <a href="{{ route('dashboard.auth.edit', $admin->id) }}">{{ __('Edit') }}</a>
                <form action="{{ route('dashboard.auth.destroy', $admin->id) }}" method="post" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">{{ __('Delete') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
