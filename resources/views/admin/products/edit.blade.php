@extends('layouts.admin')
@section('title', 'تعديل خدمة')
@section('content')
<h3 class="fw-bold mb-4">✏️ تعديل: {{ $product->name }}</h3>
<div class="card shadow-sm">
<div class="card-body">
<form action="{{ route('admin.products.update', $product) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-3">

        {{-- Arabic Name --}}
        <div class="col-md-6">
            <label class="form-label fw-bold">اسم الخدمة (عربي) <span class="text-danger">*</span></label>
            <input type="text" name="name_ar"
                   class="form-control @error('name_ar') is-invalid @enderror"
                   value="{{ old('name_ar', $product->name_ar) }}"
                   dir="rtl" required>
            @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- English Name --}}
        <div class="col-md-6">
            <label class="form-label fw-bold">Service Name (English)</label>
            <input type="text" name="name_en"
                   class="form-control @error('name_en') is-invalid @enderror"
                   value="{{ old('name_en', $product->name_en) }}"
                   dir="ltr">
            @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Price --}}
        <div class="col-md-4">
            <label class="form-label">السعر ($)</label>
            <input type="number" name="price" step="0.01"
                   class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>

        {{-- Category --}}
        <div class="col-md-4">
            <label class="form-label fw-bold">القسم</label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                <option value="">— بدون قسم —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (string) old('category_id', $product->category_id) === (string) $cat->id ? 'selected' : '' }}>
                        {{ $cat->name_ar }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Arabic Description --}}
        <div class="col-12">
            <label class="form-label fw-bold">الوصف (عربي)</label>
            <textarea name="description_ar" class="form-control" rows="3" dir="rtl">{{ old('description_ar', $product->description_ar) }}</textarea>
        </div>

        {{-- English Description --}}
        <div class="col-12">
            <label class="form-label fw-bold">Description (English)</label>
            <textarea name="description_en" class="form-control" rows="3" dir="ltr">{{ old('description_en', $product->description_en) }}</textarea>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1"
                       class="form-check-input" id="active"
                       {{ $product->active ? 'checked' : '' }}>
                <label class="form-check-label" for="active">خدمة نشطة</label>
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
