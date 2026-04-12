@extends('layouts.admin')
@section('title', 'التقييمات')
@section('content')

<h3 class="fw-bold mb-4">⭐ التقييمات</h3>

<div class="card shadow-sm border-0 rounded-4">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>المنتج</th>
                <th>العميل</th>
                <th>التقييم</th>
                <th>التعليق</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
        @forelse($reviews as $review)
        <tr>
            <td>{{ $review->id }}</td>
            <td class="fw-bold">{{ $review->product->name }}</td>
            <td>{{ $review->user->name }}</td>
            <td>
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                @endfor
                <span class="ms-1 fw-bold">{{ $review->rating }}/5</span>
            </td>
            <td>
                <span class="text-muted">
                    {{ $review->comment ? Str::limit($review->comment, 50) : '—' }}
                </span>
            </td>
            <td>
                <span class="badge bg-{{ $review->approved ? 'success' : 'secondary' }}">
                    {{ $review->approved ? 'ظاهر' : 'مخفي' }}
                </span>
            </td>
            <td class="text-muted small">{{ $review->created_at->format('Y-m-d') }}</td>
            <td>
                <form action="{{ route('admin.reviews.toggle', $review) }}"
                      method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm btn-outline-{{ $review->approved ? 'secondary' : 'success' }}">
                        {{ $review->approved ? 'إخفاء' : 'إظهار' }}
                    </button>
                </form>
                <form action="{{ route('admin.reviews.destroy', $review) }}"
                      method="POST" class="d-inline"
                      onsubmit="return confirm('حذف هذا التقييم؟')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted py-4">لا توجد تقييمات</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $reviews->links() }}</div>

@endsection
