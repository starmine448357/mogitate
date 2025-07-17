{{-- resources/views/products/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品登録</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">商品名</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">価格</label>
            <input type="number" name="price" id="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">画像</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">説明</label>
            <textarea name="description" id="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label for="seasons" class="form-label">季節</label>
            @foreach ($seasons as $season)
                <div class="form-check">
                    <input type="checkbox" name="seasons[]" value="{{ $season->id }}" class="form-check-input" id="season{{ $season->id }}">
                    <label for="season{{ $season->id }}" class="form-check-label">{{ $season->name }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">登録</button>
    </form>
</div>
@endsection
