@extends('layouts.admin')
@section('title', 'سجل عمليات: ' . $user->name)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">سجل عمليات: {{ $user->name }}</h3>
        <small class="text-muted" dir="ltr">{{ $user->email }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.wallet.adjust-form', $user) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil-square me-1"></i>تعديل الرصيد
        </a>
        <a href="{{ route('admin.wallet.users') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-right me-1"></i>رجوع
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- بطاقة الرصيد --}}
<div class="card border-0 shadow-sm rounded-4 mb-4"
     style="background:linear-gradient(135deg,#1a1f3d 0%,#e8611a 100%)">
    <div class="card-body p-4 text-white text-center">
        <div class="opacity-75 mb-1">الرصيد الحالي</div>
        <div class="fw-bold" style="font-size:2.5rem" dir="ltr">
            ${{ number_format($wallet->balance, 2) }}
        </div>
    </div>
</div>

{{-- جدول العمليات --}}
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">النوع</th>
                        <th>المبلغ</th>
                        <th>الرصيد بعد</th>
                        <th>المصدر</th>
                        <th>الوصف</th>
                        <th class="pe-4">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($transactions as $tx)
                <tr>
                    <td class="ps-4">
                        @if($tx->isCredit())
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-arrow-down-circle me-1"></i>شحن
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                <i class="bi bi-arrow-up-circle me-1"></i>خصم
                            </span>
                        @endif
                    </td>
                    <td class="fw-bold {{ $tx->isCredit() ? 'text-success' : 'text-danger' }}" dir="ltr">
                        {{ $tx->isCredit() ? '+' : '-' }}${{ number_format($tx->amount, 2) }}
                    </td>
                    <td class="text-muted" dir="ltr">${{ number_format($tx->balance_after, 2) }}</td>
                    <td>
                        @php
                            $labels = [
                                'redeem_code'      => ['كود شحن',     'bi-gift',         'text-primary'],
                                'service_purchase' => ['شراء خدمة',   'bi-bag-check',    'text-warning'],
                                'admin_adjustment' => ['تعديل إداري', 'bi-shield-check', 'text-secondary'],
                            ];
                            [$label, $icon, $cls] = $labels[$tx->source_type] ?? [$tx->source_type ?? '—', 'bi-circle', 'text-muted'];
                        @endphp
                        <span class="{{ $cls }}"><i class="bi {{ $icon }} me-1"></i>{{ $label }}</span>
                    </td>
                    <td class="text-muted small">{{ $tx->description ?? '—' }}</td>
                    <td class="pe-4 text-muted small" dir="ltr">
                        {{ $tx->created_at->format('Y-m-d H:i') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">لا توجد عمليات.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-4 py-3 border-top">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>

@endsection
