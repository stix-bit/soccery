@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Profile') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">{{ __('Current Photo') }}</label>
                            <div class="col-md-6 d-flex align-items-center gap-3">
                                @if ($user->img_path)
                                    <img
                                        src="{{ asset('storage/' . $user->img_path) }}"
                                        alt="{{ $user->first_name }} {{ $user->last_name }}"
                                        style="width:64px;height:64px;object-fit:cover;border-radius:9999px;"
                                    >
                                @else
                                    <div style="width:64px;height:64px;border-radius:9999px;background:#e5e7eb;"></div>
                                @endif
                                <span class="text-muted">{{ __('Upload a new photo below to replace it.') }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="photo" class="col-md-4 col-form-label text-md-end">{{ __('New Profile Photo') }}</label>
                            <div class="col-md-6">
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img
                                        id="newPhotoPreview"
                                        alt="New photo preview"
                                        style="display:none;width:64px;height:64px;object-fit:cover;border-radius:9999px;"
                                    >
                                    <span id="newPhotoPreviewHint" class="text-muted small">{{ __('Choose an image to preview it.') }}</span>
                                </div>
                                <input id="photo" type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept="image/*">
                                @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>
                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user->first_name) }}" required autocomplete="first_name" autofocus>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>
                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user->last_name) }}" required autocomplete="last_name">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save Changes') }}
                                </button>
                                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
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
        const preview = document.getElementById('newPhotoPreview');
        const hint = document.getElementById('newPhotoPreviewHint');
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
