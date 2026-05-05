{{--
    Password input with eye toggle. Expects: name, optional id, inputClass, required, autocomplete, value
--}}
@php
    $inputId = $id ?? $name;
    $autocomplete = $autocomplete ?? 'new-password';
@endphp
<div class="position-relative dashboard-password-reveal"
     data-show-label="{{ __('dashboard.show_password') }}"
     data-hide-label="{{ __('dashboard.hide_password') }}">
    <input type="password"
           name="{{ $name }}"
           id="{{ $inputId }}"
           class="form-control dashboard-password-reveal__input {{ $inputClass ?? '' }}"
           @if (! empty($required)) required @endif
           autocomplete="{{ $autocomplete }}"
           @isset($value) value="{{ $value }}" @endisset>
    <button type="button"
            class="dashboard-password-reveal__toggle"
            data-password-toggle
            aria-label="{{ __('dashboard.show_password') }}"
            aria-pressed="false">
        <i class="feather icon-eye" data-toggle-icon aria-hidden="true"></i>
    </button>
</div>

@once
    @push('styles')
        <style>
            .dashboard-password-reveal__input { padding-inline-end: 2.75rem; }
            .dashboard-password-reveal__toggle {
                position: absolute;
                inset-inline-end: 0.125rem;
                top: 50%;
                transform: translateY(-50%);
                padding: 0.35rem 0.55rem;
                line-height: 1;
                color: #6e6b7b;
                border: 0;
                background: transparent;
                border-radius: 0.25rem;
                cursor: pointer;
            }
            .dashboard-password-reveal__toggle:hover { color: #7367f0; }
            .dashboard-password-reveal__toggle:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(115, 103, 240, 0.25);
            }
            .dashboard-password-reveal__input.is-invalid { padding-inline-end: 3.1rem; }
            .dashboard-password-reveal__input.is-invalid + .dashboard-password-reveal__toggle {
                background: #fff;
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-password-toggle]');
                if (!btn) return;
                var wrap = btn.closest('.dashboard-password-reveal');
                if (!wrap) return;
                var input = wrap.querySelector('.dashboard-password-reveal__input');
                var icon = btn.querySelector('[data-toggle-icon]');
                if (!input || !icon) return;
                var showL = wrap.getAttribute('data-show-label') || 'Show password';
                var hideL = wrap.getAttribute('data-hide-label') || 'Hide password';
                var concealed = input.getAttribute('type') === 'password';
                input.setAttribute('type', concealed ? 'text' : 'password');
                icon.classList.remove('icon-eye', 'icon-eye-off');
                icon.classList.add(concealed ? 'icon-eye-off' : 'icon-eye');
                btn.setAttribute('aria-label', concealed ? hideL : showL);
                btn.setAttribute('aria-pressed', concealed ? 'true' : 'false');
            });
        </script>
    @endpush
@endonce
