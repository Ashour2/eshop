@extends('layouts.app')
@section('title', 'مفضلتي')
@section('content')

<h3 class="fw-bold mb-4">❤️ مفضلتي ({{ $items->count() }})</h3>

@forelse($items as $item)
@php $product = $item->product; @endphp
<div class="card border-0 shadow-sm rounded-4 mb-3">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">

            {{-- الصورة --}}
            <div class="col-md-1 col-3">
                @if($product->image)
                    <img src="{{ $product->image_url }}"
                         class="rounded-3 w-100"
                         style="height:70px;object-fit:cover"
                         alt="{{ $product->name }}">
                @else
                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center"
                         style="height:70px;font-size:1.8rem">🖥️</div>
                @endif
            </div>

            {{-- البيانات --}}
            <div class="col-md-6 col-9">
                @if($product->category)
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-1" style="font-size:.75rem">
                    {{ $product->category->name }}
                </span>
                @endif
                <h6 class="fw-bold mb-1">
                    <a href="{{ route('shop.show', $product) }}"
                       class="text-decoration-none text-dark">
                        {{ $product->name }}
                    </a>
                </h6>
                <div class="small">
                    {!! $product->stars_html !!}
                    <span class="text-muted ms-1">({{ $product->reviews_count }})</span>
                </div>
            </div>

            {{-- السعر --}}
            <div class="col-md-2 text-center">
                <div class="fs-5 fw-bold text-success">${{ number_format($product->price, 2) }}</div>
                @if($product->stock > 0)
                    <span class="badge bg-success-subtle text-success border border-success-subtle">متوفر</span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">نفد</span>
                @endif
            </div>

            {{-- الأزرار --}}
            <div class="col-md-3 d-flex gap-2 justify-content-md-end">
                @if($product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-sm">
                        <i class="bi bi-cart-plus"></i> أضف للسلة
                    </button>
                </form>
                @endif

                <form action="{{ route('wishlist.destroy', $item) }}" method="POST"
                      onsubmit="return confirm('إزالة من المفضلة؟')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <i class="bi bi-heart" style="font-size:5rem;color:#ccc"></i>
    <h5 class="mt-3 text-muted">قائمة المفضلة فارغة</h5>
    <p class="text-muted">أضف منتجاتك المفضلة بالضغط على ❤️</p>
    <a href="{{ route('shop.index') }}" class="btn btn-primary mt-2">تصفح المنتجات</a>
</div>
@endforelse

@endsection
