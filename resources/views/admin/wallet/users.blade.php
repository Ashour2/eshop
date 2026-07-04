@extends('layouts.admin')
@section('title', 'أرصدة العملاء')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">👥 أرصدة العملاء</h3>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.wallet.codes') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-list-ul me-1"></i>قائمة الأكواد
        </a>
        <a href="{{ route('admin.wallet.generate-form') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-lightning-charge-fill me-1"></i>توليد أكواد
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- بحث --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.wallet.users') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control"
                       placeholder="ابحث بالاسم أو البريد الإلكتروني..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">بحث</button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('admin.wallet.users') }}" class="btn btn-outline-secondary w-100">مسح</a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الرصيد الحالي</th>
                        <th class="pe-4">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="ps-4 text-muted">{{ $user->id }}</td>
                    <td class="fw-bold">{{ $user->name }}</td>
                    <td class="text-muted small" dir="ltr">{{ $user->email }}</td>
                    <td>
                        <span class="fw-bold fs-5 {{ ($user->wallet?->balance ?? 0) > 0 ? 'text-success' : 'text-muted' }}" dir="ltr">
                            ${{ number_format($user->wallet?->balance ?? 0, 2) }}
                        </span>
                    </td>
                    <td class="pe-4">
                        <a href="{{ route('admin.wallet.user-transactions', $user) }}"
                           class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-clock-history me-1"></i>السجل
                        </a>
                        <a href="{{ route('admin.wallet.adjust-form', $user) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil-square me-1"></i>تعديل
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">لا يوجد مستخدمون.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-4 py-3 border-top">{{ $users->links() }}</div>
        @endif
    </div>
</div>

@endsection
