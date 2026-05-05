<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <link rel="apple-touch-icon" href="{{ asset('dashboardAssets/app-assets/images/logo/logo.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('dashboardAssets/app-assets/images/logo/logo.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    {{-- Vendor CSS --}}
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboardAssets/app-assets/vendors/css/vendors'.dashboard_vendors_rtl_suffix().'.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/vendors/css/extensions/tether-theme-arrows.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/vendors/css/extensions/tether.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/vendors/css/extensions/shepherd-theme-default.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/vendors/css/extensions/swiper.min.css') }}">

    {{-- Theme CSS (RTL aware) --}}
    @php($cssBase = dashboard_css_bundle_dir())
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/themes/semi-dark-layout.css') }}">

    {{-- Page CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/pages/dashboard-analytics.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/pages/card-analytics.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/plugins/tour/tour.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/pages/app-user.css') }}">

    {{-- Custom CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/custom-rtl.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboardAssets/assets/css/'.(dashboard_is_rtl() ? 'style-rtl.css' : 'style.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/css/iziToast.min.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/css/pickers/pickadate/pickadate.css') }}">

    <style>
        .table-action-btn { min-width: 34px; }
        .table-actions-cell {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: center;
        }

        /* Unified header bar: profile + language in one white rounded panel */
        .header-navbar .dashboard-header-actions {
            margin-inline-start: auto;
        }
        .header-navbar .dashboard-header-unified-bar {
            background: #fff;
            border: 1px solid rgba(34, 41, 47, 0.07);
            border-radius: 0.65rem;
            box-shadow: 0 2px 12px rgba(34, 41, 47, 0.09);
            padding: 0.65rem 0.85rem;
            min-height: 3.25rem;
            max-width: 100%;
        }
        html[dir="rtl"] .header-navbar .dashboard-header-unified-bar {
            flex-direction: row-reverse;
            padding: 0.65rem 0.85rem;
        }
        .header-navbar .dashboard-header-unified-bar__profile {
            min-width: 0;
        }
        .header-navbar .dashboard-header-unified-bar__profile .nav-item {
            width: 100%;
        }
        .header-navbar .dashboard-header-unified-bar__lang {
            border-inline-start: 1px solid rgba(34, 41, 47, 0.1);
            padding-inline-start: 0.85rem;
            margin-inline-start: 0.65rem;
        }
        .header-navbar .dashboard-header-unified-bar__notif {
            border-inline-start: 1px solid rgba(34, 41, 47, 0.1);
            padding-inline-start: 0.85rem;
            margin-inline-start: 0.65rem;
        }
        html[dir="rtl"] .header-navbar .dashboard-header-unified-bar__lang {
            border-inline-start: none;
            border-inline-end: 1px solid rgba(34, 41, 47, 0.1);
            padding-inline-start: 0;
            padding-inline-end: 0.85rem;
            margin-inline-start: 0;
            margin-inline-end: 0.65rem;
        }
        html[dir="rtl"] .header-navbar .dashboard-header-unified-bar__notif {
            border-inline-start: none;
            border-inline-end: 1px solid rgba(34, 41, 47, 0.1);
            padding-inline-start: 0;
            padding-inline-end: 0.85rem;
            margin-inline-start: 0;
            margin-inline-end: 0.65rem;
        }
        .header-navbar .dashboard-header-notif-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.35rem;
            height: 2.6rem;
            color: #5e5873;
            text-decoration: none;
            border-radius: 0.45rem;
        }
        .header-navbar .dashboard-header-notif-btn:hover,
        .header-navbar .dashboard-header-notif-btn:focus {
            background: rgba(34, 41, 47, 0.04);
            color: #3d3b45;
            outline: none;
        }
        .header-navbar .dashboard-header-notif-btn .feather {
            font-size: 1.15rem;
            opacity: 0.9;
        }
        .header-navbar .dashboard-header-notif-dot {
            position: absolute;
            top: 0.45rem;
            inset-inline-end: 0.55rem;
            width: 0.55rem;
            height: 0.55rem;
            background: #ea5455;
            border: 2px solid #fff;
            border-radius: 999px;
        }
        .header-navbar .dashboard-notif-menu {
            min-width: 19rem;
            padding: 0;
            margin-top: 0.35rem;
            border: 1px solid rgba(34, 41, 47, 0.06);
            border-radius: 0.5rem;
            box-shadow: 0 4px 18px rgba(34, 41, 47, 0.12);
            overflow: hidden;
        }
        .header-navbar .dashboard-notif-menu .dropdown-menu-header,
        .header-navbar .dashboard-notif-menu .dropdown-menu-footer {
            background: #fff;
        }
        .header-navbar .dashboard-notif-list {
            max-height: 26rem;
            overflow: auto;
        }
        .header-navbar .dashboard-notif-item {
            padding: 0.8rem 0.95rem;
            margin: 0;
            border-bottom: 1px solid rgba(34, 41, 47, 0.06);
        }
        .header-navbar .dashboard-notif-list .dashboard-notif-item:last-child {
            border-bottom: 0;
        }
        .header-navbar .dashboard-notif-item:hover {
            background: #f6f6f9;
        }
        .header-navbar .dashboard-notif-title {
            font-size: 0.92rem;
            color: #5e5873;
            line-height: 1.2;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 12.5rem;
        }
        .header-navbar .dashboard-notif-item__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .header-navbar .dashboard-notif-time {
            font-size: 0.72rem;
            white-space: nowrap;
            opacity: 0.9;
        }
        .header-navbar .dashboard-notif-menu .avatar {
            width: 2.15rem;
            height: 2.15rem;
        }
        .header-navbar .dashboard-notif-menu .avatar .feather {
            font-size: 1.05rem;
        }
        .header-navbar .dashboard-notif-menu .dropdown-menu-header {
            padding: 0.75rem 0.95rem !important;
        }
        .header-navbar .dashboard-notif-menu .dropdown-menu-footer {
            padding: 0.55rem 0.95rem !important;
        }
        .header-navbar .dashboard-notif-menu .dropdown-menu-footer a {
            text-decoration: none;
            font-weight: 600;
        }
        /* nicer scrollbar */
        .header-navbar .dashboard-notif-list::-webkit-scrollbar { width: 8px; }
        .header-navbar .dashboard-notif-list::-webkit-scrollbar-track { background: transparent; }
        .header-navbar .dashboard-notif-list::-webkit-scrollbar-thumb {
            background: rgba(34, 41, 47, 0.18);
            border-radius: 99px;
        }
        .header-navbar .dashboard-notif-list::-webkit-scrollbar-thumb:hover {
            background: rgba(34, 41, 47, 0.28);
        }
        html[dir="rtl"] .header-navbar .dashboard-notif-menu .media-left {
            margin-right: 0 !important;
            margin-left: 0.75rem !important;
        }
        .header-navbar .dropdown-user-link.dashboard-header-profile-inner {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.35rem 0.35rem !important;
            margin: 0;
            background: transparent !important;
            border: none !important;
            border-radius: 0;
            box-shadow: none !important;
            color: #5e5873;
        }
        html[dir="rtl"] .header-navbar .dropdown-user-link.dashboard-header-profile-inner {
            flex-direction: row-reverse;
        }
        .header-navbar .dropdown-user-link.dashboard-header-profile-inner:hover,
        .header-navbar .dropdown-user-link.dashboard-header-profile-inner:focus {
            color: #2c2c2c;
        }
        .header-navbar .dashboard-header-profile-avatar img {
            box-shadow: 0 2px 6px rgba(34, 41, 47, 0.12);
        }
        .header-navbar .dashboard-header-profile-text .user-name {
            font-size: 0.95rem;
            line-height: 1.25;
            color: #2c2c2c;
        }
        .header-navbar .dashboard-header-profile-text .user-status {
            font-size: 0.78rem;
            color: #8e8e99;
            max-width: 14rem;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .header-navbar .dashboard-header-unified-bar .dashboard-lang-wrap--embedded {
            margin: 0;
        }
        .header-navbar .dashboard-header-unified-bar .dashboard-lang-chip {
            gap: 0.4rem;
            padding: 0.45rem 0.45rem !important;
            margin: 0;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            border-radius: 0;
            color: #5e5873 !important;
            font-size: 0.92rem;
        }
        .header-navbar .dashboard-header-unified-bar .dashboard-lang-chip:hover,
        .header-navbar .dashboard-header-unified-bar .dashboard-lang-chip:focus {
            color: #3d3b45 !important;
        }
        .header-navbar .dashboard-header-unified-bar .dashboard-lang-chip .feather {
            font-size: 1.05rem;
            opacity: 0.88;
        }
        .header-navbar .dashboard-lang-menu {
            min-width: 10.5rem;
            padding: 0.4rem 0;
            margin-top: 0.35rem;
            border: 1px solid rgba(34, 41, 47, 0.06);
            border-radius: 0.5rem;
            box-shadow: 0 4px 18px rgba(34, 41, 47, 0.12);
        }
        .header-navbar .dashboard-lang-menu .dropdown-item {
            padding: 0.55rem 1rem;
            border-radius: 0.35rem;
            margin: 0 0.35rem;
            font-size: 0.92rem;
            color: #5e5873;
        }
        .header-navbar .dashboard-lang-menu .dropdown-item:hover {
            background: #eef1f5;
            color: #3d4a5c;
        }
        .header-navbar .dashboard-lang-menu .dropdown-item.active {
            background: #8b9aad !important;
            color: #fff !important;
        }
        .header-navbar .navbar-container ul.nav li.dropdown-language .dashboard-lang-menu.dropdown-menu-right,
        .header-navbar .navbar-container ul.nav .dashboard-lang-wrap .dashboard-lang-menu.dropdown-menu-right {
            inset-inline-end: 0.35rem;
            inset-inline-start: auto !important;
        }
        .header-navbar .navbar-container ul.nav li.dropdown-user .dropdown-menu.dashboard-user-dropdown {
            min-width: 13rem;
            padding-top: 0.35rem;
            padding-bottom: 0.35rem;
            border: 1px solid rgba(34, 41, 47, 0.06);
            border-radius: 0.5rem;
            box-shadow: 0 4px 18px rgba(34, 41, 47, 0.12);
        }
        .header-navbar .navbar-container ul.nav li.dropdown-user .dropdown-menu.dropdown-menu-right {
            inset-inline-end: 0.75rem;
            inset-inline-start: auto !important;
        }
        .header-navbar .navbar-container ul.nav li a.dropdown-user-link .user-nav {
            float: none;
            align-items: flex-start;
        }
        html[dir="ltr"] .header-navbar .navbar-container ul.nav li a.dropdown-user-link .user-nav {
            align-items: flex-end;
        }
        html[dir="rtl"] .header-navbar .navbar-container ul.nav li a.dropdown-user-link .user-nav {
            text-align: right;
        }
        html[dir="ltr"] .header-navbar .navbar-container ul.nav li a.dropdown-user-link .user-nav {
            text-align: left;
        }
        .header-navbar .dropdown-user .dashboard-user-menu-item {
            display: flex !important;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            white-space: nowrap;
            border: 0;
            background: transparent;
            text-align: inherit;
            cursor: pointer;
            font: inherit;
            color: inherit;
        }
        .header-navbar .dropdown-user .dashboard-user-menu-item .feather {
            flex-shrink: 0;
            opacity: 0.85;
        }
    </style>
    @yield('styles')
    @stack('styles')
</head>
