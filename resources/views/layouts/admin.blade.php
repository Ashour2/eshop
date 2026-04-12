<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'لوحة التحكم')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
    <style>
        .sidebar { min-height:100vh; background:#1a1a2e; width:240px; flex-shrink:0; }
        .sidebar .nav-link { color:#aaa; border-radius:8px; padding:10px 14px; }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { color:#fff; background:rgba(255,255,255,.1); }
        .sidebar .nav-link .badge { font-size:.65rem; }
        .main-content { flex:1; min-height:100vh; background:#f4f6f9; }
        .topbar { background:#fff; border-bottom:1px solid #eee;
                  padding:12px 24px; display:flex;
                  justify-content:space-between; align-items:center; }
    </style>
</head>
<body>
<div class="d-flex">

    {{-- ── Sidebar ── --}}
    <div class="sidebar p-3">
        <h5 class="text-white fw-bold mb-4 px-2">⚙️ لوحة التحكم</h5>
        <nav class="nav flex-column gap-1">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>الرئيسية
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-2"></i>المنتجات
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt me-2"></i>الطلبات
            </a>
            <a href="{{ route('admin.coupons.index') }}"
               class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated me-2"></i>الكوبونات
            </a>
            <a href="{{ route('admin.reviews.index') }}"
               class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star me-2"></i>التقييمات
            </a>
            <a href="{{ route('admin.notifications.index') }}"
               class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell me-2"></i>الإشعارات
                @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                @if($unread > 0)
                    <span class="badge bg-danger float-start mt-1">{{ $unread }}</span>
                @endif
            </a>

            <hr class="border-secondary my-2">

            <a href="{{ route('shop.index') }}" class="nav-link" target="_blank">
                <i class="bi bi-shop me-2"></i>المتجر
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="nav-link border-0 bg-transparent text-danger w-100 text-start">
                    <i class="bi bi-box-arrow-left me-2"></i>خروج
                </button>
            </form>
        </nav>
    </div>

    {{-- ── Main Content ── --}}
    <div class="main-content">

        {{-- Topbar --}}
        <div class="topbar">
            <div class="text-muted small">
                {{ now()->format('l، d F Y') }}
            </div>
            <div class="d-flex align-items-center gap-3">

                {{-- جرس الإشعارات --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm position-relative"
                            type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        @if($unread > 0)
                            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                                {{ $unread }}
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start shadow" style="width:320px;max-height:400px;overflow-y:auto">
                        <li class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom">
                            <span class="fw-bold">الإشعارات</span>
                            <a href="{{ route('admin.notifications.index') }}" class="small text-primary">عرض الكل</a>
                        </li>
                        @forelse(auth()->user()->unreadNotifications->take(5) as $n)
                        <li>
                            <a href="{{ route('admin.notifications.read', $n->id) }}"
                               class="dropdown-item py-3 border-bottom">
                                <div class="d-flex gap-2">
                                    <span class="fs-5">{{ $n->data['icon'] ?? '🔔' }}</span>
                                    <div>
                                        <div class="fw-bold small">{{ $n->data['title'] }}</div>
                                        <div class="text-muted" style="font-size:.78rem">{{ $n->data['message'] }}</div>
                                        <div class="text-muted" style="font-size:.72rem">{{ $n->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @empty
                        <li class="text-center text-muted py-4 small">لا توجد إشعارات جديدة</li>
                        @endforelse
                    </ul>
                </div>

                {{-- معلومات الأدمن --}}
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                         style="width:36px;height:36px">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold small">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size:.75rem">مدير النظام</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <div class="p-4">
            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
