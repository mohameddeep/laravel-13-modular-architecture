@extends('base::components.dashboard.layouts.master')

@section('title', __('dashboard.edit_permission'))

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">

            @include('base::components.dashboard.layouts._breadcrumb', ['items' => [
                ['label' => __('dashboard.permissions_list'), 'route' => route('dashboard.permissions.index')],
                ['label' => __('dashboard.edit_permission')],
            ]])

            <div class="content-body px-1 px-sm-2">
                <div class="row">
                    <div class="col-12 col-md-8 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">{{ __('dashboard.edit_permission') }}: <code>{{ $permission->name }}</code></h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body p-2 p-sm-3">
                                    <form method="POST" action="{{ route('dashboard.permissions.update', $permission->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group mb-2">
                                            <label for="name">{{ __('dashboard.name') }}</label>
                                            <input type="text" id="name" name="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', $permission->name) }}" required>
                                            @error('name')<span class="text-danger small">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group mb-2">
                                            <label for="display_name">{{ __('dashboard.display_name') }}</label>
                                            <input type="text" id="display_name" name="display_name"
                                                   class="form-control @error('display_name') is-invalid @enderror"
                                                   value="{{ old('display_name', $permission->display_name) }}">
                                            @error('display_name')<span class="text-danger small">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="description">{{ __('dashboard.description') }}</label>
                                            <textarea id="description" name="description" rows="3"
                                                      class="form-control">{{ old('description', $permission->description) }}</textarea>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="feather icon-save"></i> {{ __('dashboard.update') }}
                                            </button>
                                            <a href="{{ route('dashboard.permissions.index') }}" class="btn btn-outline-secondary">
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
