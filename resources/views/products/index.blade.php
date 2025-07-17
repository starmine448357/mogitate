{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>商品一覧</h1>

        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">新規作成</a>

        @if($products->isEmpty())
            <p>商品が登録されていません。</p>
        @else
            <ul>
                @foreach($products as $product)
                    @php $id = $product->id; @endphp
                    <li>
                        <a href="{{ route('products.show', $id) }}">
                            {{ $product->name }} - ¥{{ number_format($product->price) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
