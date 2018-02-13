@extends('master')
@section('content')
    <div class="container">
        <div class="row">
            <h3 class="title">THÊM ACC GAME</h3>
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div id="error-message"></div>
                <form class="form-group" method="post" id="form-add-account">
                    {{csrf_field()}}
                    <label for="">Chọn loại game:</label>
                    <select name="gameId">
                        @foreach (App\Category::all() as $item)
                            <option value="{{$item->id}}">{{$item->game_name}}</option>
                        @endforeach
                    </select>
                    <label for="">Tên tài khoản:</label>
                    <input type="text" name="username" value="{{old('username')}}">
                    <label for="">Mật khẩu:</label>
                    <input type="text" name="password" value="{{old('password')}}">
                    <label for="">Giá thuê:</label>
                    <div id="price-addon">
                        <div class="group">
                        <input type="" name="hour[]" placeholder="Thời gian: 1, 2 ,3 (giờ)"><input type="" name="price[]" placeholder="Giá: 2000, 3000, 4000...">
                        </div>
                    </div>
                    <a class="btn btn-primary" id="add-price-element">+</a><br>
                    <label for="">Giới thiệu:</label>
                    <textarea name="description" cols="30" rows="10">{{old('description')}}</textarea>
                    <button>THÊM ACC GAME</button>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection