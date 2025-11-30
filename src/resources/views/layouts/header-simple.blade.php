<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECHフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/header-simple.css') }}">
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
        </div>
    </header>
    <main class="main">
        @yield('content')
    </main>
    @yield('script')
</body>

</html>
