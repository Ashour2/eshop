@extends('layouts.app')
@section('title', __('shop.order_placed'))
@section('content')
<div class="text-center py-5">
    <div style="font-size:5rem">🎉</div>
    <i class="bi bi-check-circle-fill text-success" style="font-size:3rem"></i>
    <h2 class="mt-3 fw-bold">{{ __('shop.order_success') }}</h2>
    <p class="text-muted mb-1">{{ __('shop.order_number') }}: <strong class="text-warning">#{{ $order->id }}</strong></p>
    <p class="text-muted mb-4">{{ __('shop.contact_via') }} <strong>{{ $order->customer_email }}</strong></p>

    @if($waUrl)
    <div class="card border-0 rounded-4 mx-auto mb-4 p-4"
         style="max-width:420px;background:var(--rt-card-bg);border:1px solid rgba(37,211,102,.3) !important">
        <div style="font-size:2.5rem">💬</div>
        <h5 class="fw-bold mt-2">{{ __('shop.wa_send_title') }}</h5>
        <p class="text-muted small mb-3">{{ __('shop.wa_send_desc') }}</p>
        <a href="{{ $waUrl }}" target="_blank"
           class="btn fw-bold btn-lg w-100"
           style="background:#25D366;color:#fff;border:none">
            <i class="bi bi-whatsapp me-2"></i> {{ __('shop.wa_send_btn') }}
        </a>
    </div>
    @endif

    <a href="{{ route('shop.index') }}" class="btn btn-outline-light">
        <i class="bi bi-arrow-right me-1"></i> {{ __('shop.back_to_shop') }}
    </a>
</div>
@endsection
