@extends('base::components.dashboard.layouts.master')

@section('title', __('dashboard.roles_list'))

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">

            @include('base::components.dashboard.layouts._breadcrumb', ['items' => [
                ['label' => __('dashboard.roles_list')],
            ]])

            <div class="content-body px-1 px-sm-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                                <h4 class="card-title mb-0">{{ __('dashboard.roles_list') }}</h4>
                                <a href="{{ route('dashboard.roles.create') }}" class="btn btn-primary btn-md">
                                    <i class="feather icon-plus mr-1"></i> {{ __('dashboard.add_role') }}
                                </a>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard p-2 p-sm-3">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('dashboard.name') }}</th>
                                                    <th>{{ __('dashboard.display_name_en') }}</th>
                                                    <th>{{ __('dashboard.display_name_ar') }}</th>
                                                    <th>{{ __('dashboard.permissions_count') }}</th>
                                                    <th>{{ __('dashboard.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($roles as $role)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><code>{{ $role->name }}</code></td>
                                                        <td>{{ $role->display_name_en ?? '-' }}</td>
                                                        <td>{{ $role->display_name_ar ?? '-' }}</td>
                                                        <td>
                                                            {{-- permissions_count loaded via withCount — no extra query --}}
                                                            <span class="badge badge-primary">{{ $role->permissions_count }}</span>
                                                        </td>
                                                        <td class="table-actions-cell text-nowrap">
                                                            <a href="{{ route('dashboard.roles.edit', $role->id) }}"
                                                               class="btn btn-sm btn-info table-action-btn"
                                                               title="{{ __('dashboard.edit') }}">
                                                                <i class="feather icon-edit"></i>
                                                            </a>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-danger table-action-btn"
                                                                    onclick="remove({{ $role->id }}, 'delete-role')"
                                                                    title="{{ __('dashboard.delete') }}">
                                                                <i class="feather icon-trash"></i>
                                                            </button>
                                                            <form class="delete-role-{{ $role->id }} d-none"
                                                                  action="{{ route('dashboard.roles.destroy', $role->id) }}"
                                                                  method="POST">
                                                                @csrf @method('DELETE')
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">{{ __('dashboard.no_data') }}</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-center">
                                        {{ $roles->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
