@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="profile-form__content">
  <div class="profile-form__heading">
    <h2>プロフィール設定</h2>
  </div>
  <form class="form" action="/mypage/profile" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

  <div class="profile-edit__wrapper">
    <div class="profile-edit__avatar">
        <img id="avatarPreview"
             src="@if (old('image'))
              {{ asset('storage/' . old('image')) }}
              @elseif (!empty($profile) && $profile->image)
              {{ asset('storage/' . $profile->image) }}
              @else
              {{ asset('images/avatar-placeholder.png') }}
               @endif
               "
             alt="">
    </div>
    <div class="profile-edit__controls">
        <label for="image" class="profile__button-update">
        画像を選択する
        </label>
        <input id="image" name="image" type="file" accept="image/*" class="profile-edit__file-input" hidden />
    </div>
  </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">ユーザー名</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="username" value="{{ old('username', $profile->username ?? '') }}" />
        </div>
        <div class="form__error">
          @error('username')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">郵便番号</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}" />
        </div>
        <div class="form__error">
          @error('postal_code')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">住所</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="address" value="{{ old('address', $profile->address) }}" />
        </div>
        <div class="form__error">
          @error('address')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">建物名</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="building" value="{{ old('building', $profile->building) }}"/>
        </div>
      </div>
    </div>
    <div class="form__button">
      <button class="form__button-submit" type="submit">更新する</button>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('image');
  const preview = document.getElementById('avatarPreview');


  input.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) return;

    const reader = new FileReader();
    reader.onload = function (ev) {
      preview.src = ev.target.result;
    };
    reader.readAsDataURL(file);
  });
});
</script>
@endsection