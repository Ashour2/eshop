@extends('layouts.app')
@section('title', 'إتمام الطلب')
@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<h2 class="mb-4 fw-bold">📦 إتمام الطلب</h2>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">ملخص الطلب</div>
    <ul class="list-group list-group-flush">
    @foreach($cart as $item)
        <li class="list-group-item d-flex justify-content-between">
            <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
            <span>{{ number_format($item['price'] * $item['quantity'], 2) }} $</span>
        </li>
    @endforeach
        <li class="list-group-item d-flex justify-content-between fw-bold">
            <span>المجموع</span><span class="text-success">{{ number_format($total, 2) }} $</span>
        </li>
    </ul>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">الاسم الكامل</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">العنوان</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address') }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-success w-100 btn-lg">
                <i class="bi bi-check-circle"></i> تأكيد الطلب
            </button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
