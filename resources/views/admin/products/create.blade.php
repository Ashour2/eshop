@extends('layouts.admin')
@section('title', 'إضافة خدمة جديدة')
@section('content')
<h3 class="fw-bold mb-4">➕ إضافة خدمة جديدة</h3>

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

    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            {{-- Arabic Name --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">اسم الخدمة (عربي) <span class="text-danger">*</span></label>
                <input type="text" name="name_ar"
                       class="form-control @error('name_ar') is-invalid @enderror"
                       value="{{ old('name_ar') }}"
                       placeholder="مثال: تصميم موقع إلكتروني" dir="rtl">
                @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- English Name --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">Service Name (English)</label>
                <input type="text" name="name_en"
                       class="form-control @error('name_en') is-invalid @enderror"
                       value="{{ old('name_en') }}"
                       placeholder="e.g. Website Design" dir="ltr">
                @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Price --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">السعر ($) <span class="text-danger">*</span></label>
                <input type="number" name="price" step="0.01" min="0"
                       class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price') }}" placeholder="0.00">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Category --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">القسم</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">— بدون قسم —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) old('category_id') === (string) $cat->id ? 'selected' : '' }}>
                            {{ $cat->name_ar }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Arabic Description --}}
            <div class="col-12">
                <label class="form-label fw-bold">الوصف (عربي)</label>
                <textarea name="description_ar"
                          class="form-control @error('description_ar') is-invalid @enderror"
                          rows="3" placeholder="اكتب وصف الخدمة بالعربية..." dir="rtl">{{ old('description_ar') }}</textarea>
                @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- English Description --}}
            <div class="col-12">
                <label class="form-label fw-bold">Description (English)</label>
                <textarea name="description_en"
                          class="form-control @error('description_en') is-invalid @enderror"
                          rows="3" placeholder="Write the service description in English..." dir="ltr">{{ old('description_en') }}</textarea>
                @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <div class="form-check form-switch">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1"
                           class="form-check-input" id="active"
                           {{ old('active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">خدمة نشطة (تظهر في الموقع)</label>
                </div>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> حفظ الخدمة
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </div>
    </form>

</div>
</div>
@endsection
