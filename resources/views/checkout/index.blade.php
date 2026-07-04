@extends('layouts.app')
@section('title', __('shop.checkout_title'))
@section('content')

<div class="row justify-content-center g-4">

<div class="col-md-5 order-md-2">
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-header bg-dark text-white fw-bold rounded-top-4 px-4 py-3">
            🧾 {{ __('shop.order_summary') }}
        </div>
        <ul class="list-group list-group-flush">
            @foreach($cart as $item)
            <li class="list-group-item d-flex justify-content-between px-4">
                <span>{{ $item['name'] }}</span>
                <span>${{ number_format($item['price'], 2) }}</span>
            </li>
            @endforeach
            <li class="list-group-item d-flex justify-content-between px-4">
                <span class="text-muted">{{ __('shop.subtotal') }}</span>
                <span id="subtotalDisplay">${{ number_format($total, 2) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-4 text-danger" id="discountRow" style="display:none!important">
                <span>🎟️ {{ __('shop.discount') }} (<span id="couponLabel"></span>)</span>
                <span>-$<span id="discountDisplay">0.00</span></span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-4 fw-bold fs-5">
                <span>{{ __('shop.total') }}</span>
                <span class="text-success" id="totalDisplay">${{ number_format($total, 2) }}</span>
            </li>
        </ul>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body px-4">
            <label class="form-label fw-bold">🎟️ {{ __('shop.coupon') }}</label>
            <div class="input-group">
                <input type="text" id="couponInput" class="form-control text-uppercase"
                       placeholder="{{ __('shop.coupon') }}" style="letter-spacing:2px">
                <button type="button" class="btn btn-outline-dark" onclick="applyCoupon()">
                    {{ __('shop.apply_coupon') }}
                </button>
            </div>
            <div id="couponMsg" class="mt-2 small"></div>
        </div>
    </div>
</div>

<div class="col-md-7 order-md-1">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">📦 {{ __('shop.checkout_title') }}</h5>
            <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="coupon_code" id="appliedCoupon">

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('shop.full_name') }}</label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user?->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('shop.email') }}</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user?->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">{{ __('shop.address') }}</label>
                    <textarea name="address" rows="3"
                              class="form-control @error('address') is-invalid @enderror"
                              required>{{ old('address') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- ── خيار الدفع بالرصيد ───────────────────── --}}
                @auth
                @php $walletBalance = $wallet?->balance ?? 0; @endphp
                <div class="card rounded-3 mb-4 border-0"
                     style="background:rgba(232,97,26,.07);border:1px solid rgba(232,97,26,.25)!important">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold"><i class="bi bi-wallet2 me-2" style="color:#e8611a"></i>الدفع من الرصيد</span>
                            <span class="fw-bold" style="color:#e8611a" dir="ltr">
                                رصيدك: ${{ number_format($walletBalance, 2) }}
                            </span>
                        </div>

                        @if($walletBalance >= $total)
                            {{-- رصيد كافٍ --}}
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox"
                                       name="pay_with_wallet" value="1" id="payWallet"
                                       {{ old('pay_with_wallet') ? 'checked' : '' }}>
                                <label class="form-check-label" for="payWallet">
                                    ادفع ${{ number_format($total, 2) }} من رصيدي
                                </label>
                            </div>
                        @else
                            {{-- رصيد غير كافٍ --}}
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    رصيدك غير كافٍ لهذا الطلب
                                    @if($walletBalance > 0)
                                        (ينقصك ${{ number_format($total - $walletBalance, 2) }})
                                    @endif
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal" data-bs-target="#walletModal">
                                    <i class="bi bi-plus-circle me-1"></i>اشحن رصيد
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                @endauth

                <button type="submit" class="btn btn-success w-100 btn-lg fw-bold">
                    <i class="bi bi-check-circle"></i> {{ __('shop.confirm_order') }}
                </button>
            </form>
        </div>
    </div>
</div>

</div>

@push('scripts')
<script>
const originalTotal = {{ $total }};
function applyCoupon() {
    const code = document.getElementById('couponInput').value.trim();
    const msg  = document.getElementById('couponMsg');
    if (!code) { msg.innerHTML = '<span class="text-danger">{{ __("shop.enter_coupon_first") }}</span>'; return; }
    fetch('{{ route("coupon.apply") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ code: code, total: originalTotal })
    })
    .then(r => r.json())
    .then(data => {
        if (data.valid) {
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('couponLabel').textContent   = data.description;
            document.getElementById('discountDisplay').textContent = data.discount.toFixed(2);
            document.getElementById('totalDisplay').textContent  = '$' + data.new_total.toFixed(2);
            document.getElementById('appliedCoupon').value       = code;
            msg.innerHTML = `<span class="text-success"><i class="bi bi-check-circle"></i> ${data.message}</span>`;
        } else {
            msg.innerHTML = `<span class="text-danger"><i class="bi bi-x-circle"></i> ${data.message}</span>`;
        }
    });
}
document.getElementById('couponInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); applyCoupon(); }
});
</script>
@endpush

@endsection
