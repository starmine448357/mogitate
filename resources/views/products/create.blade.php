@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')
<div class="create-container">
    <h1 class="create-title">商品登録</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="create-form">
        @csrf

        {{-- 商品名 --}}
        <div class="form-group">
            <label for="name">商品名 <span class="required-badge">必須</span></label>
            <input type="text" name="name" id="name" placeholder="商品名を入力" value="{{ old('name') }}" class="form-control">
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- 値段 --}}
        <div class="form-group">
            <label for="price">値段 <span class="required-badge">必須</span></label>
            <input type="number" name="price" id="price" placeholder="値段を入力" value="{{ old('price') }}" class="form-control">
            @error('price')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品画像 --}}
        <div class="form-group">
            <label for="image">商品画像 <span class="required-badge">必須</span></label>

            {{-- プレビュー表示 --}}
            @if(session('image_temp_url'))
                <div id="image-preview-container" class="image-preview-container">
                    <img id="image-preview" class="image-preview" src="{{ session('image_temp_url') }}" alt="選択された画像" />
                </div>
            @else
                <div id="image-preview-container" class="image-preview-container" style="display:none;">
                    <img id="image-preview" class="image-preview" alt="選択された画像" />
                </div>
            @endif

            {{-- ボタンとファイル名 --}}
            <div class="file-upload-wrapper">
                <label for="image" class="file-label">ファイルを選択</label>
                @if(session('image_temp_name'))
                    <span id="image-filename" class="image-filename">{{ session('image_temp_name') }}</span>
                @else
                    <span id="image-filename" class="image-filename" style="display:none;">未選択</span>
                @endif
            </div>

            <input type="file" name="image" id="image" class="file-input" accept="image/*">

            @error('image')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- 季節 --}}
        <div class="form-group">
            <label>季節 <span class="required-badge">必須</span><span class="note-red">複数選択可</span></label>
            <div class="season-options">
                @foreach (['春', '夏', '秋', '冬'] as $index => $season)
                    <label class="season-option">
                        <input type="checkbox" name="seasons[]" value="{{ $index + 1 }}"
                            {{ is_array(old('seasons')) && in_array($index + 1, old('seasons')) ? 'checked' : '' }}>
                        {{ $season }}
                    </label>
                @endforeach
            </div>
            @error('seasons')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品説明 --}}
        <div class="form-group">
            <label for="description">商品説明 <span class="required-badge">必須</span></label>
            <textarea name="description" id="description" placeholder="商品の説明を入力" class="form-control">{{ old('description') }}</textarea>
            @error('description')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- ボタン --}}
        <div class="form-buttons">
            <a href="{{ route('products.index') }}" class="btn cancel-btn">戻る</a>
            <button type="submit" class="btn submit-btn">登録</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('image');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');
    const filenameText = document.getElementById('image-filename');

    fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];
        if (file) {
            filenameText.textContent = file.name;
            filenameText.style.display = 'inline';

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            filenameText.textContent = '';
            filenameText.style.display = 'none';
            previewContainer.style.display = 'none';
        }
    });
});
</script>
@endsection
