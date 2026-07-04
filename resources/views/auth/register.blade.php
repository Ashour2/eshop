@extends('layouts.app')
@section('title', __('shop.create_account'))
@section('content')

<div class="row justify-content-center">
<div class="col-md-6">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div style="font-size:3rem">👤</div>
                <h3 class="fw-bold">{{ __('shop.create_account') }}</h3>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('shop.full_name') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autofocus>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('shop.email') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('shop.password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">{{ __('shop.confirm_password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">
                    <i class="bi bi-person-plus"></i> {{ __('shop.create_account') }}
                </button>
            </form>

            <hr class="my-4">
            <p class="text-center text-muted mb-0">
                {{ __('shop.already_have_account') }}
                <a href="{{ route('login') }}" class="fw-bold text-decoration-none">{{ __('shop.login') }}</a>
            </p>
        </div>
    </div>
</div>
</div>
@endsection
