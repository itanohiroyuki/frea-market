@extends('layouts.header-nav')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
    <div class="product-content">
        <div class="product-image-area">
            <img class="image" src="{{ asset('storage/' . $product->image) }}" alt="商品画像">
        </div>
        <div class="product-description-area">
            <div class="product-top">
                <h2 class="product-name">{{ $product->name }}</h2>
                <p class="product-brand">{{ $product->brand }}</p>
                <p class="product-price">¥{{ number_format($product->price) }}<span class="tax">(税込)</span>
                </p>
                <div class="product-actions">
                    <form action="/item/like/{{ $product->id }}" method="POST">
                        @csrf
                        <button type="submit" class="like-action">
                            @if ($isLiked)
                                <img class="icon-image" src="{{ asset('/images/ハートロゴ_ピンク.png') }}" alt="">
                            @else
                                <img class="icon-image" src="{{ asset('/images/ハートロゴ_デフォルト.png') }}" alt="">
                            @endif
                            <span class="like-counter">{{ $product->likedBy->count() }}</span>
                        </button>
                    </form>
                    <button type="submit" class="comment-action">
                        <img class="icon-image" src="{{ asset('/images/ふきだしのアイコン.png') }}" alt="">
                        <span class="comment-counter">{{ $product->comments->count() }}</span>
                    </button>
                </div>
                <div class="button-area">
                    <form action="/purchase/{{ $product->id }}" method="GET">
                        <button class="purchase-button">購入手続きへ</button>
                    </form>
                </div>
            </div>
            <div class="product-description">
                <h3 class="title">商品説明</h3>
                <p class="description">{{ $product->description }}</p>
            </div>
            <div class="product-info">
                <h3 class="title">商品情報</h3>
                <div class="info-row">
                    <label class="info-label">カテゴリー</label>
                    @foreach ($product->categories as $category)
                        <span class="category-value">{{ $category->name }}</span>
                    @endforeach
                </div>
                <div class="info-row">
                    <label class="info-label">商品の状態</label>
                    <span class="condition-value">{{ $product->condition->name }}</span>
                </div>
            </div>
            <div class="comments-area">
                <h3 class="comment-title">
                    コメント({{ $product->comments->count() }})
                </h3>
                @foreach ($product->comments as $comment)
                    <div class="comment">
                        @if (optional($comment->user->profile)->image_url && Storage::exists(optional($comment->user->profile)->image_url))
                            <img class="comment-user-image" src="{{ $comment->user->profile->image_url }}"
                                alt="{{ $comment->user->name }}">
                        @else
                            <div class="comment-user-image-placeholder"></div>
                        @endif
                        <div class="comment-body">
                            <p class="comment-user">{{ $comment->user->name }}</p>
                            <p class="comment-text">{{ $comment->content }}</p>
                        </div>
                    </div>
                @endforeach
                @auth
                    <form action="/item/comment/{{ $product->id }}" method="POST" class="comment-form">
                        @csrf
                        <label class="label">商品へのコメント</label>
                        <textarea name="content"></textarea>
                        <div class="error">
                            @error('content')
                                {{ $message }}
                            @enderror
                        </div>
                        <button type="submit" class="comment-btn">コメントを送信する</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
@endsection
