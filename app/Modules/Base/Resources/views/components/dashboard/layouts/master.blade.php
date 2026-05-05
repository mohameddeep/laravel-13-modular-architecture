<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ dashboard_is_rtl() ? 'rtl' : 'ltr' }}">
@include('base::components.dashboard.layouts.head')

<body class="vertical-layout vertical-menu-modern 2-columns navbar-floating footer-static menu-collapsed"
      data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('base::components.dashboard.layouts.navbar')

    @include('base::components.dashboard.layouts.sidebar')

    @yield('content')

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('base::components.dashboard.layouts.footer')

</body>

</html>
