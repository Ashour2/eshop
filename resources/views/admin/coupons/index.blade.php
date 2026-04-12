@extends('layouts.admin')
@section('title', 'الكوبونات')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">🎟️ الكوبونات</h3>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة كوبون
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>الكود</th>
                <th>نوع الخصم</th>
                <th>القيمة</th>
                <th>الحد الأدنى</th>
                <th>الاستخدامات</th>
                <th>الانتهاء</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
        @forelse($coupons as $coupon)
        <tr>
            <td>
                <span class="badge bg-dark fs-6 px-3">{{ $coupon->code }}</span>
            </td>
            <td>{{ $coupon->type === 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}</td>
            <td class="fw-bold text-success">
                {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}
            </td>
            <td>${{ number_format($coupon->min_order, 2) }}</td>
            <td>
                {{ $coupon->used_count }}
                @if($coupon->max_uses > 0)
                    / {{ $coupon->max_uses }}
                @else
                    / ∞
                @endif
            </td>
            <td>
                @if($coupon->expires_at)
                    <span class="{{ $coupon->expires_at->isPast() ? 'text-danger' : 'text-muted' }}">
                        {{ $coupon->expires_at->format('Y-m-d') }}
                    </span>
                @else
                    <span class="text-muted">بلا تاريخ</span>
                @endif
            </td>
            <td>
                <span class="badge bg-{{ $coupon->active ? 'success' : 'secondary' }}">
                    {{ $coupon->active ? 'مفعّل' : 'معطّل' }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.coupons.edit', $coupon) }}"
                   class="btn btn-sm btn-outline-primary">تعديل</a>
                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                      class="d-inline" onsubmit="return confirm('هل تريد حذف هذا الكوبون؟')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted py-4">لا توجد كوبونات</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $coupons->links() }}</div>

@endsection
