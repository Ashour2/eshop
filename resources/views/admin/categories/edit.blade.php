@extends('layouts.admin')
@section('title', 'تعديل قسم')
@section('content')
<h3 class="fw-bold mb-4">✏️ تعديل: {{ $category->name_ar }}</h3>

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

    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf @method('PUT')

        <div class="row g-3">

            {{-- Arabic Name --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">اسم القسم (عربي) <span class="text-danger">*</span></label>
                <input type="text" name="name_ar"
                       class="form-control @error('name_ar') is-invalid @enderror"
                       value="{{ old('name_ar', $category->name_ar) }}"
                       dir="rtl" required>
                @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- English Name --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">Category Name (English)</label>
                <input type="text" name="name_en"
                       class="form-control @error('name_en') is-invalid @enderror"
                       value="{{ old('name_en', $category->name_en) }}"
                       dir="ltr">
                @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Slug --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">الرابط (Slug) <span class="text-danger">*</span></label>
                <input type="text" name="slug"
                       class="form-control @error('slug') is-invalid @enderror"
                       value="{{ old('slug', $category->slug) }}"
                       dir="ltr" required>
                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Icon --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">أيقونة القسم (Bootstrap Icons)</label>
                <div class="input-group">
                    <span class="input-group-text"><i id="icon-preview" class="bi {{ old('icon', $category->icon) }} fs-5"></i></span>
                    <input type="text" name="icon" id="icon-input"
                           class="form-control @error('icon') is-invalid @enderror"
                           value="{{ old('icon', $category->icon) }}"
                           list="icon-suggestions" dir="ltr">
                </div>
                <datalist id="icon-suggestions">
                    <option value="bi-grid"><option value="bi-window-stack"><option value="bi-server">
                    <option value="bi-palette"><option value="bi-phone"><option value="bi-brush">
                    <option value="bi-search"><option value="bi-cloud-check"><option value="bi-cpu">
                    <option value="bi-code-slash"><option value="bi-camera"><option value="bi-megaphone">
                </datalist>
                <div class="form-text">
                    اسم أيقونة من مكتبة
                    <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a> مثل bi-grid
                </div>
                @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary px-4">تحديث</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </div>
    </form>

</div>
</div>

@push('scripts')
<script>
    const iconInput = document.getElementById('icon-input');
    const iconPreview = document.getElementById('icon-preview');
    iconInput.addEventListener('input', () => {
        iconPreview.className = 'bi ' + (iconInput.value.trim() || 'bi-grid') + ' fs-5';
    });
</script>
@endpush
@endsection
