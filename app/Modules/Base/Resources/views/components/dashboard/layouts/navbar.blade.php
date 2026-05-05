<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse d-flex flex-wrap justify-content-between align-items-center w-100"
                 id="navbar-mobile">
                <div class="bookmark-wrapper d-flex flex-wrap align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none">
                            <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
                                <i class="ficon feather icon-menu"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav bookmark-icons">
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link nav-link-expand" href="#">
                                <i class="ficon feather icon-maximize"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="d-flex flex-wrap align-items-center dashboard-header-actions">
                    <div class="dashboard-header-unified-bar d-flex align-items-stretch">
                        <div class="dashboard-header-unified-bar__profile d-flex align-items-center flex-grow-1">
                            <ul class="nav navbar-nav align-items-center mb-0 w-100">
                                <li class="dropdown dropdown-user nav-item w-100">
                                    <a class="dropdown-toggle nav-link dropdown-user-link dashboard-header-profile-inner"
                                       href="#" data-toggle="dropdown" aria-expanded="false">
                                        <span class="dashboard-header-profile-avatar">
                                            <img class="round" src="{{ asset('dashboardAssets/app-assets/images/logo/logo.png') }}"
                                                 alt="" width="40" height="40">
                                        </span>
                                        <div class="user-nav d-sm-flex d-none dashboard-header-profile-text">
                                            <span class="user-name text-bold-600">{{ auth('admin')->user()?->name ?? 'Admin' }}</span>
                                            <span class="user-status">{{ auth('admin')->user()?->email ?? '' }}</span>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dashboard-user-dropdown">
                                        <a class="dropdown-item dashboard-user-menu-item" href="{{ route('dashboard.profile.edit') }}">
                                            <i class="feather icon-user font-medium-4"></i>
                                            <span>{{ __('dashboard.Edit Profile') }}</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('dashboard.auth.logout') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item dashboard-user-menu-item">
                                                <i class="feather icon-power font-medium-4"></i>
                                                <span>{{ __('dashboard.Logout') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-header-unified-bar__notif d-flex align-items-center flex-shrink-0">
                            <ul class="nav navbar-nav align-items-center mb-0">
                                <li class="dropdown nav-item">
                                    <a class="dropdown-toggle dashboard-header-notif-btn"
                                       href="#"
                                       data-toggle="dropdown"
                                       aria-expanded="false"
                                       aria-label="Notifications">
                                        <i class="feather icon-bell" aria-hidden="true"></i>
                                        <span class="dashboard-header-notif-dot" aria-hidden="true"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dashboard-notif-menu">
                                        @php
                                            // Fake notifications (UI preview only)
                                            $fakeNotifications = [
                                                [
                                                    'icon' => 'icon-user',
                                                    'bg' => 'bg-light-primary',
                                                    'title' => dashboard_is_rtl() ? 'تم تحديث ملفك الشخصي' : 'Profile updated',
                                                    'time' => dashboard_is_rtl() ? 'منذ دقيقتين' : '2 min ago',
                                                ],
                                                [
                                                    'icon' => 'icon-shield',
                                                    'bg' => 'bg-light-warning',
                                                    'title' => dashboard_is_rtl() ? 'تسجيل دخول جديد' : 'New login detected',
                                                    'time' => dashboard_is_rtl() ? 'منذ 15 دقيقة' : '15 min ago',
                                                ],
                                                [
                                                    'icon' => 'icon-check-circle',
                                                    'bg' => 'bg-light-success',
                                                    'title' => dashboard_is_rtl() ? 'تم حفظ الصلاحيات بنجاح' : 'Permissions saved successfully',
                                                    'time' => dashboard_is_rtl() ? 'أمس' : 'Yesterday',
                                                ],
                                            ];
                                        @endphp

                                        <div class="dropdown-menu-header border-bottom px-2 py-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-bold-600">{{ dashboard_is_rtl() ? 'الإشعارات' : 'Notifications' }}</span>
                                                <span class="badge badge-pill badge-light-primary">{{ count($fakeNotifications) }}</span>
                                            </div>
                                        </div>

                                        <div class="dashboard-notif-list">
                                            @foreach ($fakeNotifications as $n)
                                                <a class="dropdown-item dashboard-notif-item" href="#">
                                                    <div class="media align-items-center">
                                                        <div class="media-left mr-75">
                                                            <div class="avatar {{ $n['bg'] }}">
                                                                <div class="avatar-content">
                                                                    <i class="feather {{ $n['icon'] }}"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="dashboard-notif-item__top">
                                                                <p class="mb-0 dashboard-notif-title">{{ $n['title'] }}</p>
                                                                <small class="text-muted dashboard-notif-time">{{ $n['time'] }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>

                                        <div class="dropdown-menu-footer border-top px-2 py-1 text-center">
                                            <a href="#" class="small text-primary">{{ dashboard_is_rtl() ? 'عرض الكل' : 'View all' }}</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-header-unified-bar__lang d-flex align-items-center flex-shrink-0">
                            @include('base::components.dashboard.language-switcher')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
