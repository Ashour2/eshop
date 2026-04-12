@extends('layouts.app')
@section('title', 'تسجيل الدخول')
@section('content')
<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white text-center fw-bold">تسجيل الدخول</div>
        <div class="card-body">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">تذكرني</label>
                </div>
                <button type="submit" class="btn btn-dark w-100">دخول</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
