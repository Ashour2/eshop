@extends('layouts.admin')
@section('title', 'إضافة منتج')
@section('content')
<h3 class="fw-bold mb-4">➕ إضافة منتج جديد</h3>

{{-- عرض الأخطاء --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm">
<div class="card-body">

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-bold">اسم المنتج <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="مثال: لابتوب Dell XPS">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">السعر ($) <span class="text-danger">*</span></label>
                <input type="number"
                       name="price"
                       step="0.01"
                       min="0"
                       class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price') }}"
                       placeholder="0.00">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">المخزون <span class="text-danger">*</span></label>
                <input type="number"
                       name="stock"
                       min="0"
                       class="form-control @error('stock') is-invalid @enderror"
                       value="{{ old('stock', 0) }}">
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-8">
                <label class="form-label fw-bold">الصورة</label>
                <input type="file"
                       name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">الوصف</label>
                <textarea name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="4"
                          placeholder="اكتب وصف المنتج هنا...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <div class="form-check form-switch">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox"
                           name="active"
                           value="1"
                           class="form-check-input"
                           id="active"
                           {{ old('active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">منتج نشط (يظهر في المتجر)</label>
                </div>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> حفظ المنتج
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    إلغاء
                </a>
            </div>
        </div>
    </form>

</div>
</div>
@endsection
