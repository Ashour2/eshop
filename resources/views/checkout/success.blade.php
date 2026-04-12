@extends('layouts.app')
@section('title', 'تم الطلب')
@section('content')
<div class="text-center py-5">
    <i class="bi bi-check-circle-fill text-success" style="font-size:5rem"></i>
    <h2 class="mt-3 fw-bold">تم استلام طلبك بنجاح! 🎉</h2>
    <p class="text-muted">رقم الطلب: <strong>#{{ $order->id }}</strong></p>
    <p>سيتم التواصل معك على: <strong>{{ $order->customer_email }}</strong></p>
    <a href="{{ route('shop.index') }}" class="btn btn-primary mt-3">العودة للتسوق</a>
</div>
@endsection
