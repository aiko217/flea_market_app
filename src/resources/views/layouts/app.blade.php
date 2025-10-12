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
    <img src="{{ asset('storage/images/logo.svg') }}" alt="アプリロゴ" class="img-content" />
        <form action="/items/search" class="item_search__form" method="get">
        <input type="text" class="item_search-input" name="keyword" placeholder="なにをお探しですか?" value="{{ old('keyword', request('keyword')) }}">
        <input type="hidden" name="viewType" value="{{ $viewType ?? 'recommend' }}">
        </form>
        <nav class="header__nav">
          <ul class="header__list">
          @auth
            <li>
            <form action="/logout" class="header__form" method="post">
            @csrf
            <button type="submit" class="logout__button-submit">ログアウト</button>
            </form>
            </li>
            <li>
            <a class="mypage__button-submit" href="/mypage">マイページ</a>
            </li>
            <li>
            <a class="sell__button-submit" href="/sell">出品</a>
            </li>
            @else
            <li>
            <a class="login__button-submit" href="/login">ログイン</a>
            </li>
            <li>
            <a class="mypage__button-submit" href="/mypage">マイページ</a>
            </li>
            <li>
            <a class="sell__button-submit" href="/sell">出品</a>
            </li>
         @endauth
         </ul>
        </nav>
    </div>
    </header>
    @yield('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>