@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="sidebar">
        <h2 class="sidebar-title">商品一覧</h2>
        <form action="{{ route('products.search') }}" method="GET">
            <input type="text" name="keyword" placeholder="商品名で検索" value="{{ request('keyword') }}">
            <button type="submit">検索</button>
            <div class="sort">
                <label for="sort">価格順で表示</label>
                <select name="sort" id="sort" onchange="this.form.submit()">
                    <option value="">選択してください</option>
                    <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>安い順に表示</option>
                    <option value="desc" {{ request('sort') === 'desc' ? 'selected' : '' }}>高い順に表示</option>
                </select>
            </div>
        </form>
    @if(request('sort'))
        <div class="sort-badge">
            @if(request('sort') === 'asc')
                安い順に表示
            @elseif(request('sort') === 'desc')
                高い順に表示
            @endif
            <a href="{{ route('products.search', ['keyword' => request('keyword')]) }}" class="sort-clear-btn">×</a>
        </div>
    @endif

    </div>

    <div class="product-area">
        <div class="product-area-header">
            @if (session('success'))
                <div class="alert alert-success" style="text-align: left;">
                    {{ session('success') }}
                </div>
            @endif
            <a href="{{ route('products.create') }}" class="add-button">+ 商品を追加</a>
        </div>

        <div class="product-list">
            @if($products->isEmpty())
                <p>商品が登録されていません。</p>
            @else
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product->id) }}" class="product-card">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @endif
                        <div class="product-info">
                            <div class="product-name-price">
                                <p>{{ $product->name }}</p>
                                <p>¥{{ number_format($product->price) }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>

        <div class="pagination-wrapper">
            {{ $products->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
@endsection
