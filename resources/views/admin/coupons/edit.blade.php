@extends('layouts.admin')
@section('title', 'تعديل كوبون')
@section('content')

<h3 class="fw-bold mb-4">✏️ تعديل كوبون: {{ $coupon->code }}</h3>

<div class="card shadow-sm border-0 rounded-4">
<div class="card-body p-4">
<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label fw-bold">كود الكوبون</label>
            <input type="text" name="code"
                   class="form-control text-uppercase @error('code') is-invalid @enderror"
                   value="{{ old('code', $coupon->code) }}"
                   style="letter-spacing:2px">
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">نوع الخصم</label>
            <select name="type" class="form-select" id="typeSelect" onchange="updateLabel()">
                <option value="percentage" {{ $coupon->type === 'percentage' ? 'selected' : '' }}>نسبة مئوية %</option>
                <option value="fixed"      {{ $coupon->type === 'fixed'      ? 'selected' : '' }}>مبلغ ثابت $</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold" id="valueLabel">
                {{ $coupon->type === 'percentage' ? 'قيمة الخصم (%)' : 'قيمة الخصم ($)' }}
            </label>
            <input type="number" name="value" step="0.01" min="0.01"
                   class="form-control @error('value') is-invalid @enderror"
                   value="{{ old('value', $coupon->value) }}">
            @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">الحد الأدنى للطلب ($)</label>
            <input type="number" name="min_order" step="0.01" min="0"
                   class="form-control" value="{{ old('min_order', $coupon->min_order) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">الحد الأقصى للاستخدام</label>
            <input type="number" name="max_uses" min="0"
                   class="form-control" value="{{ old('max_uses', $coupon->max_uses) }}">
            <div class="form-text">مستخدم حتى الآن: {{ $coupon->used_count }}</div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">تاريخ الانتهاء</label>
            <input type="date" name="expires_at"
                   class="form-control"
                   value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
        </div>

        <div class="col-12">
            <div class="form-check form-switch">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1"
                       class="form-check-input" id="active"
                       {{ $coupon->active ? 'checked' : '' }}>
                <label class="form-check-label" for="active">كوبون مفعّل</label>
            </div>
        </div>

        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save"></i> تحديث
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </div>
    </div>
</form>
</div>
</div>

<script>
function updateLabel() {
    const type = document.getElementById('typeSelect').value;
    document.getElementById('valueLabel').textContent =
        type === 'percentage' ? 'قيمة الخصم (%)' : 'قيمة الخصم ($)';
}
</script>
@endsection
