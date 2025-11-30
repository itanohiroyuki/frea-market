@extends('layouts.header-nav')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/mypage.css') }}">
@endsection
@section('content')
    <div class="all-contents">
        <div class="top-content">
            <div class="profile-area">
                <div class="image-area">
                    <img class="profile-image" src="{{ $profile->image_url }}" alt="">
                </div>
                <p class="profile-name">{{ $profile->name }}</p>
            </div>
            <form action="mypage/profile" method="GET">
                <button class="edit-button">プロフィールを編集</button>
            </form>
        </div>
        <div class="category">
            <a class="seller-item {{ $page === 'sell' ? 'active' : '' }}" href="{{ url('/mypage?page=sell') }}">出品した商品</a>
            <a class="buyer-item {{ $page === 'buy' ? 'active' : '' }}" href="{{ url('/mypage?page=buy') }}">購入した商品</a>
        </div>

        <div class="under-content">
            @foreach ($products as $product)
                <div class="product-content">
                    <a href="/item/{{ $product->id }}" class="product-link">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="商品画像" class="product-image" />
                    </a>
                    <div class="detail-content">
                        <p class="product-name">{{ $product->name }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
