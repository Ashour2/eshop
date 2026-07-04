@extends('layouts.admin')
@section('title', 'إدارة أكواد الشحن')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">🎁 أكواد الشحن</h3>
    <a href="{{ route('admin.wallet.generate-form') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>توليد أكواد
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3">
        {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- فلاتر البحث --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.wallet.codes') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="ابحث بالكود..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">كل الحالات</option>
                    <option value="unused"   {{ request('status') == 'unused'   ? 'selected':'' }}>غير مستخدم</option>
                    <option value="used"     {{ request('status') == 'used'     ? 'selected':'' }}>مستخدم</option>
                    <option value="disabled" {{ request('status') == 'disabled' ? 'selected':'' }}>معطّل</option>
                    <option value="expired"  {{ request('status') == 'expired'  ? 'selected':'' }}>منتهي الصلاحية</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">فلترة</button>
            </div>
            @if(request()->hasAny(['search','status']))
            <div class="col-md-2">
                <a href="{{ route('admin.wallet.codes') }}" class="btn btn-outline-secondary w-100">مسح</a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">الكود</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th>استُخدم بواسطة</th>
                        <th>تاريخ الاستخدام</th>
                        <th>الانتهاء</th>
                        <th>الملاحظة</th>
                        <th class="pe-4">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($codes as $code)
                <tr>
                    <td class="ps-4">
                        <code style="color:#e8611a;letter-spacing:.08rem">{{ $code->code }}</code>
                    </td>
                    <td class="fw-bold" dir="ltr">${{ number_format($code->amount, 2) }}</td>
                    <td>
                        @if($code->is_used)
                            <span class="badge bg-secondary">مستخدم</span>
                        @elseif($code->is_disabled)
                            <span class="badge bg-danger">معطّل</span>
                        @elseif($code->isExpired())
                            <span class="badge bg-warning text-dark">منتهي</span>
                        @else
                            <span class="badge bg-success">متاح</span>
                        @endif
                    </td>
                    <td>{{ $code->usedByUser?->name ?? '—' }}</td>
                    <td class="text-muted small" dir="ltr">
                        {{ $code->used_at?->format('Y-m-d H:i') ?? '—' }}
                    </td>
                    <td class="text-muted small" dir="ltr">
                        {{ $code->expires_at?->format('Y-m-d') ?? '—' }}
                    </td>
                    <td class="text-muted small">{{ $code->batch_note ?? '—' }}</td>
                    <td class="pe-4">
                        @if(!$code->is_used)
                        <form action="{{ route('admin.wallet.disable', $code) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm {{ $code->is_disabled ? 'btn-outline-success' : 'btn-outline-danger' }}"
                                    onclick="return confirm('{{ $code->is_disabled ? 'تفعيل الكود؟' : 'تعطيل الكود؟' }}')">
                                <i class="bi bi-{{ $code->is_disabled ? 'toggle-off' : 'toggle-on' }}"></i>
                                {{ $code->is_disabled ? 'تفعيل' : 'تعطيل' }}
                            </button>
                        </form>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">لا توجد أكواد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($codes->hasPages())
        <div class="px-4 py-3 border-top">{{ $codes->links() }}</div>
        @endif
    </div>
</div>

@endsection
