@extends('layouts.admin')
@section('title', 'الرئيسية')
@section('content')
<h3 class="fw-bold mb-4">مرحباً، {{ auth()->user()->name }} 👋</h3>

<div class="row g-3 mb-5">
    @php
    $cards = [
        ['label' => 'المنتجات',   'val' => $stats['products'],       'icon' => 'bi-box-seam',    'color' => 'primary'],
        ['label' => 'الطلبات',    'val' => $stats['orders'],         'icon' => 'bi-receipt',     'color' => 'success'],
        ['label' => 'الإيرادات',  'val' => number_format($stats['revenue'],2).' $', 'icon' => 'bi-cash-stack', 'color' => 'warning'],
        ['label' => 'بانتظار',    'val' => $stats['pending_orders'], 'icon' => 'bi-clock-history','color' => 'danger'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-{{ $card['color'] }} bg-opacity-10 rounded p-3">
                    <i class="bi {{ $card['icon'] }} fs-3 text-{{ $card['color'] }}"></i>
                </div>
                <div>
                    <div class="text-muted small">{{ $card['label'] }}</div>
                    <div class="fw-bold fs-4">{{ $card['val'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<h5 class="fw-bold mb-3">آخر الطلبات</h5>
<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-dark"><tr><th>#</th><th>العميل</th><th>المبلغ</th><th>الحالة</th><th></th></tr></thead>
        <tbody>
        @forelse($recent_orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ number_format($order->total, 2) }} $</td>
            <td><span class="badge bg-secondary">{{ $order->status_label }}</span></td>
            <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-dark">عرض</a></td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center text-muted">لا توجد طلبات</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
