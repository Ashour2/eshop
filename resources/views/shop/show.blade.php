@extends('layouts.app')
@section('title', $product->name)
@section('content')

{{-- ══ تفاصيل المنتج ══════════════════════════════════════ --}}
<div class="row g-5 mb-5">

    {{-- الصورة --}}
    <div class="col-md-5">
        @if($product->image)
            <img src="{{ $product->image_url }}"
                 class="img-fluid rounded-4 shadow-sm w-100"
                 style="max-height:400px;object-fit:cover"
                 alt="{{ $product->name }}">
        @else
            <div class="bg-light rounded-4 d-flex align-items-center justify-content-center"
                 style="height:400px;font-size:5rem">🖥️</div>
        @endif
    </div>

    {{-- البيانات --}}
    <div class="col-md-7">
        @if($product->category)
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-2">
            <i class="bi {{ $product->category->icon }}"></i> {{ $product->category->name }}
        </span>
        @endif

        <h2 class="fw-bold mb-2">{{ $product->name }}</h2>

        {{-- التقييم --}}
        <div class="d-flex align-items-center gap-2 mb-3">
            <span>{!! $product->stars_html !!}</span>
            <span class="fw-bold">{{ $product->avg_rating }}</span>
            <span class="text-muted">({{ $product->reviews_count }} تقييم)</span>
        </div>

        <p class="text-muted mb-4" style="line-height:1.9">{{ $product->description }}</p>

        <div class="d-flex align-items-center gap-3 mb-4">
            <span class="fs-2 fw-bold text-success">${{ number_format($product->price, 2) }}</span>
            @if($product->stock > 0)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                    ✅ متوفر ({{ $product->stock }} قطعة)
                </span>
            @else
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                    ❌ نفد المخزون
                </span>
            @endif
        </div>

        @if($product->stock > 0)
        <form action="{{ route('cart.add', $product) }}" method="POST">
            @csrf
            <button class="btn btn-primary btn-lg px-5">
                <i class="bi bi-cart-plus"></i> أضف للسلة
            </button>
        </form>
        @endif
    </div>
</div>

{{-- ══ التقييمات ══════════════════════════════════════════ --}}
<div class="row g-4">

    {{-- قائمة التقييمات --}}
    <div class="col-lg-8">
        <h4 class="fw-bold mb-4">⭐ آراء العملاء ({{ $product->reviews_count }})</h4>

        @forelse($product->reviews as $review)
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:44px;height:44px;font-size:1.1rem;flex-shrink:0">
                            {{ mb_substr($review->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ $review->user->name }}</div>
                            <div class="small text-muted">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                            @endfor
                        </span>
                        @auth
                            @if(auth()->id() === $review->user_id)
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                                  onsubmit="return confirm('حذف التقييم؟')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        @endauth
                    </div>
                </div>
                @if($review->comment)
                <p class="mt-3 mb-0 text-muted">{{ $review->comment }}</p>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-muted">
            <i class="bi bi-chat-square-text" style="font-size:3rem;opacity:.3"></i>
            <p class="mt-2">لا توجد تقييمات بعد — كن أول من يقيّم!</p>
        </div>
        @endforelse
    </div>

    {{-- فورم التقييم --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top:20px">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">✍️ أضف تقييمك</h5>

                @auth
                    @php
                        $userReview = $product->reviews()->where('user_id', auth()->id())->first();
                        $purchased  = auth()->user()->orders()
                            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
                            ->exists();
                    @endphp

                    @if($userReview)
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            لقد قيّمت هذا المنتج بـ {{ $userReview->rating }} نجوم
                        </div>
                    @elseif(!$purchased)
                        <div class="alert alert-warning">
                            <i class="bi bi-bag me-2"></i>
                            يجب شراء المنتج أولاً لتتمكن من التقييم
                        </div>
                        <a href="{{ route('cart.add', $product) }}"
                           class="btn btn-primary w-100">اشتره الآن</a>
                    @else
                        <form action="{{ route('reviews.store', $product) }}" method="POST">
                            @csrf

                            {{-- النجوم --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">تقييمك</label>
                                <div class="d-flex gap-2 fs-2" id="starRating">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star text-warning"
                                       style="cursor:pointer"
                                       data-value="{{ $i }}"
                                       onclick="setRating({{ $i }})"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="">
                                @error('rating')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">تعليقك (اختياري)</label>
                                <textarea name="comment" class="form-control" rows="4"
                                          placeholder="شاركنا رأيك بالمنتج...">{{ old('comment') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 fw-bold">
                                <i class="bi bi-send"></i> إرسال التقييم
                            </button>
                        </form>
                    @endif

                @else
                    <div class="text-center py-3">
                        <i class="bi bi-person-lock" style="font-size:2.5rem;color:#ccc"></i>
                        <p class="text-muted mt-2">سجّل دخول لإضافة تقييم</p>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">تسجيل الدخول</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function setRating(value) {
    document.getElementById('ratingInput').value = value;
    const stars = document.querySelectorAll('#starRating i');
    stars.forEach((s, i) => {
        s.className = i < value
            ? 'bi bi-star-fill text-warning'
            : 'bi bi-star text-warning';
        s.style.cursor = 'pointer';
    });
}

// hover effect
document.querySelectorAll('#starRating i').forEach((star, idx) => {
    star.addEventListener('mouseover', () => {
        document.querySelectorAll('#starRating i').forEach((s, i) => {
            s.className = i <= idx
                ? 'bi bi-star-fill text-warning'
                : 'bi bi-star text-warning';
        });
    });
    star.addEventListener('mouseout', () => {
        const current = parseInt(document.getElementById('ratingInput').value) || 0;
        document.querySelectorAll('#starRating i').forEach((s, i) => {
            s.className = i < current
                ? 'bi bi-star-fill text-warning'
                : 'bi bi-star text-warning';
        });
    });
});
</script>
@endpush

@endsection
