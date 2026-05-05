<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-textdirection="{{ dashboard_is_rtl() ? 'rtl' : 'ltr' }}">

<head>
    @php($cssBase = dashboard_css_bundle_dir())

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>{{ __('dashboard.login') }}</title>

    <link rel="apple-touch-icon" href="{{ asset('dashboardAssets/app-assets/images/logo/logo.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('dashboardAssets/app-assets/images/logo/logo.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboardAssets/app-assets/vendors/css/vendors'.dashboard_vendors_rtl_suffix().'.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/themes/semi-dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/'.$cssBase.'/pages/authentication.css') }}">
    @if (dashboard_is_rtl())
        <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/app-assets/css-rtl/custom-rtl.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/assets/css/style-rtl.css') }}">
    @else
        <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets/assets/css/style.css') }}">
    @endif
    {{-- Theme ships vuexy-login-bg.jpg with stray text burned in; use a clean gradient instead --}}
    <style>
        html body.bg-full-screen-image {
            background-image: none;
            background: linear-gradient(145deg, #e8ecf4 0%, #eef1f8 45%, #e4eaf3 100%) fixed;
        }
    </style>
</head>

<body class="vertical-layout vertical-menu-modern 1-column navbar-floating footer-static bg-full-screen-image menu-collapsed blank-page"
      data-open="click" data-menu="vertical-menu-modern" data-col="1-column">

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <section class="row flexbox-container">
                    <div class="col-xl-8 col-11 d-flex flex-wrap justify-content-center">
                        <div class="card bg-authentication rounded-0 mb-0">
                            <div class="row m-0">
                                <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                                    <img src="{{ asset('dashboardAssets/app-assets/images/pages/login.png') }}" alt="branding logo">
                                </div>
                                <div class="col-lg-6 col-12 p-0">
                                    <div class="card rounded-0 mb-0 px-2">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="mb-0">{{ __('dashboard.login') }}</h4>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body pt-1">
                                                @if (session('status'))
                                                    <div class="alert alert-success mb-1" role="alert">{{ session('status') }}</div>
                                                @endif
                                                <form action="{{ route('dashboard.auth.login.attempt') }}" method="POST">
                                                    @csrf
                                                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                        <input type="email" class="form-control" id="user-email"
                                                               name="email" value="{{ old('email') }}"
                                                               placeholder="{{ __('dashboard.email') }}" required>
                                                        <div class="form-control-position">
                                                            <i class="feather icon-user"></i>
                                                        </div>
                                                        <label for="user-email">{{ __('dashboard.email') }}</label>
                                                        @error('email')
                                                            <span class="text text-danger font-size-xsmall">{{ $message }}</span>
                                                        @enderror
                                                    </fieldset>

                                                    <fieldset class="form-label-group position-relative has-icon-left">
                                                        <input type="password" class="form-control" id="user-password"
                                                               name="password"
                                                               placeholder="{{ __('dashboard.password') }}" required>
                                                        <div class="form-control-position">
                                                            <i class="feather icon-lock"></i>
                                                        </div>
                                                        <label for="user-password">{{ __('dashboard.password') }}</label>
                                                        @error('password')
                                                            <span class="text text-danger font-size-xsmall">{{ $message }}</span>
                                                        @enderror
                                                    </fieldset>

                                                    @if (session('error'))
                                                        <div class="text text-danger font-size-xsmall mb-1">{{ session('error') }}</div>
                                                    @endif

                                                    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="1">
                                                            <label class="custom-control-label" for="remember">{{ __('dashboard.remember_me') }}</label>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary btn-inline mb-2 form-control">
                                                        {{ __('dashboard.login') }}
                                                    </button>
                                                </form>
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

    <script src="{{ asset('dashboardAssets/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('dashboardAssets/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('dashboardAssets/app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('dashboardAssets/app-assets/js/scripts/components.js') }}"></script>
</body>

</html>
