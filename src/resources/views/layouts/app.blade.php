<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
    <div class="header__inner">
    <img src="images/logo.svg" alt="アプリロゴ">
        <form action="/items/search" class="item_search__form" method="get">
        <input type="text" class="item_search-input" name="keyword" placeholder="何をお探しですか?" value="{{ old('keyword', request('keyword')) }}">
        </form>
        @auth
        <nav class="header__nav">
          <ul class="header__list">
            <li class="header__list-item">
            <form action="/logout" class="header__form" method="post">
            @csrf
            <button class="header__form--logout" type="submit">ログアウト</button>
            </form>
            </li>
            <li class="header__list-item">
            <div class="mypage__link">
            <a class="mypage__button-submit" href="/profile">マイページ</a>
            </div>
            </li>
            <li class="header__list-item">
            <div class="sell__link">
            <a class="sell__button-submit" href="/sell">出品</a>
            </div>
            </li>
          </ul>
        </nav>
            @else
        <nav class="header__nav">
          <ul class="header__list">
            <li class="header__list-item">
            <div class="sell__link">
            <a class="login__button-submit" href="/login">ログイン</a>
            </div>
            </li>
            <li class="header__list-item">
            <div class="mypage__link">
            <a class="mypage__button-submit" href="/profile">マイページ</a>
            </div>
            </li>
            <li class="header__list-item">
            <div class="sell__link">
            <a class="sell__button-submit" href="/sell">出品</a>
            </div>
            </li>
          </ul>
        </nav>
            @endauth
        </nav>
    </div>
    @yield('content')
</body>
</html>