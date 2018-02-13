@extends('master')
@section('content')
    <div class="container">
        <div class="row">
            <h3 class="title">SỬA TÀI KHOẢN: {{$account->category->game_name}}</h3>
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div id="error-message"></div>
                <form class="form-group" method="post" id="form-edit-account">
                    {{csrf_field()}}
                    <label for="">Chọn loại game:</label>
                    <select name="gameId">
                        @foreach (App\Category::all() as $item)
                            <option value="{{$item->id}}"
                            @if ($account->game_id == $item->id)
                                selected
                            @endif
                            >{{$item->game_name}}</option>
                        @endforeach
                    </select>
                    <label for="">Tên tài khoản:</label>
                    <input type="text" name="username" value="{{old('username') ?? $account->username}}">
                    <label for="">Mật khẩu:</label>
                    <input type="text" name="password" value="{{old('password') ?? $account->password}}">
                    <label for="">Giá thuê:</label>
                    <div id="price-addon">
                        @foreach (json_decode($account->price, true) as $k => $v)
                            <div class="group">
                                <input type="" name="hour[]" value="{{$k}}" placeholder="Thời gian: 1, 2 ,3 (giờ)"><input type="" name="price[]" value="{{$v}}" placeholder="Giá: 2000, 3000, 4000...">
                            </div>
                        @endforeach
                    </div>
                    <a class="btn btn-primary" id="add-price-element">+</a><br>
                    <label for="">Giới thiệu:</label>
                    <textarea name="description" cols="30" rows="10">{{old('description') ?? $account->description}}</textarea>
                    <button>LƯU THAY ĐỔI</button>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection