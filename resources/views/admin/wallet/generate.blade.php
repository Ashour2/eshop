@extends('layouts.admin')
@section('title', 'توليد أكواد الشحن')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">🎁 توليد أكواد الشحن</h3>
    <a href="{{ route('admin.wallet.codes') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-list-ul me-1"></i>كل الأكواد
    </a>
</div>

<div class="row g-4">

    {{-- ── فورم التوليد ── --}}
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4">إعدادات الكود</h6>
                <form action="{{ route('admin.wallet.generate') }}" method="POST">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 py-2">
                            @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">المبلغ ($) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" step="0.01" min="1" max="10000"
                               class="form-control" value="{{ old('amount', 50) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">عدد الأكواد <span class="text-danger">*</span></label>
                        <input type="number" name="count" min="1" max="100"
                               class="form-control" value="{{ old('count', 1) }}" required>
                        <div class="form-text">أقصى 100 كود دفعة واحدة</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ملاحظة الدفعة (اختياري)</label>
                        <input type="text" name="batch_note" class="form-control"
                               placeholder="مثال: دفعة يوليو 2026"
                               value="{{ old('batch_note') }}" maxlength="255">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">تاريخ الانتهاء (اختياري)</label>
                        <input type="datetime-local" name="expires_at" class="form-control"
                               value="{{ old('expires_at') }}">
                        <div class="form-text">اتركه فارغاً إذا الكود بلا مدة انتهاء</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-lightning-charge-fill me-1"></i>توليد الأكواد
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ── الأكواد الناتجة ── --}}
    @if(isset($generated) && isset($codes) && count($codes))
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-success text-white fw-bold rounded-top-4 d-flex justify-content-between align-items-center px-4 py-3">
                <span><i class="bi bi-check-circle me-2"></i>تم توليد {{ count($codes) }} كود بنجاح!</span>
                <button class="btn btn-light btn-sm" onclick="exportCSV()">
                    <i class="bi bi-download me-1"></i>تصدير CSV
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0" id="codesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>الكود</th>
                            <th>المبلغ</th>
                            <th class="pe-4">نسخ</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($codes as $i => $code)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                        <td>
                            <code class="fs-6 fw-bold" style="color:#e8611a;letter-spacing:.1rem">
                                {{ $code->code }}
                            </code>
                        </td>
                        <td class="fw-bold" dir="ltr">${{ number_format($code->amount, 2) }}</td>
                        <td class="pe-4">
                            <button class="btn btn-outline-secondary btn-sm"
                                    onclick="copyCode('{{ $code->code }}', this)"
                                    title="نسخ">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
function copyCode(code, btn) {
    navigator.clipboard.writeText(code).then(() => {
        btn.innerHTML = '<i class="bi bi-clipboard-check text-success"></i>';
        setTimeout(() => btn.innerHTML = '<i class="bi bi-clipboard"></i>', 2000);
    });
}

function exportCSV() {
    const rows = [['#', 'الكود', 'المبلغ']];
    document.querySelectorAll('#codesTable tbody tr').forEach((tr, i) => {
        const cells = tr.querySelectorAll('td');
        rows.push([i + 1, cells[1].textContent.trim(), cells[2].textContent.trim()]);
    });
    const csv = rows.map(r => r.join(',')).join('\n');
    const blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'redeem-codes-' + Date.now() + '.csv';
    a.click();
}
</script>
@endpush

@endsection
