@extends('layouts.header-nav')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address_change.css') }}">
@endsection

@section('content')
    <div class="edit-content">
        <form class="edit-form" action="{{ url('/purchase/address/' . $product->id) }}" method="POST">
            @csrf
            <h2 class="title">住所の変更</h2>
            <div class="address-group">
                <label class="label">郵便番号</label>
                <input class="input" type="text" name="postal_code">
                <p class="error">
                    @error('postal_code')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="address-group">
                <label class="label">住所</label>
                <input class="input" type="text" name="city">
                <p class="error">
                    @error('city')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="address-group">
                <label class="label">建物名</label>
                <input class="input" type="text" name="building">
                <p class="error">
                    @error('building')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <button type="submit" class="update-button">更新する</button>
        </form>
    </div>
@endsection
