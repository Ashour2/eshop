@extends('layouts.app')
@section('title', 'سلة المشتريات')
@section('content')
<h2 class="mb-4 fw-bold">🛒 سلة المشتريات</h2>
@if(empty($cart))
    <div class="alert alert-info">السلة فارغة. <a href="{{ route('shop.index') }}">تسوّق الآن</a></div>
@else
<div class="table-responsive">
    <table class="table table-hover align-middle bg-white shadow-sm rounded">
        <thead class="table-dark">
            <tr>
                <th>المنتج</th><th>السعر</th><th>الكمية</th><th>الإجمالي</th><th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($cart as $id => $item)
        <tr>
            <td>{{ $item['name'] }}</td>
            <td>{{ number_format($item['price'], 2) }} $</td>
            <td>
                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex gap-2">
                    @csrf @method('PATCH')
                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" class="form-control form-control-sm" style="width:70px">
                    <button class="btn btn-sm btn-outline-secondary">تحديث</button>
                </form>
            </td>
            <td>{{ number_format($item['price'] * $item['quantity'], 2) }} $</td>
            <td>
                <form action="{{ route('cart.remove', $id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end fw-bold">المجموع:</td>
                <td colspan="2" class="fw-bold text-success fs-5">{{ number_format($total, 2) }} $</td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="d-flex justify-content-between mt-3">
    <form action="{{ route('cart.clear') }}" method="POST">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger">إفراغ السلة</button>
    </form>
    <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">
        إتمام الطلب <i class="bi bi-arrow-left"></i>
    </a>
</div>
@endif
@endsection
