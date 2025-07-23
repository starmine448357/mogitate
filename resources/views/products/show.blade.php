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
            <div class="form-image">
                @php
                    use Illuminate\Support\Facades\Storage;
                    $imagePath = $product->image;
                @endphp

                @if ($imagePath && Storage::disk('public')->exists($imagePath))
                    <img id="preview" src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}">
                @else
                    <img id="preview" src="{{ asset('storage/images/default.png') }}" alt="画像なし">
                @endif

                <label class="file-label">
                    ファイルを選択
                    <input type="file" name="image" id="imageInput" hidden>
                </label>

                <span class="filename">未選択</span>

                @error('image')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-fields">
                <label>商品名</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror

                <label>値段</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}">
                @error('price') <div class="form-error">{{ $message }}</div> @enderror

                <label>季節</label>
                <div class="season-options">
                    @foreach ($seasons as $season)
                        <label>
                            <input type="checkbox" name="seasons[]" value="{{ $season->id }}"
                            {{ in_array($season->id, old('seasons', $product->seasons->pluck('id')->toArray())) ? 'checked' : '' }}>
                            {{ $season->name }}
                        </label>
                    @endforeach
                </div>
                @error('seasons') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-description">
            <label>商品説明</label>
            <textarea name="description">{{ old('description', $product->description) }}</textarea>
            @error('description') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-buttons">
            <a href="{{ route('products.index') }}" class="back-button">戻る</a>
            <button type="submit" class="submit-button">変更を保存</button>
        </div>

        <div class="delete-button-wrapper">
            <form action="{{ route('products.delete', $product->id) }}" method="POST" class="delete-form" onsubmit="return confirm('本当に削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-button">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
@endsection

@section('js')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('preview').src = URL.createObjectURL(file);
        document.querySelector('.filename').textContent = file.name;
    }
});
</script>
@endsection
