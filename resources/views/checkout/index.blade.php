@extends('layouts.app')
@section('title', 'إتمام الطلب')
@section('content')

<div class="row justify-content-center g-4">

{{-- ── ملخص الطلب ─────────────────────────────────────── --}}
<div class="col-md-5 order-md-2">
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-header bg-dark text-white fw-bold rounded-top-4 px-4 py-3">
            🧾 ملخص الطلب
        </div>
        <ul class="list-group list-group-flush">
            @foreach($cart as $item)
            <li class="list-group-item d-flex justify-content-between px-4">
                <span>{{ $item['name'] }} <span class="text-muted">× {{ $item['quantity'] }}</span></span>
                <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
            </li>
            @endforeach
            <li class="list-group-item d-flex justify-content-between px-4">
                <span class="text-muted">المجموع الفرعي</span>
                <span id="subtotalDisplay">${{ number_format($total, 2) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-4 text-danger" id="discountRow" style="display:none!important">
                <span>🎟️ الخصم (<span id="couponLabel"></span>)</span>
                <span>-$<span id="discountDisplay">0.00</span></span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-4 fw-bold fs-5">
                <span>الإجمالي</span>
                <span class="text-success" id="totalDisplay">${{ number_format($total, 2) }}</span>
            </li>
        </ul>
    </div>

    {{-- حقل الكوبون --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body px-4">
            <label class="form-label fw-bold">🎟️ هل لديك كوبون خصم؟</label>
            <div class="input-group">
                <input type="text" id="couponInput"
                       class="form-control text-uppercase"
                       placeholder="أدخل كود الكوبون"
                       style="letter-spacing:2px">
                <button type="button" class="btn btn-outline-dark" onclick="applyCoupon()">
                    تطبيق
                </button>
            </div>
            <div id="couponMsg" class="mt-2 small"></div>
        </div>
    </div>
</div>

{{-- ── بيانات الطلب ───────────────────────────────────── --}}
<div class="col-md-7 order-md-1">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">📦 بيانات التوصيل</h5>

            <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="coupon_code" id="appliedCoupon">

                <div class="mb-3">
                    <label class="form-label fw-bold">الاسم الكامل</label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user?->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">البريد الإلكتروني</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user?->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">العنوان الكامل</label>
                    <textarea name="address" rows="3"
                              class="form-control @error('address') is-invalid @enderror"
                              placeholder="المدينة، الشارع، رقم المبنى..." required>{{ old('address') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-success w-100 btn-lg fw-bold">
                    <i class="bi bi-check-circle"></i> تأكيد الطلب
                </button>
            </form>
        </div>
    </div>
</div>

</div>

@push('scripts')
<script>
    const originalTotal = {{ $total }};
    let appliedDiscount = 0;

    function applyCoupon() {
        const code = document.getElementById('couponInput').value.trim();
        const msg  = document.getElementById('couponMsg');

        if (!code) {
            msg.innerHTML = '<span class="text-danger">أدخل كود الكوبون أولاً</span>';
            return;
        }

        fetch('{{ route("coupon.apply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code, total: originalTotal })
        })
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                appliedDiscount = data.discount;

                // تحديث الواجهة
                document.getElementById('discountRow').style.display = 'flex';
                document.getElementById('couponLabel').textContent   = data.description;
                document.getElementById('discountDisplay').textContent = data.discount.toFixed(2);
                document.getElementById('totalDisplay').textContent  = '$' + data.new_total.toFixed(2);
                document.getElementById('appliedCoupon').value       = code;

                msg.innerHTML = `<span class="text-success"><i class="bi bi-check-circle"></i> ${data.message}</span>`;
            } else {
                msg.innerHTML = `<span class="text-danger"><i class="bi bi-x-circle"></i> ${data.message}</span>`;
                resetCoupon();
            }
        })
        .catch(() => {
            msg.innerHTML = '<span class="text-danger">حدث خطأ، حاول مرة أخرى</span>';
        });
    }

    function resetCoupon() {
        appliedDiscount = 0;
        document.getElementById('discountRow').style.display = 'none';
        document.getElementById('totalDisplay').textContent  = '$' + originalTotal.toFixed(2);
        document.getElementById('appliedCoupon').value       = '';
    }

    // السماح بالضغط Enter في حقل الكوبون
    document.getElementById('couponInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); applyCoupon(); }
    });
</script>
@endpush

@endsection
