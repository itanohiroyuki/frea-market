@extends('layouts.header-simple')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register-content">
        <div class="register-content__inner">
            <h2 class="register-content__title">会員登録</h2>
            <form class="register-form" action="/register" method="POST">
                @csrf
                <div class="input-wrapper">
                    <label class="label" for="name">ユーザー名</label>
                    <input class="input" type="text" name="name" id="name" value="" />
                    @error('name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

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

                <div class="input-wrapper">
                    <label class="label" for="password_confirmation">確認用パスワード</label>
                    <input class="input" type="password" name="password_confirmation" id="password_confirmation"
                        value="" />
                    @error('password_confirmation')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <button class="register-btn" type="submit">登録する</button>
                <a class="login-move" href="/login">ログインはこちら</a>
            </form>
        </div>
    </div>
@endsection
