@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<form class="form" action="/" method="get">
<div class="toppage-list">
    <a class="tab__button {{ $viewType === 'recommend'
     ? 'active' : '' }}" href="{{ url('/?viewType=recommend&keyword=' . request('keyword')) }}">おすすめ</a>
    <a class="tab__button {{ $viewType === 'mylist' ? 'active' : '' }}" href="{{ url('/?viewType=mylist&keyword=' . request('keyword')) }}">マイリスト</a>
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
           @else
           <p>ログインするとマイリストが表示されます。</p>
           @endauth
        @endforelse
    </div>
</div>
@endsection