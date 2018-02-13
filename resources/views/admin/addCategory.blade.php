@extends('master')
@section('content')
    <div class="container">
        <div class="row">
            <h3 class="title">THÊM LOẠI GAME</h3>
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div id="error-message"></div>
                <form class="form-group" method="post" id="form-add-category">
                    {{csrf_field()}}
                    <label for="">Tên game:</label>
                    <input type="text" name="game_name" value="{{old('game_name')}}">
                    <label for="">Ảnh:</label>
                    <div id="game-avatar"></div>
                    <div class="input-group">
                        <input type="file" class="form-control" name="game_avatar">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" id="btn-game-avatar">URL</button>
                        </span>
                    </div>
                    <label for="">Giới thiệu:</label>
                    <textarea name="game_description" cols="30" rows="10">{{old('game_description')}}</textarea>
                    <button>THÊM LOẠI GAME</button>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection