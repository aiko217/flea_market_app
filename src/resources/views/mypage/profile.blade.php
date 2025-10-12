@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

<form class="form" action="/mypage" method="get">
<div class="user-info">
  <div class="profile__avatar">
        <img src="{{ $user->profile->image ? asset('storage/' . $user->profile->image) : asset('images/default-icon.png') }}" alt="">
  </div>
  <div class="mypage-username">{{ $user->profile->username }}</div> 
    <a href="/mypage/profile" class="submit-profile">プロフィールを編集</a>
</div>
<div class="toppage-list">
    <a class="tab__button {{ $viewType === 'sell'
     ? 'active' : '' }}" href="{{ url('/mypage?viewType=sell') }}">出品した商品</a>
    <a class="tab__button {{ $viewType === 'purchase' ? 'active' : '' }}" href="{{ url('/mypage?viewType=purchase') }}">購入した商品</a>
</div>
<div class="products-row">
        @forelse($items as $item)
            <div class="item-card">
                <a href="/item/{{ $item->id }}">
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"class="item-card__img">
                </a>
                <p>{{ $item->name }}</p>

                @if($item->purchase)
                <span class="sold-label">Sold</span>
                @endif
            </div>
        @empty
           @auth
            <p>商品がありません。</p>
           @endauth
        @endforelse
    </div>
</div>
</form>
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