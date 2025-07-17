@extends('layouts.app')

@section('content')
<div class="product-detail-card">
    @if ($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-detail-image">
    @endif

    <h2 class="product-detail-name">{{ $product->name }}</h2>
    <p class="product-detail-price">¥{{ number_format($product->price) }}</p>
    <p class="product-detail-description">{{ $product->description }}</p>
    <p class="product-detail-season">
        @if ($product->seasons->isNotEmpty())
            {{ $product->seasons->pluck('name')->join(', ') }}
        @else
            設定なし
        @endif
    </p>

    <div class="product-detail-back">
        <a href="{{ route('products.index') }}" class="product-detail-button">一覧に戻る</a>
    </div>
</div>
@endsection
