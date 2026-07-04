@extends('layouts.app')
@section('title', __('shop.login'))
@section('content')

<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div style="font-size:3rem">🔐</div>
                <h3 class="fw-bold">{{ __('shop.welcome_back') }}</h3>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('shop.email') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('shop.password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">{{ __('shop.remember_me') }}</label>
                </div>

                <button type="submit" class="btn btn-dark w-100 btn-lg">{{ __('shop.login') }}</button>
            </form>

            <hr class="my-4">
            <p class="text-center text-muted mb-0">
                {{ __('shop.no_account') }}
                <a href="{{ route('register') }}" class="fw-bold text-decoration-none">{{ __('shop.register') }}</a>
            </p>
        </div>
    </div>
</div>
</div>
@endsection
