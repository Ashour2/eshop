@extends('layouts.admin')
@section('title', 'الخدمات')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">💻 الخدمات الرقمية</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> إضافة خدمة</a>
</div>
<div class="card shadow-sm">
<table class="table table-hover align-middle mb-0">
    <thead class="table-dark"><tr><th>#</th><th>الاسم</th><th>القسم</th><th>السعر</th><th>الحالة</th><th>إجراءات</th></tr></thead>
    <tbody>
    @foreach($products as $p)
    <tr>
        <td>{{ $p->id }}</td>
        <td>{{ $p->name }}</td>
        <td>{{ $p->category?->name ?? '—' }}</td>
        <td>{{ number_format($p->price, 2) }} $</td>
        <td>
            <span class="badge bg-{{ $p->active ? 'success' : 'secondary' }}">
                {{ $p->active ? 'نشطة' : 'مخفية' }}
            </span>
        </td>
        <td>
            <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
            <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف هذه الخدمة؟')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">حذف</button>
            </form>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
