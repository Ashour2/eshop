@extends('layouts.admin')
@section('title', 'تعديل منتج')
@section('content')
<h3 class="fw-bold mb-4">✏️ تعديل: {{ $product->name }}</h3>
<div class="card shadow-sm">
<div class="card-body">
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">اسم المنتج</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">السعر ($)</label>
            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">المخزون</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <div class="col-md-8">
            <label class="form-label">تغيير الصورة</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @if($product->image)
                <img src="{{ $product->image_url }}" width="80" class="mt-2 rounded">
            @endif
        </div>
        <div class="col-12">
            <label class="form-label">الوصف</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" name="active" class="form-check-input" id="active" {{ $product->active ? 'checked' : '' }}>
                <label class="form-check-label" for="active">منتج نشط</label>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">تحديث</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </div>
    </div>
</form>
</div>
</div>
@endsection
