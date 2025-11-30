@extends('layouts.header-simple')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-content">
        <div class="login-content__inner">
            <h2 class="login-content__title">ログイン</h2>
            <form class="login-form" action="/login" method="post">
                @csrf
                <div class="input-wrapper">
                    <label class="label" for="email">メールアドレス</label>
                    <input class="input" type="email" name="email" id="email" value="" />
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-wrapper">
                    <label class="label" for="password">パスワード</label>
                    <input class="input" type="password" name="password" id="password" value="" />
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <button class="login-btn" type="submit">ログインする</button>
                <a class="register-move" href="/register">会員登録はこちら</a>
            </form>
        </div>
    </div>
@endsection
