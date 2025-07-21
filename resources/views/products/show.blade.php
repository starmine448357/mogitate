@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="product-edit-container">
    <div class="breadcrumb">
        <a href="{{ route('products.index') }}">商品一覧</a> > {{ $product->name }}
    </div>

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="form-main">
            <!-- 左：画像 -->
            <div class="form-image">
                <img id="preview" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                
                <label class="file-label">
                    ファイルを選択
                    <input type="file" name="image" id="imageInput" hidden>
                </label>
                <span class="filename">{{ $product->image ? basename($product->image) : '未選択' }}</span>
            </div>

            <!-- 右：フォーム入力 -->
            <div class="form-fields">
                <label>商品名</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}">

                <label>値段</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}">

            <label>季節</label>
            <div class="season-options">
            @foreach ($seasons as $season)
                <label>
                    <input type="checkbox" name="seasons[]" value="{{ $season->id }}"
                        {{ $product->seasons->contains($season->id) ? 'checked' : '' }}>
                    {{ $season->name }}
                </label>
            @endforeach
            </div>
            </div>
        </div>

        <!-- 商品説明 -->
        <div class="form-description">
            <label>商品説明</label>
            <textarea name="description">{{ old('description', $product->description) }}</textarea>
        </div>

        <!-- ボタン -->
        <div class="form-buttons">
            <a href="{{ route('products.index') }}" class="back-button">戻る</a>
            <button type="submit" class="submit-button">変更を保存</button>
        </div>
    </form>
</div>
@endsection
@section('js')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const preview = document.getElementById('preview');
        preview.src = URL.createObjectURL(file);

        // ファイル名の表示
        const filename = document.querySelector('.filename');
        filename.textContent = file.name;
    }
});
</script>
@endsection
