@extends('base::components.dashboard.layouts.master')

@section('title', __('dashboard.dashboard'))

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{ __('dashboard.dashboard') }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section id="dashboard-analytics">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-center">
                                                <i class="feather icon-user-check primary font-large-2 float-left"></i>
                                            </div>
                                            <div class="media-body text-right">
                                                <h3>{{ $stats['admins'] }}</h3>
                                                <span>{{ __('dashboard.admins') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-center">
                                                <i class="feather icon-shield warning font-large-2 float-left"></i>
                                            </div>
                                            <div class="media-body text-right">
                                                <h3>{{ $stats['roles'] }}</h3>
                                                <span>{{ __('dashboard.roles') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-center">
                                                <i class="feather icon-users success font-large-2 float-left"></i>
                                            </div>
                                            <div class="media-body text-right">
                                                <h3>{{ $stats['users'] }}</h3>
                                                <span>{{ __('dashboard.users') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
