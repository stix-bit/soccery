@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-4 p-md-5 rounded-4 d-flex align-items-center justify-content-center" style="background: linear-gradient(90deg, #6b21a8, #a855f7); min-height: 80vh;">
        <div class="w-100" style="max-width: 600px;">
            <div class="card rounded-4 shadow-sm" style="border: 1px solid #000; overflow: hidden; min-height: 600px;">
                <div class="card-header bg-white text-center border-bottom"><h5 class="mb-0 fw-bold">{{ __('Register') }}</h5></div>

                <div class="card-body d-flex flex-column justify-content-center">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name">
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">{{ __('Profile Photo') }}</label>
                            <div class="mb-2 d-flex align-items-center gap-3 justify-content-center">
                                <img
                                    id="photoPreview"
                                    alt="Profile preview"
                                    style="display:none;width:64px;height:64px;object-fit:cover;border-radius:9999px;"
                                >
                                <span id="photoPreviewHint" class="text-muted small">{{ __('Choose an image to preview it.') }}</span>
                            </div>
                            <input id="photo" type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept="image/*">
                            @error('photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const input = document.getElementById('photo');
        const preview = document.getElementById('photoPreview');
        const hint = document.getElementById('photoPreviewHint');
        if (!input || !preview) return;

        input.addEventListener('change', function () {
            const file = input.files && input.files[0];
            if (!file) {
                preview.style.display = 'none';
                if (hint) hint.style.display = '';
                preview.removeAttribute('src');
                return;
            }

            const url = URL.createObjectURL(file);
            preview.src = url;
            preview.style.display = '';
            if (hint) hint.style.display = 'none';

            preview.onload = function () {
                URL.revokeObjectURL(url);
            };
        });
    })();
</script>
@endsection
