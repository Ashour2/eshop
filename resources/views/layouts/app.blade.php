<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('shop.home')) — RoyaTech</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @if(app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">

{{-- Page Loader --}}
<div class="rt-page-loader" id="pageLoader">
    <svg width="80" height="80" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect class="rt-line rt-line-1" x="10" y="10" width="180" height="180" rx="6" stroke="#e8611a" stroke-width="12" fill="none"/>
        <rect class="rt-line rt-line-2" x="35" y="35" width="130" height="130" rx="4" stroke="#e8611a" stroke-width="11" fill="none"/>
        <rect class="rt-line rt-line-3" x="58" y="58" width="84" height="84" rx="3" stroke="#e8611a" stroke-width="10" fill="none"/>
        <rect class="rt-line rt-line-4" x="78" y="78" width="44" height="44" rx="2" stroke="#e8611a" stroke-width="9" fill="none"/>
    </svg>
    <div class="rt-loader-text">Roya<span class="brand-accent">Tech</span></div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2" href="{{ route('shop.index') }}">
            <svg class="rt-logo" width="38" height="38" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect class="rt-line rt-line-1" x="10" y="10" width="180" height="180" rx="6" stroke="#e8611a" stroke-width="12" fill="none"/>
                <rect class="rt-line rt-line-2" x="35" y="35" width="130" height="130" rx="4" stroke="#e8611a" stroke-width="11" fill="none"/>
                <rect class="rt-line rt-line-3" x="58" y="58" width="84" height="84" rx="3" stroke="#e8611a" stroke-width="10" fill="none"/>
                <rect class="rt-line rt-line-4" x="78" y="78" width="44" height="44" rx="2" stroke="#e8611a" stroke-width="9" fill="none"/>
            </svg>
            <span>Roya<span class="brand-accent">Tech</span></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.index') && !request()->has('category') ? 'active' : '' }}"
                       href="{{ route('shop.index') }}">{{ __('shop.home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.index') }}#products">{{ __('shop.products') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.index') }}#categories">{{ __('shop.categories') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.index') }}#about">{{ __('shop.about_us') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.index') }}#contact">{{ __('shop.contact_us') }}</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">

                {{-- زر البحث --}}
                <button id="searchBtn" class="btn btn-outline-light btn-sm px-2" title="{{ __('shop.search') }}">
                    <i class="bi bi-search fs-6"></i>
                </button>

                {{-- زر تبديل اللغة --}}
                @if(app()->getLocale() === 'ar')
                    <a href="{{ route('locale.switch', 'en') }}" class="btn btn-outline-light btn-sm">
                        🌐 English
                    </a>
                @else
                    <a href="{{ route('locale.switch', 'ar') }}" class="btn btn-outline-light btn-sm">
                        🌐 العربية
                    </a>
                @endif

                {{-- السلة --}}
                <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-sm position-relative">
                    <i class="bi bi-cart3"></i>
                    @if(count(session('cart', [])) > 0)
                        <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                            {{ count(session('cart', [])) }}
                        </span>
                    @endif
                    {{ __('shop.cart') }}
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
                        {{ __('shop.wishlist') }}
                    </a>
                    @endif
                @endauth

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-speedometer2"></i> {{ __('shop.dashboard') }}
                        </a>
                    @else
                        {{-- زر المحفظة --}}
                        <button class="btn btn-sm position-relative fw-bold"
                                style="background:rgba(232,97,26,.15);border:1px solid #e8611a;color:#e8611a"
                                data-bs-toggle="modal" data-bs-target="#walletModal"
                                title="محفظتي">
                            <i class="bi bi-wallet2 me-1"></i>
                            ${{ number_format(auth()->user()->wallet?->balance ?? 0, 2) }}
                        </button>

                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle"
                                    type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li>
                                    <a class="dropdown-item" href="{{ route('account.index') }}">
                                        <i class="bi bi-person me-2"></i> {{ __('shop.my_account') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('account.orders') }}">
                                        <i class="bi bi-receipt me-2"></i> {{ __('shop.my_orders') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('wishlist.index') }}">
                                        <i class="bi bi-heart me-2"></i> {{ __('shop.wishlist') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('wallet.index') }}">
                                        <i class="bi bi-wallet2 me-2"></i> محفظتي
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-left me-2"></i> {{ __('shop.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-in-right"></i> {{ __('shop.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-person-plus"></i> {{ __('shop.register') }}
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

@hasSection('full_width')
    @yield('content')
@else
    <div class="container py-4">
        @yield('content')
    </div>
@endif

<footer class="bg-dark text-white mt-5 py-4">
    <div class="container text-center">
        <p class="mb-0 opacity-75">© {{ date('Y') }} RoyaTech — {{ __('shop.all_rights') }}</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Modal المحفظة — متاح في كل الصفحات للمستخدمين العاديين --}}
@auth
    @if(!auth()->user()->is_admin)
        @include('wallet._redeem_modal')
        @if(session('open_wallet_modal') || $errors->has('amount') || $errors->has('code'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = new bootstrap.Modal(document.getElementById('walletModal'));
                modal.show();
                @if($errors->has('amount') && !$errors->has('code'))
                // افتح تاب طلب الشحن إن كان الخطأ من نموذج الشحن
                var tab = document.querySelector('[data-bs-target="#tabRequest"]');
                if (tab) bootstrap.Tab.getOrCreateInstance(tab).show();
                @endif
            });
        </script>
        @endif
    @endif
@endauth

@stack('scripts')

<script>
(function() {
    const loader = document.getElementById('pageLoader');

    // Hide loader when page is ready
    window.addEventListener('DOMContentLoaded', function () {
        setTimeout(function() {
            loader.classList.add('rt-loader-hidden');
        }, 900);

        // Scroll restore
        const scrollPos = sessionStorage.getItem('scrollPos');
        if (scrollPos) {
            window.scrollTo({ top: parseInt(scrollPos), behavior: 'instant' });
            sessionStorage.removeItem('scrollPos');
        }

        // Save scroll on form submit
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function () {
                sessionStorage.setItem('scrollPos', window.scrollY);
            });
        });

        // Search button → scroll to filter
        var searchBtn = document.getElementById('searchBtn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                var filterSection = document.getElementById('categories');
                if (filterSection) {
                    // على الصفحة الرئيسية — scroll مباشرة
                    filterSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // فوكس على حقل البحث
                    setTimeout(function() {
                        var searchInput = filterSection.querySelector('input[name="search"]');
                        if (searchInput) searchInput.focus();
                    }, 600);
                } else {
                    // على صفحة ثانية — روح للرئيسية وفتح الفلترة
                    window.location.href = '{{ route('shop.index') }}#categories';
                }
            });
        }

        // إذا جاء من صفحة ثانية عبر #categories، فوكس على البحث
        if (window.location.hash === '#categories') {
            setTimeout(function() {
                var searchInput = document.querySelector('#categories input[name="search"]');
                if (searchInput) searchInput.focus();
            }, 800);
        }
    });

    // Show loader on page navigation (link clicks)
    document.addEventListener('click', function(e) {
        var link = e.target.closest('a[href]');
        if (!link) return;
        var href = link.getAttribute('href');
        // Skip anchors, javascript:, new tabs, external
        if (!href || href.startsWith('#') || href.startsWith('javascript:')
            || href.includes('#') || link.target === '_blank' || link.hasAttribute('download')) return;
        // Skip if modifier keys held (open in new tab)
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;

        // Reset SVG animations by cloning
        loader.classList.remove('rt-loader-hidden');
        var oldSvg = loader.querySelector('svg');
        var newSvg = oldSvg.cloneNode(true);
        oldSvg.parentNode.replaceChild(newSvg, oldSvg);
    });

    // Safety: hide loader after max 4 seconds
    window.addEventListener('pageshow', function() {
        setTimeout(function() {
            loader.classList.add('rt-loader-hidden');
        }, 900);
    });
})();
</script>

</body>
</html>
