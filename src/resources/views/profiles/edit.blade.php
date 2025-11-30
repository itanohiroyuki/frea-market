@extends('layouts.header-nav')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/edit.css') }}">
@endsection

@section('content')
    <div class="edit-content">
        <div class="edit-content__inner">
            <h2 class="edit-content__title">プロフィール設定</h2>
            <form method="POST" action="/mypage/profile" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="profile-image-area">
                        <output id="list" class="image">
                            @if ($profile->image)
                                <div class="reader_file">
                                    <img class="reader_image" src="{{ $profile->image_url }}" alt="">
                                </div>
                            @endif
                        </output>
                        <label for="image" class="image-button">画像を選択する</label>
                        <input type="file" id="image" class="image-file" name="image">
                        @error('image')
                            <p class="error">{{ $errors->first('image') }}</p>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="label">ユーザー名</label>
                    <input type="text" name="name" class="input" value="{{ old('name', $profile->name) }}">
                    @error('name')
                        <p class="error">{{ $errors->first('name') }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="label">郵便番号</label>
                    <input type="text" name="postal_code" class="input"
                        value="{{ old('postal_code', $profile->postal_code ?? '') }}">
                    @error('postal_code')
                        <p class="error">{{ $errors->first('postal_code') }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="label">住所</label>
                    <input type="text" name="city" class="input" value="{{ old('city', $profile->city ?? '') }}">
                    @error('city')
                        <p class="error">{{ $errors->first('city') }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="label">建物名</label>
                    <input type="text" name="building" class="input"
                        value="{{ old('building', $profile->building ?? '') }}">
                </div>

                <button class="update-btn" type="submit">更新する</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('image').onchange = function(event) {
            initializeFiles();

            var files = event.target.files;

            for (var i = 0, f; f = files[i]; i++) {
                var reader = new FileReader();
                reader.readAsDataURL(f);
                reader.onload = function(e) {
                    var div = document.createElement('div');
                    div.className = 'reader_file';
                    div.innerHTML = '<img class="reader_image" src="' + e.target.result + '" />';
                    document.getElementById('list').appendChild(div);
                };
            }
        };

        function initializeFiles() {
            document.getElementById('list').innerHTML = '';
        }
    </script>
@endsection
