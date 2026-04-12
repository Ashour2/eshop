<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'لوحة التحكم')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #1a1a2e;
        }

        .sidebar .nav-link {
            color: #aaa;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, .1);
            border-radius: 8px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="d-flex">
        <div class="sidebar p-3" style="width:240px">
            <h5 class="text-white fw-bold mb-4">⚙️ لوحة التحكم</h5>
            <nav class="nav flex-column gap-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i
                        class="bi bi-speedometer2 me-2"></i>الرئيسية</a>
                <a href="{{ route('admin.products.index') }}"
                    class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i
                        class="bi bi-box-seam me-2"></i>المنتجات</a>

                <a href="{{ route('admin.coupons.index') }}"
                    class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated me-2"></i>الكوبونات
                </a>

                <a href="{{ route('admin.reviews.index') }}"
                    class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="bi bi-star me-2"></i>التقييمات
                </a>

                <a href="{{ route('admin.orders.index') }}"
                    class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i
                        class="bi bi-receipt me-2"></i>الطلبات</a>
                <hr class="border-secondary">
                <a href="{{ route('shop.index') }}" class="nav-link"><i class="bi bi-shop me-2"></i>المتجر</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="nav-link border-0 bg-transparent text-danger"><i
                            class="bi bi-box-arrow-left me-2"></i>خروج</button>
                </form>
            </nav>
        </div>
        <div class="flex-grow-1 p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button
                        type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
