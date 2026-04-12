@extends('layouts.admin')
@section('title', 'الإشعارات')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">🔔 الإشعارات</h3>
    @if(auth()->user()->notifications->count())
    <form action="{{ route('admin.notifications.clear') }}" method="POST"
          onsubmit="return confirm('حذف كل الإشعارات؟')">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger btn-sm">
            <i class="bi bi-trash"></i> حذف الكل
        </button>
    </form>
    @endif
</div>

@forelse($notifications as $n)
<div class="card border-0 shadow-sm rounded-4 mb-3 {{ $n->read_at ? 'opacity-75' : '' }}">
    <div class="card-body px-4 py-3">
        <div class="d-flex align-items-center gap-3">

            {{-- الأيقونة --}}
            <div class="fs-2" style="min-width:48px;text-align:center">
                {{ $n->data['icon'] ?? '🔔' }}
            </div>

            {{-- المحتوى --}}
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold {{ $n->read_at ? 'text-muted' : '' }}">
                            {{ $n->data['title'] }}
                            @if(!$n->read_at)
                                <span class="badge bg-danger ms-2">جديد</span>
                            @endif
                        </div>
                        <div class="text-muted small mt-1">{{ $n->data['message'] }}</div>
                    </div>
                    <small class="text-muted text-nowrap ms-3">
                        {{ $n->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>

            {{-- زر التوجيه --}}
            <a href="{{ route('admin.notifications.read', $n->id) }}"
               class="btn btn-sm btn-outline-dark text-nowrap">
                عرض <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <i class="bi bi-bell-slash" style="font-size:4rem;color:#ccc"></i>
    <h5 class="mt-3 text-muted">لا توجد إشعارات</h5>
</div>
@endforelse

<div class="mt-3">{{ $notifications->links() }}</div>

@endsection
