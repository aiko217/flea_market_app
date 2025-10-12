@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
<div class="purchase-left">
    <div class="product-info">
        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="product-image">
        <div class="product-detail">
            <h2 class="product-name">{{ $item->name}}</h2>
            <p class="product-price">¥{{ number_format($item->price) }}</p>
        </div>
    </div>
    <form action="/purchase/{{ $item->id }}" method="post">
        @csrf
    <div class="payment-method">
        <h3>支払い方法</h3>
        <select name="payment_method"  id="paymentSelect" class="payment-select">
            <option value="">選択してください</option>
            <option value="コンビニ払い">コンビニ支払い</option>
            <option value="クレジットカード">カード支払い</option>
        </select>
        @error('payment_method')
            <p class="error">{{ $message }}</p>
        @enderror
    </div>
    <div class="shipping-address">
        <div class="shipping-header">
        <h3>配送先</h3>
        <a href="/purchase/address/{{ $item->id }}" class="change-link">変更する</a>
        </div>
            @if (!empty($address['postal_code']))
            <p>〒 {{ $address['postal_code'] }}</p>
            <p>{{ $address['address'] }} {{ $address['building'] ?? '' }}</p>
            @else
            <p>配送先が登録されていません</p>
            @endif
        @error('shipping_address')
            <p class="error">{{ $message }}</p>
        @enderror
    </div>
</div>
        
<div class="purchase-right">
    <div class="summary-box">
        <div class="summary-row">
            <span>商品代金</span>
            <span>¥{{ number_format($item->price) }}</span>
        </div>
<div class="summary-row">
    <span>支払い方法</span>
    <span id="selectedPayment">選択されていません</span>
</div>
    </div>
    <button type="submit" class="purchase-btn">購入する</button>
</div>
</form>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('paymentSelect');
    const display = document.getElementById('selectedPayment');

    select.addEventListener('change', function () {
        display.textContent = select.value ? select.value : "選択されていません";
    });
});
</script>
@endsection
