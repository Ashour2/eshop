@extends('layouts.app')
@section('title', 'إنشاء حساب جديد')
@section('content')

<div class="row justify-content-center">
<div class="col-md-6">

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div style="font-size:3rem">👤</div>
                <h3 class="fw-bold">إنشاء حساب جديد</h3>
                <p class="text-muted">انضم إلينا وابدأ التسوق الآن</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">الاسم الكامل</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="أدخل اسمك الكامل"
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">البريد الإلكتروني</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="example@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="8 أحرف على الأقل">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">تأكيد كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="أعد كتابة كلمة المرور">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">
                    <i class="bi bi-person-plus"></i> إنشاء الحساب
                </button>
            </form>

            <hr class="my-4">
            <p class="text-center text-muted mb-0">
                لديك حساب بالفعل؟
                <a href="{{ route('login') }}" class="fw-bold text-decoration-none">تسجيل الدخول</a>
            </p>
        </div>
    </div>

</div>
</div>
@endsection
