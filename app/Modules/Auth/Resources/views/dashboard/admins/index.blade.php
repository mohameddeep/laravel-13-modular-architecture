@extends('base::components.dashboard.layouts.master')

@section('title', __('dashboard.admins'))

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">

            @include('base::components.dashboard.layouts._breadcrumb', ['items' => [
                ['label' => __('dashboard.admins')],
            ]])

            <div class="content-body px-1 px-sm-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                                <h4 class="card-title mb-0">{{ __('dashboard.admins') }}</h4>
                                <a href="{{ route('dashboard.admins.create') }}" class="btn btn-primary btn-md">
                                    <i class="feather icon-plus mr-1"></i> {{ __('dashboard.add_admin') }}
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
                                                    <th>{{ __('dashboard.email') }}</th>
                                                    <th>{{ __('dashboard.created_at') }}</th>
                                                    <th>{{ __('dashboard.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($items as $admin)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $admin->name }}</td>
                                                        <td>{{ $admin->email }}</td>
                                                        <td>{{ $admin->created_at?->format('Y-m-d') }}</td>
                                                        <td class="table-actions-cell text-nowrap">
                                                            <a href="{{ route('dashboard.admins.edit', $admin->id) }}"
                                                               class="btn btn-sm btn-info table-action-btn"
                                                               title="{{ __('dashboard.edit') }}">
                                                                <i class="feather icon-edit"></i>
                                                            </a>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-danger table-action-btn"
                                                                    onclick="remove({{ $admin->id }}, 'delete-admin')"
                                                                    title="{{ __('dashboard.delete') }}">
                                                                <i class="feather icon-trash"></i>
                                                            </button>
                                                            <form class="delete-admin-{{ $admin->id }} d-none"
                                                                  action="{{ route('dashboard.admins.destroy', $admin->id) }}"
                                                                  method="POST">
                                                                @csrf @method('DELETE')
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">{{ __('dashboard.no_data') }}</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-center">
                                        {{ $items->links() }}
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
