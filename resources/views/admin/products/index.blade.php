@extends('layouts.admin')
@section('title', 'المنتجات')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">📦 المنتجات</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> إضافة منتج</a>
</div>
<div class="card shadow-sm">
<table class="table table-hover align-middle mb-0">
    <thead class="table-dark"><tr><th>#</th><th>الصورة</th><th>الاسم</th><th>السعر</th><th>المخزون</th><th>الحالة</th><th>إجراءات</th></tr></thead>
    <tbody>
    @foreach($products as $p)
    <tr>
        <td>{{ $p->id }}</td>
        <td>
            @if($p->image)
                <img src="{{ $p->image_url }}" width="50" height="50" style="object-fit:cover;border-radius:6px">
            @else <span class="text-muted">—</span> @endif
        </td>
        <td>{{ $p->name }}</td>
        <td>{{ number_format($p->price, 2) }} $</td>
        <td>{{ $p->stock }}</td>
        <td>
            <span class="badge bg-{{ $p->active ? 'success' : 'secondary' }}">
                {{ $p->active ? 'نشط' : 'مخفي' }}
            </span>
        </td>
        <td>
            <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
            <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد الحذف؟')">
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
