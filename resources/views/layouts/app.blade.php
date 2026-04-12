<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'المتجر الإلكتروني')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('shop.index') }}">🛒 متجرنا</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.index') ? 'active' : '' }}"
                       href="{{ route('shop.index') }}">الرئيسية</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">

                {{-- السلة --}}
                <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-sm position-relative">
                    <i class="bi bi-cart3"></i>
                    @if(count(session('cart', [])) > 0)
                        <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                            {{ count(session('cart', [])) }}
                        </span>
                    @endif
                    السلة
                </a>

                {{-- المفضلة --}}
                @auth
                    @if(!auth()->user()->is_admin)
                    <a href="{{ route('wishlist.index') }}" class="btn btn-outline-light btn-sm position-relative">
                        <i class="bi bi-heart"></i>
                        @if(auth()->user()->wishlistCount() > 0)
                            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->wishlistCount() }}
                            </span>
                        @endif
                        المفضلة
                    </a>
                    @endif
                @endauth

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-speedometer2"></i> لوحة التحكم
                        </a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle"
                                    type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li>
                                    <a class="dropdown-item" href="{{ route('account.index') }}">
                                        <i class="bi bi-person me-2"></i> حسابي
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('account.orders') }}">
                                        <i class="bi bi-receipt me-2"></i> طلباتي
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('wishlist.index') }}">
                                        <i class="bi bi-heart me-2"></i> مفضلتي
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-left me-2"></i> تسجيل الخروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-in-right"></i> دخول
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-person-plus"></i> حساب جديد
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid px-0">
    @foreach(['success','error'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg === 'success' ? 'success' : 'danger' }} alert-dismissible fade show rounded-0 mb-0">
                <div class="container">
                    <i class="bi bi-{{ $msg === 'success' ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
                    {{ session($msg) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
    @endforeach
</div>

<div class="container py-4">
    @yield('content')
</div>

<footer class="bg-dark text-white mt-5 py-4">
    <div class="container text-center">
        <p class="mb-0 opacity-75">© {{ date('Y') }} متجرنا الإلكتروني — جميع الحقوق محفوظة</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

<script>
// ── حفظ واستعادة موضع الصفحة ─────────────────────────────
document.addEventListener('DOMContentLoaded', function () {

    // استعادة الموضع بعد الـ reload
    const scrollPos = sessionStorage.getItem('scrollPos');
    if (scrollPos) {
        window.scrollTo({ top: parseInt(scrollPos), behavior: 'instant' });
        sessionStorage.removeItem('scrollPos');
    }

    // حفظ الموضع عند أي submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            sessionStorage.setItem('scrollPos', window.scrollY);
        });
    });

});
</script>

</body>
</html>
