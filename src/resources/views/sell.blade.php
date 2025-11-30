@extends('layouts.header-nav')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection
@section('content')
    <div class="all-content">
        <form action="/sell" method="POST" enctype="multipart/form-data">
            @csrf
            <h2 class="main-title">商品の出品</h2>
            <label class="image-label">商品画像</label>
            <div class="image-area">
                <output class="output" id="sell-image"></output>
                <label for="image" class="image-button">画像を選択する</label>
                <input type="file" id="image" class="image-file" name="image" accept="image/*">
            </div>
            @error('image')
                <p class="error">{{ $message }}</p>
            @enderror

            <div class="detail-area">
                <h3 class="title">商品の詳細</h3>
                <label class="category-label">カテゴリー</label>
                <div class="checkbox-group">
                    @foreach ($categories as $category)
                        <input type="checkbox" id="category_{{ $category->id }}" name="product_category[]"
                            value="{{ $category->id }}" class="checkbox-input">

                        <label for="category_{{ $category->id }}" class="checkbox-label">
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
                <label class="condition-label">商品の状態</label>
                <select class="condition-select"name="condition">
                    <option disabled selected>選択してください</option>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                            {{ $condition->name }}</option>
                    @endforeach
                </select>
                @error('condition_id')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="description-area">
                <h3 class="title">商品名と説明</h3>
                <label class="product-label">商品名</label>
                <input class="input" type="text" name="name" value="{{ old('name') }}">
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
                <label class="product-label">ブランド名</label>
                <input class="input" type="text" name="brand" value="{{ old('brand') }}">
                <label class="product-label">商品の説明</label>
                <textarea class="textarea" type="text" name="description">{{ old('description') }}</textarea>
                @error('description')
                    <p class="error">{{ $message }}</p>
                @enderror
                <label class="product-label">販売価格</label>
                <div class="price-input-wrapper">
                    <input class="price-input" type="text" name="price" value="{{ old('price') }}">
                </div>
                @error('price')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <button class="sell-button" type="submit">出品する</button>
        </form>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('image');
            const output = document.getElementById('sell-image');
            output.addEventListener('click', () => {
                input.value = '';
                input.click();
            });
            input.addEventListener('change', e => {
                const file = e.target.files[0];
                if (!file || !file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = ev => {
                    output.innerHTML = `
                <img src="${ev.target.result}" alt="preview">
            `;
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
