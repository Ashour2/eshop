@extends('layouts.app')
@section('title', 'طلباتي')
@section('content')

<div class="row g-4">

    {{-- الشريط الجانبي --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4 mb-3">
            <div class="mx-auto mb-3 rounded-circle bg-primary d-flex align-items-center justify-content-center"
                 style="width:80px;height:80px;font-size:2rem;color:#fff">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <h5 class="fw-bold mb-0">{{ auth()->user()->name }}</h5>
            <small class="text-muted">{{ auth()->user()->email }}</small>
        </div>
        <div class="list-group shadow-sm rounded-4 overflow-hidden">
            <a href="{{ route('account.index') }}"
               class="list-group-item list-group-item-action">
                <i class="bi bi-person me-2"></i> حسابي
            </a>
            <a href="{{ route('account.orders') }}"
               class="list-group-item list-group-item-action active fw-bold">
                <i class="bi bi-receipt me-2"></i> طلباتي
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="list-group-item list-group-item-action text-danger border-0 w-100 text-start">
                    <i class="bi bi-box-arrow-left me-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </div>

    {{-- قائمة الطلبات --}}
    <div class="col-md-9">
        <h4 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i> طلباتي</h4>

        @forelse($orders as $order)
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold mb-1">طلب رقم #{{ $order->id }}</h6>
                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $order->created_at->format('Y-m-d — H:i') }}
                        </small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $order->status_color }} mb-1">{{ $order->status_label }}</span>
                        <div class="fw-bold text-success">${{ number_format($order->total, 2) }}</div>
                    </div>
                </div>

                <hr>

                {{-- المنتجات --}}
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($order->items as $item)
                    <span class="badge bg-light text-dark border">
                        {{ $item->product_name }} × {{ $item->quantity }}
                    </span>
                    @endforeach
                </div>

                <a href="{{ route('account.orders.show', $order->id) }}"
                   class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-eye"></i> عرض التفاصيل
                </a>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-bag-x" style="font-size:4rem;color:#ccc"></i>
            <h5 class="mt-3 text-muted">لا توجد طلبات حتى الآن</h5>
            <a href="{{ route('shop.index') }}" class="btn btn-primary mt-2">ابدأ التسوق</a>
        </div>
        @endforelse

        <div class="mt-3">{{ $orders->links() }}</div>
    </div>

</div>
@endsection
