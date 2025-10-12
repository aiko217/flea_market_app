@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-form__content">
  <h2 class="sell-form__title">商品の出品</h2>

<form action="/sell" method="post" enctype="multipart/form-data" class="sell-form">
    @csrf
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="form-group">
    <label class="form-label">商品画像</label>
    <div class="image-upload-box">
<img id="itemPreview" style="display:none;"
alt="プレビュー画像">
        <label for="image" class="image-upload-label" id="imageLabel">画像を選択する</label>
        <input id="image" name="image" type="file" accept="image/*" class="item__file-input" hidden />
        <div class="form__error">
          @error('image')
          {{ $message }}
          @enderror
        </div>
    </div>
</div>
<div class="form-group">
<h3 class="item-detail">商品の詳細</h3>
    <label for="categories">カテゴリ</label>
    <div class="category-list">
        @foreach ($categories as $category)
            <input type="checkbox" name="categories[]" id="category-{{ $category->id }}" value="{{ $category->id }}"
            @if(collect(old('categories'))->contains($category->id)) checked @endif>
            <label for="category-{{ $category->id }}" class="category-label">
                {{ $category->category }}
            </label>
        @endforeach
    </div>
    <div class="form__error">
          @error('categories')
          {{ $message }}
          @enderror
    </div>
</div>
<div class="form-group">
    <label class="form-label">商品の状態</label>
    <select name="condition_id" class="select-box">
        <option value="">選択してください</option>
        <option value="1" {{ old('condition_id') == 1 ? 'selected' : '' }}>良好</option>
        <option value="2" {{ old('condition_id') == 2 ? 'selected' : '' }}>目立った傷や汚れなし</option>
        <option value="3" {{ old('condition_id') == 3 ? 'selected' : '' }}>やや傷や汚れあり</option>
        <option value="4" {{ old('condition_id') == 4 ? 'selected' : '' }}>状態が悪い</option>
    </select>
    <div class="form__error">
          @error('condition')
          {{ $message }}
          @enderror
    </div>
</div>
<h3 class="item-detail">商品名と説明</h3>
<div class="form-group">
    <label class="form-label">商品名</label>
    <input type="text" name="name" class="input-box" value="{{ old('name') }}">
    <div class="form__error">
          @error('name')
          {{ $message }}
          @enderror
    </div>
    <label class="form-label">ブランド名</label>
    <input type="text" name="brand" class="input-box" value="{{ old('brand') }}">
    <label class="form-label">商品の説明</label>
    <textarea name="description" class="textarea-box">{{ old('description') }}</textarea>
    <div class="form__error">
          @error('description')
          {{ $message }}
          @enderror
    </div>
    <label class="form-label">販売価格</label>
    <div class="price-input">
    <input type="number" name="price" class="input-box" value="{{ old('price') }}">
    </div>
    <div class="form__error">
          @error('price')
          {{ $message }}
          @enderror
    </div>
</div>
    <div class="form-group">
        <button type="submit" class="submit-button">出品する</button>
    </div>
</form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const imageInput = document.getElementById('image');
  const preview = document.getElementById('itemPreview');
  const label = document.getElementById('imageLabel');

  // ファイル選択時の処理
  imageInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
      label.style.display = 'none'; // ボタンを隠す
    }
  });

  // プレビュー画像クリックで再アップロード
  preview.addEventListener('click', function() {
    imageInput.click();
  });
});
</script>

@endsection