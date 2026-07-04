@extends('layouts.admin')
@section('title', 'إعدادات الموقع')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">⚙️ إعدادات الموقع</h3>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

{{-- ══ واتساب ══════════════════════════════════════════════ --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-header fw-bold px-4 py-3">
        <i class="bi bi-whatsapp text-success me-2"></i> رقم واتساب الشركة
    </div>
    <div class="card-body p-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <label class="form-label fw-bold">الرقم (بدون + ومع رمز الدولة)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input type="text" name="whatsapp_number" class="form-control"
                           value="{{ $whatsapp }}" placeholder="970594048945" dir="ltr">
                </div>
                <div class="form-text">مثال: 970594048945 (فلسطين)</div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">معاينة الرابط</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                    <input type="text" class="form-control text-muted" dir="ltr"
                           value="https://wa.me/{{ $whatsapp }}" readonly id="waPreview">
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <a href="https://wa.me/{{ $whatsapp }}" target="_blank"
                   class="btn btn-success w-100" id="waTestBtn">
                    <i class="bi bi-whatsapp"></i> اختبار الرابط
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ══ السلايدرات ══════════════════════════════════════════ --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-header fw-bold px-4 py-3 d-flex justify-content-between align-items-center">
        <span><i class="bi bi-images me-2"></i> سلايدرات الصفحة الرئيسية</span>
        <button type="button" class="btn btn-sm btn-outline-light" onclick="addSlide()">
            <i class="bi bi-plus-lg"></i> إضافة سلايد
        </button>
    </div>
    <div class="card-body p-4" id="slidersContainer">

        @foreach($sliders as $i => $slide)
        <div class="slide-item border rounded-4 p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold text-muted small">سلايد {{ $i + 1 }}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSlide(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">العنوان (عربي)</label>
                    <input type="text" name="title_ar[]" class="form-control"
                           value="{{ $slide['title_ar'] }}" placeholder="العنوان بالعربي">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Title (English)</label>
                    <input type="text" name="title_en[]" class="form-control" dir="ltr"
                           value="{{ $slide['title_en'] }}" placeholder="Slide title in English">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">الوصف (عربي)</label>
                    <input type="text" name="subtitle_ar[]" class="form-control"
                           value="{{ $slide['subtitle_ar'] }}" placeholder="وصف قصير بالعربي">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Subtitle (English)</label>
                    <input type="text" name="subtitle_en[]" class="form-control" dir="ltr"
                           value="{{ $slide['subtitle_en'] }}" placeholder="Short description in English">
                </div>

                {{-- الصورة: رابط أو رفع --}}
                <div class="col-12">
                    <label class="form-label small fw-bold">الصورة</label>

                    {{-- التبديل --}}
                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-sm btn-primary img-tab active"
                                onclick="switchTab(this, 'url', {{ $i }})">
                            <i class="bi bi-link-45deg"></i> رابط URL
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-light img-tab"
                                onclick="switchTab(this, 'file', {{ $i }})">
                            <i class="bi bi-upload"></i> رفع من الجهاز
                        </button>
                    </div>

                    {{-- حقل الرابط --}}
                    <div id="url-panel-{{ $i }}" class="img-panel">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                            <input type="url" name="bg[]" class="form-control url-input" dir="ltr"
                                   value="{{ !empty($slide['bg']) && !str_starts_with($slide['bg'], '/storage') ? $slide['bg'] : '' }}"
                                   placeholder="https://..."
                                   oninput="previewFromUrl(this, {{ $i }})">
                        </div>
                    </div>

                    {{-- حقل الرفع --}}
                    <div id="file-panel-{{ $i }}" class="img-panel d-none">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-folder2-open"></i></span>
                            <input type="file" name="bg_file[]" class="form-control"
                                   accept="image/*" onchange="previewFromFile(this, {{ $i }})">
                        </div>
                        <div class="form-text">JPG, PNG, WebP — حد أقصى 3MB</div>
                        {{-- hidden للاحتفاظ بالصورة الحالية إذا لم يتم الرفع --}}
                        <input type="hidden" name="bg_existing[]" value="{{ $slide['bg'] ?? '' }}">
                    </div>

                    {{-- معاينة الصورة الحالية --}}
                    <div id="preview-{{ $i }}" class="mt-2 rounded-3 overflow-hidden"
                         style="{{ !empty($slide['bg']) ? '' : 'display:none' }}; height:100px; max-width:300px">
                        <img id="preview-img-{{ $i }}"
                             src="{{ $slide['bg'] ?? '' }}"
                             class="w-100 h-100" style="object-fit:cover"
                             onerror="this.parentElement.style.display='none'">
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary px-5 fw-bold">
        <i class="bi bi-save"></i> حفظ الإعدادات
    </button>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">إلغاء</a>
</div>

</form>

@push('scripts')
<script>
// واتساب
document.querySelector('input[name="whatsapp_number"]').addEventListener('input', function() {
    var num = this.value.replace(/\D/g, '');
    document.getElementById('waPreview').value = 'https://wa.me/' + num;
    document.getElementById('waTestBtn').href = 'https://wa.me/' + num;
});

// تبديل بين URL ورفع
function switchTab(btn, mode, index) {
    var slideItem = btn.closest('.slide-item');
    slideItem.querySelectorAll('.img-tab').forEach(function(b) {
        b.classList.remove('btn-primary');
        b.classList.add('btn-outline-light');
    });
    btn.classList.add('btn-primary');
    btn.classList.remove('btn-outline-light');

    var urlPanel  = document.getElementById('url-panel-' + index);
    var filePanel = document.getElementById('file-panel-' + index);

    if (mode === 'url') {
        urlPanel.classList.remove('d-none');
        filePanel.classList.add('d-none');
        // عطل حقل الرفع
        filePanel.querySelector('input[type="file"]').disabled = true;
        urlPanel.querySelector('input[type="url"]').disabled = false;
    } else {
        filePanel.classList.remove('d-none');
        urlPanel.classList.add('d-none');
        filePanel.querySelector('input[type="file"]').disabled = false;
        urlPanel.querySelector('input[type="url"]').disabled = true;
    }
}

// معاينة من URL
function previewFromUrl(input, index) {
    var preview    = document.getElementById('preview-' + index);
    var previewImg = document.getElementById('preview-img-' + index);
    if (input.value) {
        previewImg.src = input.value;
        preview.style.display = '';
    } else {
        preview.style.display = 'none';
    }
}

// معاينة من ملف
function previewFromFile(input, index) {
    var file = input.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview    = document.getElementById('preview-' + index);
        var previewImg = document.getElementById('preview-img-' + index);
        previewImg.src = e.target.result;
        preview.style.display = '';
    };
    reader.readAsDataURL(file);
}

// إضافة سلايد جديد
var slideCount = {{ count($sliders) }};
function addSlide() {
    var idx = slideCount++;
    var container = document.getElementById('slidersContainer');
    var html = `
    <div class="slide-item border rounded-4 p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="fw-bold text-muted small">سلايد جديد</span>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSlide(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold">العنوان (عربي)</label>
                <input type="text" name="title_ar[]" class="form-control" placeholder="العنوان بالعربي">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">Title (English)</label>
                <input type="text" name="title_en[]" class="form-control" dir="ltr" placeholder="Slide title in English">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">الوصف (عربي)</label>
                <input type="text" name="subtitle_ar[]" class="form-control" placeholder="وصف قصير بالعربي">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">Subtitle (English)</label>
                <input type="text" name="subtitle_en[]" class="form-control" dir="ltr" placeholder="Short description in English">
            </div>
            <div class="col-12">
                <label class="form-label small fw-bold">الصورة</label>
                <div class="d-flex gap-2 mb-3">
                    <button type="button" class="btn btn-sm btn-primary img-tab active"
                            onclick="switchTab(this, 'url', ${idx})">
                        <i class="bi bi-link-45deg"></i> رابط URL
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light img-tab"
                            onclick="switchTab(this, 'file', ${idx})">
                        <i class="bi bi-upload"></i> رفع من الجهاز
                    </button>
                </div>
                <div id="url-panel-${idx}" class="img-panel">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-image"></i></span>
                        <input type="url" name="bg[]" class="form-control url-input" dir="ltr"
                               placeholder="https://..."
                               oninput="previewFromUrl(this, ${idx})">
                    </div>
                </div>
                <div id="file-panel-${idx}" class="img-panel d-none">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-folder2-open"></i></span>
                        <input type="file" name="bg_file[]" class="form-control" accept="image/*"
                               onchange="previewFromFile(this, ${idx})" disabled>
                    </div>
                    <div class="form-text">JPG, PNG, WebP — حد أقصى 3MB</div>
                    <input type="hidden" name="bg_existing[]" value="">
                </div>
                <div id="preview-${idx}" class="mt-2 rounded-3 overflow-hidden"
                     style="display:none; height:100px; max-width:300px">
                    <img id="preview-img-${idx}" src="" class="w-100 h-100" style="object-fit:cover">
                </div>
            </div>
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
}

// حذف سلايد
function removeSlide(btn) {
    if (document.querySelectorAll('.slide-item').length <= 1) {
        alert('يجب أن يكون هناك سلايد واحد على الأقل');
        return;
    }
    btn.closest('.slide-item').remove();
}
</script>
@endpush

@endsection
