@extends('layouts.app')
@section('title', 'الصفحة الرئيسية')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<style>
    /* ── السلايدر ── */
    .swiper { width:100%; height:480px; }
    .swiper-slide { position:relative; background-size:cover; background-position:center; display:flex; align-items:center; }
    .slide-overlay { position:absolute; inset:0; background:linear-gradient(to left,rgba(0,0,0,.65),rgba(0,0,0,.2)); }
    .slide-content { position:relative; z-index:2; color:#fff; padding:0 60px; max-width:600px; }
    .slide-content h2 { font-size:2.2rem; font-weight:800; margin-bottom:.5rem; }
    .slide-content p  { font-size:1.1rem; opacity:.9; margin-bottom:1.5rem; }
    .swiper-button-next,.swiper-button-prev { color:#fff; }
    .swiper-pagination-bullet-active { background:#fff; }

    /* ── فلترة ── */
    .filter-bar { background:#fff; border-radius:16px; padding:24px; box-shadow:0 4px 20px rgba(0,0,0,.07); }
    .category-pills .btn { border-radius:50px; font-size:.85rem; }
    .category-pills .btn.active { background:#0d6efd; color:#fff; border-color:#0d6efd; }

    /* ── البطاقات ── */
    .product-card { transition:transform .25s,box-shadow .25s; border:none; border-radius:14px; overflow:hidden; }
    .product-card:hover { transform:translateY(-6px); box-shadow:0 12px 30px rgba(0,0,0,.12) !important; }
    .product-card img { height:200px; object-fit:cover; }
    .no-img { height:200px; background:#f0f4ff; display:flex; align-items:center; justify-content:center; font-size:3rem; color:#aab; }

    /* ── من نحن ── */
    .about-section { background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%); color:#fff; }
    .stat-box { border:1px solid rgba(255,255,255,.15); border-radius:12px; padding:24px; text-align:center; }
    .stat-box .num { font-size:2.4rem; font-weight:800; color:#ffc107; }
    .stat-box .lbl { font-size:.95rem; opacity:.8; margin-top:4px; }
    .feature-icon { width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }

    /* ── نتائج البحث ── */
    .results-bar { background:#f8f9fa; border-radius:10px; padding:12px 18px; }

    @media(max-width:576px) {
        .swiper { height:300px; }
        .slide-content { padding:0 20px; }
        .slide-content h2 { font-size:1.4rem; }
    }
</style>
@endpush

@section('content')

{{-- ══ 1. السلايدر ══════════════════════════════════════════ --}}
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
                        {{ $slide['btn'] }} &larr;
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

<div class="container">

{{-- ══ 2. شريط البحث والفلترة ══════════════════════════════ --}}
<section class="mb-4">
    <div class="filter-bar">
        <form action="{{ route('shop.index') }}" method="GET" id="filterForm">

            {{-- بحث + ترتيب --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text"
                               name="search"
                               class="form-control border-start-0"
                               placeholder="ابحث عن منتج..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">من $</span>
                        <input type="number" name="min_price" class="form-control" placeholder="0" value="{{ request('min_price') }}" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">إلى $</span>
                        <input type="number" name="max_price" class="form-control" placeholder="9999" value="{{ request('max_price') }}" min="0">
                    </div>
                </div>
            </div>

            {{-- التصنيفات --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3 category-pills">
                <span class="text-muted small fw-bold me-1">التصنيف:</span>
                <a href="{{ route('shop.index', array_merge(request()->except('category','page'), [])) }}"
                   class="btn btn-sm btn-outline-secondary {{ !request('category') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i> الكل
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('shop.index', array_merge(request()->except('category','page'), ['category' => $cat->id])) }}"
                   class="btn btn-sm btn-outline-secondary {{ request('category') == $cat->id ? 'active' : '' }}">
                    <i class="bi {{ $cat->icon }}"></i> {{ $cat->name }}
                </a>
                @endforeach
            </div>

            {{-- الترتيب + زر البحث --}}
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <select name="sort" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                    <option value="latest"     {{ request('sort','latest') == 'latest'     ? 'selected':'' }}>الأحدث</option>
                    <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected':'' }}>السعر: الأقل أولاً</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected':'' }}>السعر: الأعلى أولاً</option>
                    <option value="name"       {{ request('sort') == 'name'       ? 'selected':'' }}>الاسم أ-ي</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm px-4">
                    <i class="bi bi-funnel"></i> تطبيق الفلتر
                </button>
                @if(request()->hasAny(['search','category','min_price','max_price','sort']))
                <a href="{{ route('shop.index') }}" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-x-circle"></i> مسح الفلاتر
                </a>
                @endif
            </div>

        </form>
    </div>
</section>

{{-- ══ 3. نتائج البحث ══════════════════════════════════════ --}}
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0">
                @if(request('search'))
                    🔍 نتائج البحث عن "{{ request('search') }}"
                @elseif(request('category'))
                    🏷️ {{ $categories->find(request('category'))?->name ?? 'منتجات' }}
                @else
                    🛍️ جميع المنتجات
                @endif
            </h5>
            <small class="text-muted">{{ $products->total() }} منتج</small>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-search" style="font-size:4rem;color:#ccc"></i>
            <h5 class="mt-3 text-muted">لا توجد نتائج تطابق بحثك</h5>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-primary mt-2">عرض جميع المنتجات</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-xl-4 col-md-6">
                <div class="card product-card shadow-sm h-100">
                    @if($product->image)
                        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <div class="no-img">🖥️</div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        {{-- التصنيف --}}
                        @if($product->category)
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-2" style="width:fit-content">
                            <i class="bi {{ $product->category->icon }}"></i>
                            {{ $product->category->name }}
                        </span>
                        @endif

                        <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($product->description, 70) }}
                        </p>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fs-5 fw-bold text-success">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                                @if($product->stock > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        متوفر ({{ $product->stock }})
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">نفد</span>
                                @endif
                            </div>

                            @if($product->stock > 0)
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-primary w-100">
                                        <i class="bi bi-cart-plus"></i> أضف للسلة
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100" disabled>نفد المخزون</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @endif
</section>

{{-- ══ 4. من نحن ══════════════════════════════════════════ --}}
</div>

<section class="about-section py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2">من نحن</span>
                <h2 class="fw-bold mb-3" style="font-size:2rem">متجرك الموثوق للإلكترونيات 🏆</h2>
                <p class="opacity-75 mb-4" style="line-height:1.9">
                    نحن متجر إلكتروني متخصص في توفير أحدث المنتجات التقنية والإلكترونيات بأعلى جودة
                    وأفضل الأسعار. نسعى دائماً لتقديم تجربة تسوق سهلة وممتعة، مع ضمان أصالة
                    جميع المنتجات وخدمة ما بعد البيع.
                </p>
                <div class="row g-3 mb-4">
                    @foreach([
                        ['icon'=>'bi-truck',       'color'=>'#ffc107','title'=>'توصيل سريع',  'desc'=>'لجميع المناطق'],
                        ['icon'=>'bi-shield-check', 'color'=>'#0dcaf0','title'=>'ضمان الجودة', 'desc'=>'منتجات أصلية 100%'],
                        ['icon'=>'bi-headset',      'color'=>'#20c997','title'=>'دعم 24/7',    'desc'=>'فريقنا في خدمتك'],
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
                <a href="{{ route('shop.index') }}" class="btn btn-warning fw-bold px-4">تسوق الآن &larr;</a>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">
                    @foreach([
                        ['icon'=>'📦','num'=>$stats['products'].'+',   'lbl'=>'منتج متوفر'],
                        ['icon'=>'👥','num'=>$stats['clients'].'+',    'lbl'=>'عميل سعيد'],
                        ['icon'=>'⭐','num'=>'4.9',                    'lbl'=>'تقييم المتجر'],
                        ['icon'=>'🏅','num'=>$stats['years'].' سنوات','lbl'=>'خبرة في السوق'],
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
