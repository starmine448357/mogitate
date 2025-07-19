@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>商品一覧</h1>

        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">新規作成</a>

        <form action="{{ route('products.search') }}" method="GET" class="mb-4">
            <input type="text" name="keyword" placeholder="商品名で検索" value="{{ request('keyword') }}" class="form-control" style="max-width: 300px; display: inline-block;">
            <button type="submit" class="btn btn-secondary">検索</button>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (isset($keyword))
            <p>「{{ $keyword }}」の検索結果：{{ $products->count() }}件</p>
        @endif

        @if($products->isEmpty())
            <p>商品が登録されていません。</p>
        @else
            <ul style="list-style: none; padding: 0;">
                @foreach($products as $product)
                    <li style="margin-bottom: 20px; border: 1px solid #ccc; padding: 15px; border-radius: 8px;">
                        <a href="{{ route('products.show', $product->id) }}" style="display: flex; align-items: center; gap: 20px; text-decoration: none; color: inherit;">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100px; height: auto; border-radius: 5px;">
                            @endif
                            <div>
                                <h3 style="margin: 0 0 5px 0;">{{ $product->name }}</h3>
                                <p style="margin: 0;">¥{{ number_format($product->price) }}</p>
                            </div>
                        </a>
                        <form action="{{ route('products.delete', $product->id) }}" method="POST" style="margin-top: 10px;" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            <button type="submit" class="btn btn-danger">削除</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
