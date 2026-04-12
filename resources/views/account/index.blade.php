@extends('layouts.app')
@section('title', 'حسابي')
@section('content')

<div class="row g-4">

    {{-- ── الشريط الجانبي ─────────────────────────────── --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4 mb-3">
            <div class="mx-auto mb-3 rounded-circle bg-primary d-flex align-items-center justify-content-center"
                 style="width:80px;height:80px;font-size:2rem;color:#fff">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
            <small class="text-muted">{{ $user->email }}</small>
        </div>

        <div class="list-group shadow-sm rounded-4 overflow-hidden">
            <a href="{{ route('account.index') }}"
               class="list-group-item list-group-item-action active fw-bold">
                <i class="bi bi-person me-2"></i> حسابي
            </a>
            <a href="{{ route('account.orders') }}"
               class="list-group-item list-group-item-action">
                <i class="bi bi-receipt me-2"></i> طلباتي
                <span class="badge bg-primary float-start">{{ $orders_count }}</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="list-group-item list-group-item-action text-danger border-0 w-100 text-start">
                    <i class="bi bi-box-arrow-left me-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </div>

    {{-- ── المحتوى الرئيسي ─────────────────────────────── --}}
    <div class="col-md-9">

        {{-- إحصائيات سريعة --}}
        <div class="row g-3 mb-4">
            @foreach([
                ['icon'=>'bi-receipt',    'color'=>'primary', 'val'=>$orders_count,                         'lbl'=>'إجمالي الطلبات'],
                ['icon'=>'bi-cash-stack', 'color'=>'success', 'val'=>'$'.number_format($total_spent, 2),    'lbl'=>'إجمالي الإنفاق'],
            ] as $s)
            <div class="col-sm-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-{{ $s['color'] }} bg-opacity-10 rounded-3 p-3">
                            <i class="bi {{ $s['icon'] }} fs-4 text-{{ $s['color'] }}"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-4">{{ $s['val'] }}</div>
                            <div class="text-muted small">{{ $s['lbl'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- تعديل البيانات --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent fw-bold border-0 pt-4 px-4">
                <i class="bi bi-pencil-square me-2"></i> تعديل البيانات الشخصية
            </div>
            <div class="card-body px-4 pb-4">
                <form action="{{ route('account.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> حفظ التغييرات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- تغيير كلمة المرور --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent fw-bold border-0 pt-4 px-4">
                <i class="bi bi-lock me-2"></i> تغيير كلمة المرور
            </div>
            <div class="card-body px-4 pb-4">
                <form action="{{ route('account.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">كلمة المرور الحالية</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-shield-lock"></i> تغيير كلمة المرور
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- آخر الطلبات --}}
        @if($recent_orders->count())
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent fw-bold border-0 pt-4 px-4 d-flex justify-content-between">
                <span><i class="bi bi-clock-history me-2"></i> آخر الطلبات</span>
                <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
            </div>
            <div class="card-body px-4 pb-4">
                @foreach($recent_orders as $order)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <span class="fw-bold">#{{ $order->id }}</span>
                        <small class="text-muted ms-2">{{ $order->created_at->format('Y-m-d') }}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-success fw-bold">${{ number_format($order->total, 2) }}</span>
                        <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">تفاصيل</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
