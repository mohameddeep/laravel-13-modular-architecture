@extends('base::components.dashboard.layouts.master')

@section('title', __('dashboard.add_admin'))

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">

            @include('base::components.dashboard.layouts._breadcrumb', ['items' => [
                ['label' => __('dashboard.admins'), 'route' => route('dashboard.admins.index')],
                ['label' => __('dashboard.add_admin')],
            ]])

            <div class="content-body px-1 px-sm-2">
                <div class="row">
                    <div class="col-12 col-md-8 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">{{ __('dashboard.add_admin') }}</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body p-2 p-sm-3">
                                    <form action="{{ route('dashboard.admins.store') }}" method="POST">
                                        @csrf

                                        <div class="form-group mb-2">
                                            <label for="name">{{ __('dashboard.name') }}</label>
                                            <input type="text" id="name" name="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name') }}" required>
                                            @error('name')<span class="text-danger small">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group mb-2">
                                            <label for="email">{{ __('dashboard.email') }}</label>
                                            <input type="email" id="email" name="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email') }}" required>
                                            @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group mb-2">
                                            <label for="password">{{ __('dashboard.password') }}</label>
                                            @include('base::components.dashboard.forms.password-reveal', [
                                                'name' => 'password',
                                                'id' => 'password',
                                                'inputClass' => $errors->has('password') ? 'is-invalid' : '',
                                                'required' => true,
                                                'autocomplete' => 'new-password',
                                            ])
                                            @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="password_confirmation">{{ __('dashboard.password_confirmation') }}</label>
                                            @include('base::components.dashboard.forms.password-reveal', [
                                                'name' => 'password_confirmation',
                                                'id' => 'password_confirmation',
                                                'inputClass' => '',
                                                'required' => true,
                                                'autocomplete' => 'new-password',
                                            ])
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="feather icon-save"></i> {{ __('dashboard.save') }}
                                            </button>
                                            <a href="{{ route('dashboard.admins.index') }}" class="btn btn-outline-secondary">
                                                {{ __('dashboard.cancel') }}
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
