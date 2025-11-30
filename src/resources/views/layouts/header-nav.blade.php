<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECHフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/header-nav.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <h1 class="header-title">
                <a class="header-logo" href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="ロゴ" class="img-logo">
                </a>
            </h1>
            <form class="search-form"action="/search" method="GET">
                <input class="input" type="text" name="keyword" placeholder="なにをお探しですか？"
                    value="{{ $keyword ?? '' }}" />
                <input type="hidden" name="tab" value="{{ $tab ?? 'best' }}">
            </form>
            <nav class="header-nav">
                <ul class="header-nav__inner">
                    <li class="nav-list">
                        @auth
                            <form action="/logout" method="post">
                                @csrf
                                <button class="logout">ログアウト</button>
                            </form>
                        @endauth
                        @guest
                            <a class="login" href="/login">ログイン</a>
                        @endguest
                    </li>
                    <li class="nav-list"><a class="mypage" href="/mypage">マイページ</a></li>
                    <li class="nav-list"><a class="sell" href="/sell">出品</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main">
        @yield('content')
    </main>
    @yield('script')
</body>

</html>
