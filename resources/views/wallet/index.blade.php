@extends('layouts.app')
@section('title', 'محفظتي')

@section('content')
<div class="row justify-content-center g-4">

    {{-- ══ بطاقة الرصيد ══ --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow rounded-4 h-100"
             style="background: linear-gradient(135deg,#1a1f3d 0%,#e8611a 100%);">
            <div class="card-body p-4 text-white text-center d-flex flex-column justify-content-center">
                <div class="mb-2" style="font-size:2.5rem">💳</div>
                <div class="opacity-75 mb-1" style="font-size:.9rem">رصيدك الحالي</div>
                <div class="fw-bold" style="font-size:2.8rem" dir="ltr">
                    ${{ number_format($wallet->balance, 2) }}
                </div>
                <div class="mt-3">
                    <button class="btn btn-light btn-sm fw-bold px-4"
                            data-bs-toggle="modal" data-bs-target="#walletModal">
                        <i class="bi bi-plus-circle me-1"></i> شحن رصيد
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ سجل العمليات ══ --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2 text-warning"></i>سجل العمليات
                </h5>
            </div>
            <div class="card-body p-0">

                @if($transactions->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-receipt" style="font-size:3rem;opacity:.3"></i>
                        <p class="mt-2">لا توجد عمليات بعد.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
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
                            @foreach($transactions as $tx)
                            <tr>
                                <td class="ps-4">
                                    @if($tx->isCredit())
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-arrow-down-circle me-1"></i>شحن
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
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
                                        $sourceLabels = [
                                            'redeem_code'       => ['text' => 'كود شحن',      'icon' => 'bi-gift',        'class' => 'text-primary'],
                                            'service_purchase'  => ['text' => 'شراء خدمة',    'icon' => 'bi-bag-check',   'class' => 'text-warning'],
                                            'admin_adjustment'  => ['text' => 'تعديل إداري',  'icon' => 'bi-shield-check','class' => 'text-secondary'],
                                        ];
                                        $src = $sourceLabels[$tx->source_type] ?? ['text' => $tx->source_type ?? '—', 'icon' => 'bi-circle', 'class' => 'text-muted'];
                                    @endphp
                                    <span class="{{ $src['class'] }}">
                                        <i class="bi {{ $src['icon'] }} me-1"></i>{{ $src['text'] }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $tx->description ?? '—' }}</td>
                                <td class="pe-4 text-muted small" dir="ltr">
                                    {{ $tx->created_at->format('Y-m-d H:i') }}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($transactions->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $transactions->links() }}
                    </div>
                    @endif
                @endif

            </div>
        </div>
    </div>

</div>

@endsection
