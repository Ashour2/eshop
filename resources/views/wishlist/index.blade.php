@extends('layouts.app')
@section('title', __('shop.my_wishlist'))
@section('content')

<h3 class="fw-bold mb-4">❤️ {{ __('shop.my_wishlist') }} ({{ $items->count() }})</h3>

@forelse($items as $item)
@php $product = $item->product; @endphp
<div class="card border-0 shadow-sm rounded-4 mb-3">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">

            <div class="col-md-7 col-9">
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

            <div class="col-md-2 text-center">
                <div class="fs-5 fw-bold text-success">${{ number_format($product->price, 2) }}</div>
                <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('shop.available') }}</span>
            </div>

            <div class="col-md-3 d-flex gap-2 justify-content-md-end">
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-sm">
                        <i class="bi bi-cart-plus"></i> {{ __('shop.add_to_cart') }}
                    </button>
                </form>

                <form action="{{ route('wishlist.destroy', $item) }}" method="POST"
                      onsubmit="return confirm('{{ __('shop.remove_wishlist_confirm') }}')">
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
    <h5 class="mt-3 text-muted">{{ __('shop.wishlist_empty') }}</h5>
    <p class="text-muted">{{ __('shop.wishlist_hint') }}</p>
    <a href="{{ route('shop.index') }}" class="btn btn-primary mt-2">{{ __('shop.browse_products') }}</a>
</div>
@endforelse

@endsection
