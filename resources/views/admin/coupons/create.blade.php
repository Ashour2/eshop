@extends('layouts.admin')
@section('title', 'إضافة كوبون')
@section('content')

<h3 class="fw-bold mb-4">🎟️ إضافة كوبون جديد</h3>

<div class="card shadow-sm border-0 rounded-4">
<div class="card-body p-4">
<form action="{{ route('admin.coupons.store') }}" method="POST">
    @csrf
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label fw-bold">كود الكوبون</label>
            <input type="text" name="code"
                   class="form-control text-uppercase @error('code') is-invalid @enderror"
                   value="{{ old('code') }}"
                   placeholder="مثال: SAVE20"
                   style="letter-spacing:2px">
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">نوع الخصم</label>
            <select name="type" class="form-select" id="typeSelect" onchange="updateLabel()">
                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>نسبة مئوية %</option>
                <option value="fixed"      {{ old('type') === 'fixed'      ? 'selected' : '' }}>مبلغ ثابت $</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold" id="valueLabel">قيمة الخصم (%)</label>
            <input type="number" name="value" step="0.01" min="0.01"
                   class="form-control @error('value') is-invalid @enderror"
                   value="{{ old('value') }}" placeholder="10">
            @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">الحد الأدنى للطلب ($)</label>
            <input type="number" name="min_order" step="0.01" min="0"
                   class="form-control" value="{{ old('min_order', 0) }}" placeholder="0">
            <div class="form-text">0 = بلا حد أدنى</div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">الحد الأقصى للاستخدام</label>
            <input type="number" name="max_uses" min="0"
                   class="form-control" value="{{ old('max_uses', 0) }}" placeholder="0">
            <div class="form-text">0 = استخدام غير محدود</div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">تاريخ الانتهاء</label>
            <input type="date" name="expires_at"
                   class="form-control @error('expires_at') is-invalid @enderror"
                   value="{{ old('expires_at') }}"
                   min="{{ now()->addDay()->format('Y-m-d') }}">
            @error('expires_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <div class="form-check form-switch">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1"
                       class="form-check-input" id="active" checked>
                <label class="form-check-label" for="active">كوبون مفعّل</label>
            </div>
        </div>

        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save"></i> حفظ الكوبون
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
