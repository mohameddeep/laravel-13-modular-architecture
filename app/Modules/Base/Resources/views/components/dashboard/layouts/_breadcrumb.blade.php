{{--
    Breadcrumb partial.
    Usage: @include('base::components.dashboard.layouts._breadcrumb', ['items' => [
        ['label' => __('dashboard.roles_list'), 'route' => route('dashboard.roles.index')],
        ['label' => __('dashboard.add_role')],  // no 'route' = active (last item)
    ]])
--}}
<section class="px-1 px-sm-2">
    <div class="row"><div class="col-12">
        <div class="card"><div class="card-content"><div class="card-body py-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard.home') }}">{{ __('dashboard.dashboard') }}</a>
                    </li>
                    @foreach ($items as $item)
                        @if (isset($item['route']))
                            <li class="breadcrumb-item">
                                <a href="{{ $item['route'] }}">{{ $item['label'] }}</a>
                            </li>
                        @else
                            <li class="breadcrumb-item active">{{ $item['label'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div></div></div>
    </div></div>
</section>
