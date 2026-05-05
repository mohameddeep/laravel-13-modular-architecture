<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route('dashboard.home') }}">
                    <div class="brand-logo">
                        <img style="width: 40px" src="{{ asset('dashboardAssets/app-assets/images/logo/logo.png') }}">
                    </div>
                    <h2 class="brand-text mb-0">{{ config('app.name', 'Dashboard') }}</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>
                    <i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"
                       data-ticon="icon-disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>

    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            {{-- Dashboard Home --}}
            <li class="nav-item {{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
                <a href="{{ route('dashboard.home') }}">
                    <i class="feather icon-home"></i>
                    <span class="menu-title">{{ __('dashboard.dashboard') }}</span>
                </a>
            </li>

            {{-- Management section --}}
            <li class="navigation-header"><span>{{ __('dashboard.management') }}</span></li>

            {{-- Admins --}}
            @if (auth('admin')->check())
                <li class="nav-item {{ request()->routeIs('dashboard.admins.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.admins.index') }}">
                        <i class="feather icon-user-check"></i>
                        <span class="menu-title">{{ __('dashboard.admins') }}</span>
                    </a>
                </li>
            @endif

            {{-- Roles --}}
            @if (auth('admin')->check() && has_permission('roles-read'))
                <li class="nav-item {{ request()->routeIs('dashboard.roles.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.roles.index') }}">
                        <i class="feather icon-shield"></i>
                        <span class="menu-title">{{ __('dashboard.roles') }}</span>
                    </a>
                </li>
            @endif

            {{-- Permissions --}}
            @if (auth('admin')->check() && has_permission('permissions-read'))
                <li class="nav-item {{ request()->routeIs('dashboard.permissions.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.permissions.index') }}">
                        <i class="feather icon-lock"></i>
                        <span class="menu-title">{{ __('dashboard.permissions') }}</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</div>
