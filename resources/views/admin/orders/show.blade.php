@extends('layouts.admin')
@section('title', 'تفاصيل الطلب')
@section('content')
<h3 class="fw-bold mb-4">🧾 طلب رقم #{{ $order->id }}</h3>
<div class="row g-4">
<div class="col-md-5">
    <div class="card shadow-sm">
        <div class="card-header fw-bold">معلومات العميل</div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>الاسم:</strong> {{ $order->customer_name }}</li>
            <li class="list-group-item"><strong>الإيميل:</strong> {{ $order->customer_email }}</li>
            <li class="list-group-item"><strong>العنوان:</strong> {{ $order->customer_address }}</li>
            <li class="list-group-item"><strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</li>
        </ul>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header fw-bold">تغيير الحالة</div>
        <div class="card-body">
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-flex gap-2">
                @csrf @method('PATCH')
                <select name="status" class="form-select">
                    @foreach(['pending'=>'قيد الانتظار','processing'=>'قيد المعالجة','shipped'=>'تم الشحن','delivered'=>'تم التسليم','cancelled'=>'ملغي'] as $val => $label)
                        <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn btn-dark">تحديث</button>
            </form>
        </div>
    </div>
</div>

<div class="col-md-7">
    <div class="card shadow-sm">
        <div class="card-header fw-bold">المنتجات المطلوبة</div>
        <table class="table mb-0">
            <thead><tr><th>المنتج</th><th>السعر</th><th>الكمية</th><th>الإجمالي</th></tr></thead>
            <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ number_format($item->price, 2) }} $</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price * $item->quantity, 2) }} $</td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr><td colspan="3" class="text-end fw-bold">المجموع:</td>
                <td class="fw-bold text-success">{{ number_format($order->total, 2) }} $</td></tr>
            </tfoot>
        </table>
    </div>
</div>
</div>
@endsection
