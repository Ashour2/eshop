@extends('layouts.admin')
@section('title', 'تعديل رصيد: ' . $user->name)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">✏️ تعديل رصيد: {{ $user->name }}</h3>
    <a href="{{ route('admin.wallet.user-transactions', $user) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-right me-1"></i>رجوع للسجل
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
@endif

<div class="row justify-content-center">
<div class="col-lg-6">
    <div class="card shadow-sm border-0 rounded-4 mb-4"
         style="background:linear-gradient(135deg,#1a1f3d 0%,#e8611a 100%)">
        <div class="card-body p-4 text-white text-center">
            <div class="opacity-75 mb-1">الرصيد الحالي</div>
            <div class="fw-bold" style="font-size:2.2rem" dir="ltr">
                ${{ number_format($wallet->balance, 2) }}
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.wallet.adjust', $user) }}" method="POST">
                @csrf @method('POST')

                @if($errors->any())
                    <div class="alert alert-danger rounded-3 py-2">
                        @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">نوع العملية <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" value="credit"
                                   id="typeCredit" {{ old('type','credit') == 'credit' ? 'checked':'' }}>
                            <label class="form-check-label text-success fw-bold" for="typeCredit">
                                <i class="bi bi-plus-circle me-1"></i>إضافة رصيد
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" value="debit"
                                   id="typeDebit" {{ old('type') == 'debit' ? 'checked':'' }}>
                            <label class="form-check-label text-danger fw-bold" for="typeDebit">
                                <i class="bi bi-dash-circle me-1"></i>خصم رصيد
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">المبلغ ($) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" step="0.01" min="0.01"
                           class="form-control form-control-lg @error('amount') is-invalid @enderror"
                           value="{{ old('amount') }}" placeholder="0.00" required>
                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">
                        سبب التعديل <span class="text-danger">*</span>
                        <span class="text-muted fw-normal small">(مطلوب لأغراض المراجعة)</span>
                    </label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="مثال: تعويض عن خدمة متأخرة، استرجاع طلب #123..."
                              required>{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="bi bi-check-circle me-1"></i>تنفيذ التعديل
                </button>
            </form>
        </div>
    </div>
</div>
</div>

@endsection
