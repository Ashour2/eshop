@extends('layouts.admin')
@section('title', 'الأقسام')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi bi-tags"></i> أقسام الخدمات</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> إضافة قسم</a>
</div>
<div class="card shadow-sm">
<table class="table table-hover align-middle mb-0">
    <thead class="table-dark"><tr><th>#</th><th>الأيقونة</th><th>الاسم (عربي)</th><th>Name (English)</th><th>الرابط</th><th>عدد الخدمات</th><th>إجراءات</th></tr></thead>
    <tbody>
    @forelse($categories as $c)
    <tr>
        <td>{{ $c->id }}</td>
        <td><i class="bi {{ $c->icon }} fs-5"></i></td>
        <td>{{ $c->name_ar }}</td>
        <td>{{ $c->name_en ?? '—' }}</td>
        <td><code>{{ $c->slug }}</code></td>
        <td><span class="badge bg-secondary">{{ $c->products_count }}</span></td>
        <td>
            <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
            <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف هذا القسم؟ سيتم إلغاء ربط خدماته دون حذفها.')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">حذف</button>
            </form>
        </td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted py-4">لا توجد أقسام بعد</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
