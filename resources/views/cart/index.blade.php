@extends('layouts.app')
@section('title', __('shop.cart_title'))
@section('content')
<h2 class="mb-4 fw-bold">🛒 {{ __('shop.cart_title') }}</h2>
@if(empty($cart))
    <div class="alert alert-info">
        {{ __('shop.cart_empty') }}. <a href="{{ route('shop.index') }}">{{ __('shop.shop_now') }}</a>
    </div>
@else
<div class="table-responsive">
    <table class="table table-hover align-middle shadow-sm rounded">
        <thead class="table-dark">
            <tr>
                <th>{{ __('shop.product') }}</th>
                <th>{{ __('shop.price') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($cart as $id => $item)
        <tr>
            <td class="fw-bold">{{ $item['name'] }}</td>
            <td class="text-success fw-bold">{{ number_format($item['price'], 2) }} $</td>
            <td>
                <form action="{{ route('cart.remove', $id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="1" class="text-end fw-bold">{{ __('shop.total') }}:</td>
                <td colspan="2" class="fw-bold text-success fs-5">{{ number_format($total, 2) }} $</td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="d-flex justify-content-between mt-3">
    <form action="{{ route('cart.clear') }}" method="POST">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger">{{ __('shop.clear_cart') }}</button>
    </form>
    <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">
        {{ __('shop.checkout') }} <i class="bi bi-arrow-left"></i>
    </a>
</div>
@endif
@endsection
