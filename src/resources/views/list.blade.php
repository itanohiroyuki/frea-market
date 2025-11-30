@extends('layouts.header-nav')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
    <div class="all-contents">
        <div class="products-list">
            @if (request('pending'))
                <div class="alert alert-info">
                    コンビニでのお支払い番号を発行しました。Stripe から届くメールをご確認ください。
                </div>
            @endif
            <div class="products-list__inner">
                <a class="best {{ $tab === 'best' ? 'active' : '' }}" href="{{ url('/') }}">おすすめ</a>
                <a class="mylist {{ $tab === 'mylist' ? 'active' : '' }}" href="{{ url('/?tab=mylist') }}">マイリスト</a>
            </div>
        </div>
        <div class="product-contents">
            @if ($products->isEmpty())
                <p class="no-products">
                    {{ $tab === 'mylist' ? '' : '' }}
                </p>
            @else
                @foreach ($products as $product)
                    <div class="product-content">
                        <a href="/item/{{ $product->id }}" class="product-link">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="商品画像" class="product-image" />
                            @if ($product->status === 'sold')
                                <span class="sold-label">SOLD</span>
                            @endif
                        </a>
                        <div class="detail-content">
                            <p class="product-name">{{ $product->name }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const keyword = "{{ $keyword ?? '' }}";
            const bestLink = document.querySelector('.best');
            const mylistLink = document.querySelector('.mylist');
            if (!bestLink || !mylistLink) return;
            if (keyword) {
                bestLink.href = `/search?tab=best&keyword=${encodeURIComponent(keyword)}`;
                mylistLink.href = `/search?tab=mylist&keyword=${encodeURIComponent(keyword)}`;
            } else {
                bestLink.href = `/`;
                mylistLink.href = `/?tab=mylist`;
            }
        });
    </script>
@endsection
