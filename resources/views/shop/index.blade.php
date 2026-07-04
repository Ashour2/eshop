@extends('layouts.app')
@section('title', __('shop.all_products'))
@section('full_width', true)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<style>
    .swiper { width:100%; height:480px; overflow:hidden; position:relative; }
    .swiper-slide { position:relative; background-size:cover; background-position:center; display:flex; align-items:center; }
    .swiper-button-next, .swiper-button-prev { position:absolute; z-index:10; }
    .mainSwiper { overflow:hidden; }
    .slide-overlay { position:absolute; inset:0; background:linear-gradient(to left,rgba(26,31,61,.85),rgba(26,31,61,.3)); }
    .slide-content { position:relative; z-index:2; color:#fff; padding:0 60px; max-width:600px; }
    .slide-content h2 { font-size:2.2rem; font-weight:800; margin-bottom:.5rem; }
    .slide-content p  { font-size:1.1rem; opacity:.9; margin-bottom:1.5rem; }
    .filter-bar { border-radius:16px; padding:24px; }
    .category-pills .btn { border-radius:50px; font-size:.85rem; }
    .product-card { border-radius:14px; overflow:hidden; }
    .product-card img { height:200px; object-fit:cover; }
    .feature-icon { width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
    @media(max-width:576px) {
        .swiper { height:300px; }
        .slide-content { padding:0 20px; }
        .slide-content h2 { font-size:1.4rem; }
    }
</style>
@endpush

@section('content')

{{-- ══ السلايدر ══════════════════════════════════════════ --}}
<div class="container-fluid px-0 mb-5">
    <div class="swiper mainSwiper">
        <div class="swiper-wrapper">
            @foreach($slides as $slide)
            <div class="swiper-slide" style="background-image:url('{{ $slide['bg'] }}')">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h2>{{ $slide['title'] }}</h2>
                    <p>{{ $slide['subtitle'] }}</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-warning btn-lg fw-bold px-4">
                        {{ __('shop.shop_now') }} &larr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</div>

<div class="container py-4">

{{-- ══ شريط البحث والفلترة ══════════════════════════════ --}}
<section class="mb-4" id="categories">
    <div class="filter-bar">
        <form action="{{ route('shop.index') }}" method="GET" id="filterForm">
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="{{ __('shop.search') }}" value="{{ request('search') }}">
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2 mb-3 category-pills">
                <span class="text-muted small fw-bold me-1">{{ __('shop.all_categories') }}:</span>
                <a href="{{ route('shop.index', array_merge(request()->except('category','page'), [])) }}"
                   class="btn btn-sm btn-outline-secondary {{ !request('category') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i> {{ __('shop.all_categories') }}
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('shop.index', array_merge(request()->except('category','page'), ['category' => $cat->id])) }}"
                   class="btn btn-sm btn-outline-secondary {{ request('category') == $cat->id ? 'active' : '' }}">
                    <i class="bi {{ $cat->icon }}"></i> {{ $cat->name }}
                </a>
                @endforeach
            </div>

            <div class="d-flex gap-2 align-items-center flex-wrap">
                <select name="sort" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                    <option value="latest"     {{ request('sort','latest') == 'latest'     ? 'selected':'' }}>{{ __('shop.sort_latest') }}</option>
                    <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected':'' }}>{{ __('shop.sort_price_asc') }}</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected':'' }}>{{ __('shop.sort_price_desc') }}</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm px-4">
                    <i class="bi bi-funnel"></i> {{ __('shop.filter') }}
                </button>
                @if(request()->hasAny(['search','category','sort']))
                <a href="{{ route('shop.index') }}" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-x-circle"></i> {{ __('shop.clear_filters') }}
                </a>
                @endif
            </div>
        </form>
    </div>
</section>

