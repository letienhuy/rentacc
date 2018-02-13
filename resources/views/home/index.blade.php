@extends('master')
@section('content')
    <div class="container-fluid">
        <div class="banner">
            <img src="https://rentacc.com/assets/tmp/banner_header.png" alt="">
        </div>
    </div>
    @if ($errors->has('login'))
        <div id="over"></div>
        @include('_dialog', ['html' => $errors->first('login')])
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-7">
                <div class="game-list">
                    <a href="{{route('app.home')}}"><button class="game-list__button fill">TRANG CHỦ</button></a>
                    @foreach ($gameList as $item)
                    <button class="game-list__button" data-id="{{$item->id}}">{{$item->game_name}}</button>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 col-sm-5">
                <div class="game-list">
                    <center>
                        <button class="game-list__button fill"><b><span id="account-ready" data-id="">0</span> sẵn sàng</button>
                        <button class="game-list__button fill"><span id="account-renting" data-id="">0</span> đã thuê</b></button>
                    </center>
                </div>
            </div>
        </div>
        <div class="row" id="list-account">
        </div>
        <div class="row">
            <div id="loading"></div>
        </div>
    </div>
@endsection