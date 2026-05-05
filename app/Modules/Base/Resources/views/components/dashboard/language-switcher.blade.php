{{--
    Dashboard navbar language switcher (mcamara/laravel-localization).
--}}
@php
    $locales = dashboard_supported_locales();
    $current = app()->getLocale();
    $rtl = dashboard_is_rtl();
@endphp

<ul class="nav navbar-nav align-items-center dashboard-lang-wrap dashboard-lang-wrap--embedded mb-0">
    <li class="dropdown nav-item">
        <a class="nav-link dropdown-toggle dashboard-lang-trigger dashboard-lang-chip py-0 d-flex align-items-center"
           href="#" data-toggle="dropdown" aria-expanded="false">
            @if ($rtl)
                <span class="dashboard-lang-label">{{ __('dashboard.language') }}</span>
                <i class="feather icon-globe"></i>
            @else
                <i class="feather icon-globe"></i>
                <span class="dashboard-lang-label">{{ __('dashboard.language') }}</span>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right dashboard-lang-menu">
            @foreach ($locales as $localeCode => $properties)
                <a class="dropdown-item @if ($localeCode === $current) active @endif"
                   href="{{ dashboard_localized_url($localeCode) }}">
                    {{ $properties['native'] }}
                </a>
            @endforeach
        </div>
    </li>
</ul>