{{-- ══ المنتجات ══════════════════════════════════════════ --}}
<section class="mb-5" id="products">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0">🛍️ {{ __('shop.all_products') }}</h5>
            <small class="text-muted">{{ $products->total() }} {{ __('shop.product_count') }}</small>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-search" style="font-size:4rem;color:#ccc"></i>
            <h5 class="mt-3 text-muted">{{ __('shop.no_products') }}</h5>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-primary mt-2">{{ __('shop.all_products') }}</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-xl-4 col-md-6">
                <div class="card product-card shadow-sm h-100"
                     onclick="window.location='{{ route('shop.show', $product) }}'"
                     style="cursor:pointer">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            @if($product->category)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="width:fit-content">
                                <i class="bi {{ $product->category->icon }}"></i> {{ $product->category->name }}
                            </span>
                            @endif

                            @auth
                            <form action="{{ route('wishlist.toggle', $product) }}"
                                  method="POST" class="d-inline" onclick="event.stopPropagation()">
                                @csrf
                                <button class="btn btn-sm {{ auth()->user()->hasInWishlist($product->id) ? 'btn-danger' : 'btn-outline-danger' }}">
                                    <i class="bi bi-heart{{ auth()->user()->hasInWishlist($product->id) ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                            @endauth
                        </div>

                        <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 100) }}</p>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="text-muted" style="font-size:.75rem">{{ __('shop.starts_from') }}</div>
                                    <span class="fs-5 fw-bold text-success" dir="ltr">${{ number_format($product->price, 2) }}</span>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('shop.available') }}</span>
                            </div>

                            <form action="{{ route('cart.add', $product) }}" method="POST" onclick="event.stopPropagation()">
                                @csrf
                                <button class="btn btn-primary w-100">
                                    <i class="bi bi-cart-plus"></i> {{ __('shop.add_to_cart') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($products->hasPages())
        <div class="mt-5 d-flex justify-content-center align-items-center gap-2">
            {{-- السابق --}}
            @if($products->onFirstPage())
                <span class="btn btn-outline-secondary btn-sm disabled px-3">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="btn btn-outline-light btn-sm px-3">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @endif

            {{-- أرقام الصفحات --}}
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                    <span class="btn btn-sm px-3 fw-bold" style="background:var(--rt-orange);color:#fff;border:none">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="btn btn-outline-light btn-sm px-3">{{ $page }}</a>
                @endif
            @endforeach

            {{-- التالي --}}
            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="btn btn-outline-light btn-sm px-3">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @else
                <span class="btn btn-outline-secondary btn-sm disabled px-3">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @endif
        </div>
        <div class="text-center mt-2 text-muted small">
            {{ $products->firstItem() }}–{{ $products->lastItem() }} {{ __('shop.of') }} {{ $products->total() }} {{ __('shop.product_count') }}
        </div>
        @endif
    @endif
</section>

</div>

{{-- ══ من نحن ══════════════════════════════════════════ --}}
<section class="about-section py-5" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2">{{ __('shop.about_us') }}</span>
                <h2 class="fw-bold mb-3" style="font-size:2rem">{{ __('shop.trusted_store') }} 🏆</h2>
                <p class="opacity-75 mb-4" style="line-height:1.9">
                    {{ __('shop.store_desc') }}
                </p>
                <div class="row g-3 mb-4">
                    @foreach([
                        ['icon'=>'bi-truck','color'=>'#ffc107','title'=>__('shop.fast_delivery'),'desc'=>__('shop.all_areas')],
                        ['icon'=>'bi-shield-check','color'=>'#0dcaf0','title'=>__('shop.quality_guarantee'),'desc'=>__('shop.original_products')],
                        ['icon'=>'bi-headset','color'=>'#20c997','title'=>__('shop.support_24'),'desc'=>__('shop.team_service')],
                    ] as $f)
                    <div class="col-sm-4">
                        <div class="d-flex gap-3 align-items-start">
                            <div class="feature-icon" style="background:{{ $f['color'] }}22">
                                <i class="bi {{ $f['icon'] }}" style="color:{{ $f['color'] }}"></i>
                            </div>
                            <div>
                                <div class="fw-bold small">{{ $f['title'] }}</div>
                                <div class="opacity-60" style="font-size:.8rem">{{ $f['desc'] }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-warning fw-bold px-4">{{ __('shop.shop_now') }} &larr;</a>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">
                    @foreach([
                        ['icon'=>'📦','num'=>$stats['products'].'+','lbl'=>__('shop.products_available')],
                        ['icon'=>'👥','num'=>$stats['clients'].'+','lbl'=>__('shop.happy_clients')],
                        ['icon'=>'⭐','num'=>'4.9','lbl'=>__('shop.store_rating')],
                        ['icon'=>'🏅','num'=>$stats['years'].' '.__('shop.years_experience'),'lbl'=>__('shop.market_experience')],
                    ] as $s)
                    <div class="col-6">
                        <div class="stat-box">
                            <div style="font-size:2rem">{{ $s['icon'] }}</div>
                            <div class="num">{{ $s['num'] }}</div>
                            <div class="lbl">{{ $s['lbl'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ تواصل معنا ══════════════════════════════════════════ --}}
<section class="py-5" id="contact">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-warning text-dark mb-3 px-3 py-2">{{ __('shop.contact_us') }}</span>
            <h2 class="fw-bold">{{ __('shop.contact_title') }}</h2>
            <p class="text-muted">{{ __('shop.contact_subtitle') }}</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width:64px;height:64px;background:var(--rt-orange-subtle)">
                        <i class="bi bi-geo-alt-fill fs-3" style="color:var(--rt-orange)"></i>
                    </div>
                    <h6 class="fw-bold">{{ __('shop.our_location') }}</h6>
                    <p class="text-muted small mb-0">{{ __('shop.location_value') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width:64px;height:64px;background:var(--rt-orange-subtle)">
                        <i class="bi bi-telephone-fill fs-3" style="color:var(--rt-orange)"></i>
                    </div>
                    <h6 class="fw-bold">{{ __('shop.phone') }}</h6>
                    <p class="text-muted small mb-0" dir="ltr">+970 59 404 8945</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width:64px;height:64px;background:var(--rt-orange-subtle)">
                        <i class="bi bi-envelope-fill fs-3" style="color:var(--rt-orange)"></i>
                    </div>
                    <h6 class="fw-bold">{{ __('shop.email') }}</h6>
                    <p class="text-muted small mb-0">royatechgaza@gmail.com</p>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="https://www.instagram.com/royatechgaza/" target="_blank" class="btn btn-outline-light btn-sm mx-1">
                <i class="bi bi-instagram"></i> Instagram
            </a>
            <a href="https://www.facebook.com/royatechgaza" target="_blank" class="btn btn-outline-light btn-sm mx-1">
                <i class="bi bi-facebook"></i> Facebook
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
new Swiper('.mainSwiper', {
    loop: true,
    autoplay: { delay: 4500, disableOnInteraction: false },
    speed: 800,
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
});
</script>
@endpush
