@extends('base::components.dashboard.layouts.master')

@section('title', __('dashboard.edit_role'))

@section('styles')
<style>
    .permission-matrix th, .permission-matrix td { vertical-align: middle; }
    .permission-matrix thead th { background: #f8f9fa; font-size: 0.85rem; }
    .permission-matrix td:not(:first-child) { text-align: center; }
</style>
@endsection

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">

            @include('base::components.dashboard.layouts._breadcrumb', ['items' => [
                ['label' => __('dashboard.roles_list'), 'route' => route('dashboard.roles.index')],
                ['label' => __('dashboard.edit_role')],
            ]])

            <div class="content-body px-1 px-sm-2">
                <div class="row">
                    <div class="col-12 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">
                                    {{ __('dashboard.edit_role') }}: <code>{{ $role->name }}</code>
                                </h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body p-2 p-sm-3">
                                    <form method="POST" action="{{ route('dashboard.roles.update', $role->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="form-group col-12 col-md-6 mb-2">
                                                <label for="display_name_en">{{ __('dashboard.display_name_en') }}</label>
                                                <input type="text" id="display_name_en" name="display_name_en"
                                                       class="form-control @error('display_name_en') is-invalid @enderror"
                                                       value="{{ old('display_name_en', $role->display_name_en) }}" required>
                                                @error('display_name_en')<span class="text-danger small">{{ $message }}</span>@enderror
                                            </div>
                                            <div class="form-group col-12 col-md-6 mb-2">
                                                <label for="display_name_ar">{{ __('dashboard.display_name_ar') }}</label>
                                                <input type="text" id="display_name_ar" name="display_name_ar"
                                                       class="form-control @error('display_name_ar') is-invalid @enderror"
                                                       value="{{ old('display_name_ar', $role->display_name_ar) }}" required>
                                                @error('display_name_ar')<span class="text-danger small">{{ $message }}</span>@enderror
                                            </div>
                                            <div class="form-group col-12 mb-2">
                                                <label>{{ __('dashboard.system_name') }}</label>
                                                <input type="text" class="form-control bg-light" value="{{ $role->name }}" readonly>
                                            </div>
                                        </div>

                                        @include('roles::dashboard.roles._permission_matrix', [
                                            'permissions'   => $permissions,
                                            'selectedPerms' => $rolePermissions,
                                        ])

                                        <div class="d-flex gap-2 mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="feather icon-save"></i> {{ __('dashboard.update') }}
                                            </button>
                                            <a href="{{ route('dashboard.roles.index') }}" class="btn btn-outline-secondary">
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
