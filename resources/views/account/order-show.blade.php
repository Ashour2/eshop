@extends('layouts.app')
@section('title', 'تفاصيل الطلب #' . $order->id)
@section('content')

<div class="row justify-content-center">
<div class="col-md-8">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">🧾 تفاصيل الطلب #{{ $order->id }}</h4>
        <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-right"></i> طلباتي
        </a>
    </div>

    {{-- حالة الطلب --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">حالة الطلب</div>
                    <span class="badge bg-{{ $order->status_color }} fs-6 mt-1">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div class="text-end">
                    <div class="text-muted small">تاريخ الطلب</div>
                    <div class="fw-bold">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>

            {{-- شريط تتبع الطلب --}}
            @php
                $steps = ['pending'=>0,'processing'=>1,'shipped'=>2,'delivered'=>3];
                $current = $steps[$order->status] ?? 0;
                $cancelled = $order->status === 'cancelled';
            @endphp

            @if(!$cancelled)
            <div class="mt-4">
                <div class="d-flex justify-content-between position-relative">
                    <div style="position:absolute;top:16px;left:0;right:0;height:4px;background:#e9ecef;z-index:0"></div>
                    <div style="position:absolute;top:16px;left:0;height:4px;background:#0d6efd;z-index:1;width:{{ $current * 33.3 }}%"></div>
                    @foreach(['قيد الانتظار','قيد المعالجة','تم الشحن','تم التسليم'] as $i => $step)
                    <div class="text-center position-relative" style="z-index:2;flex:1">
                        <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center fw-bold"
                             style="width:36px;height:36px;background:{{ $i <= $current ? '#0d6efd' : '#e9ecef' }};color:{{ $i <= $current ? '#fff' : '#999' }}">
                            {{ $i <= $current ? '✓' : ($i+1) }}
                        </div>
                        <div class="small mt-1 {{ $i <= $current ? 'fw-bold text-primary' : 'text-muted' }}">
                            {{ $step }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="alert alert-danger mt-3 mb-0">
                <i class="bi bi-x-circle me-2"></i> هذا الطلب تم إلغاؤه
            </div>
            @endif
        </div>
    </div>

    {{-- معلومات التوصيل --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-transparent fw-bold border-0 pt-4 px-4">
            <i class="bi bi-geo-alt me-2"></i> معلومات التوصيل
        </div>
        <div class="card-body px-4 pb-4">
            <div class="row g-2">
                <div class="col-sm-4 text-muted">الاسم:</div>
                <div class="col-sm-8 fw-bold">{{ $order->customer_name }}</div>
                <div class="col-sm-4 text-muted">البريد:</div>
                <div class="col-sm-8">{{ $order->customer_email }}</div>
                <div class="col-sm-4 text-muted">العنوان:</div>
                <div class="col-sm-8">{{ $order->customer_address }}</div>
            </div>
        </div>
    </div>

    {{-- المنتجات --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent fw-bold border-0 pt-4 px-4">
            <i class="bi bi-box-seam me-2"></i> المنتجات المطلوبة
        </div>
        <div class="card-body px-0 pb-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">المنتج</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4">{{ $item->product_name }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold px-4">المجموع الكلي:</td>
                        <td class="fw-bold text-success fs-5">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
</div>
@endsection
