@extends('layouts.admin')
@section('title', 'الطلبات')
@section('content')
<h3 class="fw-bold mb-4">📋 الطلبات</h3>
<div class="card shadow-sm">
<table class="table table-hover align-middle mb-0">
    <thead class="table-dark"><tr><th>#</th><th>العميل</th><th>البريد</th><th>المبلغ</th><th>الحالة</th><th>التاريخ</th><th></th></tr></thead>
    <tbody>
    @forelse($orders as $order)
    <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->customer_name }}</td>
        <td>{{ $order->customer_email }}</td>
        <td>{{ number_format($order->total, 2) }} $</td>
        <td>
            @php $colors = ['pending'=>'warning','processing'=>'info','shipped'=>'primary','delivered'=>'success','cancelled'=>'danger']; @endphp
            <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">{{ $order->status_label }}</span>
        </td>
        <td>{{ $order->created_at->format('Y-m-d') }}</td>
        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-dark">عرض</a></td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted">لا توجد طلبات</td></tr>
    @endforelse
    </tbody>
</table>
</div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
