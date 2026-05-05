<footer class="footer footer-static footer-light">
    <p class="clearfix blue-grey lighten-2 mb-0">
        <span class="float-md-left d-block d-md-inline-block mt-25">
            COPYRIGHT &copy; {{ date('Y') }}
            <a class="text-bold-800 grey darken-2" href="#" target="_blank">{{ config('app.name') }}</a>,
            All rights Reserved
        </span>
        <button class="btn btn-primary btn-icon scroll-top" type="button">
            <i class="feather icon-arrow-up"></i>
        </button>
    </p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
    integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- Vendor JS --}}
<script src="{{ asset('dashboardAssets/app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('dashboardAssets/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('dashboardAssets/app-assets/vendors/js/extensions/tether.min.js') }}"></script>
<script src="{{ asset('dashboardAssets/app-assets/vendors/js/extensions/shepherd.min.js') }}"></script>
<script src="{{ asset('dashboardAssets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
<script src="{{ asset('dashboardAssets/app-assets/vendors/js/extensions/swiper.min.js') }}"></script>

<script src="{{ asset('dashboardAssets/app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('dashboardAssets/app-assets/js/core/app.js') }}"></script>
<script src="{{ asset('dashboardAssets/app-assets/js/scripts/components.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"
    integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('dashboardAssets/app-assets/js/iziToast.min.js') }}"></script>

@php
    $toastMap = [
        'success' => 'success',
        'error'   => 'error',
        'danger'  => 'error',
        'warning' => 'warning',
        'info'    => 'info',
    ];
@endphp
@foreach ($toastMap as $sessionKey => $toastType)
    @if (session()->has($sessionKey))
        <script>
            iziToast.{{ $toastType }}({
                title: '',
                position: 'topLeft',
                message: {!! json_encode(session()->get($sessionKey)) !!}
            });
        </script>
    @endif
@endforeach

<input type="hidden" id="areYouSure" value="{{ __('dashboard.Are you sure ?') }}">
<input type="hidden" id="yesDelete" value="{{ __('dashboard.Yes, delete') }}">
<input type="hidden" id="noCancel" value="{{ __('dashboard.No, cancel') }}">
<input type="hidden" id="deletedDone" value="{{ __('dashboard.deleted done') }}">
<input type="hidden" id="deleteMessage" value="{{ __('dashboard.delete') }}">

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    function remove(id, form) {
        Swal.fire({
            title: $('#areYouSure').val(),
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: $('#yesDelete').val(),
            cancelButtonText: $('#noCancel').val()
        }).then((result) => {
            if (result.isConfirmed) {
                $(`.${form}-${id}`).submit();
            }
        });
    }
</script>
@yield('script')
@stack('scripts')
