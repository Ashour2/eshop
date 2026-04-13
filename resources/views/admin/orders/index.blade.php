@extends('layouts.admin')
@section('title', 'الطلبات')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">📋 الطلبات</h3>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.export.excel') }}"
           class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-excel"></i> تصدير Excel
        </a>
        <a href="{{ route('admin.orders.export.pdf') }}"
           class="btn btn-danger btn-sm">
            <i class="bi bi-file-earmark-pdf"></i> تصدير PDF
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
<table class="table table-hover align-middle mb-0">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>العميل</th>
            <th>البريد</th>
            <th>المبلغ</th>
            <th>الخصم</th>
            <th>الحالة</th>
            <th>التاريخ</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @forelse($orders as $order)
    <tr>
        <td><span class="badge bg-light text-dark border">#{{ $order->id }}</span></td>
        <td class="fw-bold">{{ $order->customer_name }}</td>
        <td class="text-muted small">{{ $order->customer_email }}</td>
        <td class="fw-bold text-success">${{ number_format($order->total, 2) }}</td>
        <td>
            @if($order->discount > 0)
                <span class="badge bg-warning text-dark">
                    -${{ number_format($order->discount, 2) }}
                </span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            @php
                $colors = ['pending'=>'warning','processing'=>'info',
                           'shipped'=>'primary','delivered'=>'success','cancelled'=>'danger'];
            @endphp
            <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">
                {{ $order->status_label }}
            </span>
        </td>
        <td class="text-muted small">{{ $order->created_at->format('Y-m-d') }}</td>
        <td>
            <a href="{{ route('admin.orders.show', $order) }}"
               class="btn btn-sm btn-outline-dark">عرض</a>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="text-center text-muted py-4">لا توجد طلبات</td>
    </tr>
    @endforelse
    </tbody>
</table>
</div>
<div class="mt-3">{{ $orders->links() }}</div>

@endsection
