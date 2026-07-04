<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'لوحة التحكم') — RoyaTech</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    @stack('styles')
    <style>
        .admin-sidebar {
            width: 250px;
            flex-shrink: 0;
            background: var(--rt-navy);
            border-left: 2px solid var(--rt-orange);
            padding: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .admin-sidebar .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid var(--rt-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-sidebar .sidebar-brand svg { filter: drop-shadow(0 0 4px rgba(232,97,26,.3)); }
        .admin-sidebar .sidebar-brand span { color: #fff; font-weight: 700; font-size: 1.1rem; }
        .admin-sidebar .sidebar-brand .brand-accent { color: var(--rt-orange); }
        .admin-sidebar .nav-link {
            color: var(--rt-text-muted);
            border-radius: 8px;
            padding: 10px 14px;
            margin: 2px 12px;
            transition: all .2s;
        }
        .admin-sidebar .nav-link:hover {
            color: #fff;
            background: var(--rt-navy-lighter);
        }
        .admin-sidebar .nav-link.active {
            color: #fff;
            background: var(--rt-orange);
        }
        .admin-sidebar .nav-link .badge { font-size: .65rem; }
        .admin-main { flex: 1; min-height: 100vh; background: var(--rt-body-bg); }
        .admin-topbar {
            background: var(--rt-navy);
            border-bottom: 1px solid var(--rt-border);
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="d-flex">

    {{-- ── Sidebar ── --}}
    <div class="admin-sidebar">
        <div class="sidebar-brand">
            <svg width="32" height="32" viewBox="0 0 200 200" fill="none">
                <rect x="10" y="10" width="180" height="180" rx="6" stroke="#e8611a" stroke-width="12" fill="none"/>
                <rect x="35" y="35" width="130" height="130" rx="4" stroke="#e8611a" stroke-width="11" fill="none"/>
                <rect x="58" y="58" width="84" height="84" rx="3" stroke="#e8611a" stroke-width="10" fill="none"/>
                <rect x="78" y="78" width="44" height="44" rx="2" stroke="#e8611a" stroke-width="9" fill="none"/>
            </svg>
            <span>Roya<span class="brand-accent">Tech</span></span>
        </div>
        <nav class="nav flex-column gap-1 py-3">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>الرئيسية
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-cpu me-2"></i>الخدمات
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags me-2"></i>الأقسام
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt me-2"></i>الطلبات
            </a>
            <a href="{{ route('admin.coupons.index') }}"
               class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="bi bi-percent me-2"></i>الخصومات
            </a>
            <a href="{{ route('admin.wallet.users') }}"
               class="nav-link {{ request()->routeIs('admin.wallet.users') || request()->routeIs('admin.wallet.user-*') || request()->routeIs('admin.wallet.adjust*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>أرصدة العملاء
            </a>
            <a href="{{ route('admin.wallet.codes') }}"
               class="nav-link {{ request()->routeIs('admin.wallet.codes') || request()->routeIs('admin.wallet.generate*') || request()->routeIs('admin.wallet.disable') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated me-2"></i>أكواد الشحن
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

            <a href="{{ route('admin.settings.index') }}"
               class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear me-2"></i>إعدادات الموقع
            </a>

            <hr class="border-secondary my-2 mx-3">

            <a href="{{ route('shop.index') }}" class="nav-link" target="_blank">
                <i class="bi bi-shop me-2"></i>الموقع
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
    <div class="admin-main">

        {{-- Topbar --}}
        <div class="admin-topbar">
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
                            <a href="{{ route('admin.notifications.index') }}" class="small">عرض الكل</a>
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
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                         style="width:36px;height:36px;background:var(--rt-orange)">
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
