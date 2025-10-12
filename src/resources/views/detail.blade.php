@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="product-detail">
    <div class="left-content">
    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="img-content">
    </div>
    <div class="right-content">
        <div class="product-title">
          <h2 class="name">{{ $item->name }}</h2>
          <p class="brand-name">{{ $item->brand }}</p>
          <p class="price">¥{{ number_format($item->price) }}(税込)</p>
          <div class="icon">
            <button type="button"  class="favorite-btn" data-item-id="{{ $item->id }}">
                <img src="{{ asset('storage/images/purchase/favorite.png') }}" alt="お気に入り" class="favorite-icon {{ $item->isFavoritedBy(Auth::user()) ? 'favorited' : '' }}">
                <span class="favorite-count">{{ $item->favorites_count }}</span>
            </button>
            <div class="comment-count">
                <img src="{{ asset('storage/images/purchase/comment.png') }}" alt="コメント" class="comment-icon">
                <span>{{ $item->comments_count }}</span>
            </div>
          </div>
        <a href="/purchase/{{ $item->id }}" class="submit-purchase">購入手続きへ  
        </a>
        </div>
        <div class="product-description">
            <h3>商品説明</h3>
            <p>{{ $item->description }}</p>
        </div>
        <div class="product-info">
            <h3>商品の情報</h3>
            <div class="categories">
             <h4>カテゴリ-</h4>
            @foreach($item->categories as $category)
                <span>{{ $category->category }}</span>
            @endforeach  
            </div>
            <div class="condition">
                <h4>商品の状態</h4>
                <p>{{ $item->condition_label }}</p>
            </div>
        </div>
        <div class="product-comments">
            <h3>コメント</h3><span class="comments-count">({{ $item->comments->count() }})</span>
            <ul>
                @foreach($item->comments()->latest()->get() as $comment)
                <li class="comment-list">
                    <div class="comment-user">
                        @if($comment->user->profile && $comment->user->profile->image)
                        <img src="{{ asset('storage/' . $comment->user->profile->image) }}" alt="{{ $comment->user->profile->username }}" class="comment-user__icon">
                        @else
                        <img src="{{ asset('images/default-icon.png') }}" alt="デフォルトアイコン" class="comment-user__icon">
                        @endif
                        <p class="comment-user__name">{{ $comment->user->profile->username }}</p>
                    </div>
                    <p class="comment-content">{{ $comment->comment }}</p>
                </li>
                @endforeach
            </ul>
        <div class="comment-input">
        <h3>商品へのコメント</h3>
        <form action="/comment/{{ $item->id }}" method="post">
        @csrf
        <textarea name="comment" class="detail-form__textarea"></textarea>
        @error('comment')
            <p class="text-red-500">{{ $message }}</p>
       @enderror
       <button  class="detail-form__button-comment"type="submit">コメントを送信する</button>
        </form>
        </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.favorite-btn', function(e){
    e.preventDefault();

    var btn = $(this);
    var itemId = btn.data('item-id');

    $.ajax({
        url: '/favorite/' + itemId,
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(res){
            btn.find('.favorite-icon').toggleClass('favorited', res.favorited);
            btn.find('.favorite-count').text(res.favorites_count);
        },
        error: function(err){
            if(err.status === 401){
                window.location.href = err.responseJSON.login_url;
            } else {
                console.error(err);
            }
        }
    });
});
</script>
@endsection


