@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')
<div class="create-container">
    <h2 class="create-title">商品登録</h2>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="create-form">
        @csrf

        <div class="form-group">
            <label>商品名 <span class="required-badge">必須</span></label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="商品名を入力">
            @error('name')<p class="error-message">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>値段 <span class="required-badge">必須</span></label>
            <input type="number" name="price" value="{{ old('price') }}" class="form-control" placeholder="値段を入力">
            @error('price')<p class="error-message">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>商品画像 <span class="required-badge">必須</span></label>

            <div class="image-preview-container">
                <img id="preview" class="image-preview" style="display: none;">
            </div>

            <div class="file-upload-wrapper">
                <label for="image" class="file-label">ファイルを選択</label>
                
                <input type="file" name="image" id="image" class="file-input" accept="image/png, image/jpeg">
                <span id="filename" class="image-filename"></span>
            </div>

            @error('image')<p class="error-message">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label class="season-label">季節 <span class="required-badge">必須</span></label>
            <div class="season-options">
                @foreach($seasons as $season)
                    <label class="season-option">
                        <input type="checkbox" name="seasons[]" value="{{ $season->id }}"
                        {{ in_array($season->id, old('seasons', [])) ? 'checked' : '' }}>
                        {{ $season->name }}
                    </label>
                @endforeach
            </div>
            @error('seasons')<p class="error-message">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>商品説明 <span class="required-badge">必須</span></label>
            <textarea name="description" class="form-control" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
            @error('description')<p class="error-message">{{ $message }}</p>@enderror
        </div>

        <div class="form-buttons">
            <a href="{{ route('products.index') }}" class="cancel-btn">戻る</a>
            <button type="submit" class="submit-btn">登録</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('image');
    const filename = document.getElementById('filename');
    const preview = document.getElementById('preview');

    input.addEventListener('change', function () {
        if (input.files.length > 0) {
            const file = input.files[0];
            filename.textContent = file.name;
            filename.style.display = 'inline';

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            filename.textContent = '';
            filename.style.display = 'none';
            preview.src = '';
            preview.style.display = 'none';
        }
    });
});
</script>
@endsection
