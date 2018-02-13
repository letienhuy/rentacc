@extends('master')
@section('content')
<div class="container">
	<div class="row">
        <h3 class="title">DANH SÁCH LOẠI GAME</h3>=
        <form method="post">
        {{csrf_field()}}
        <label for="">
        Xoá <b style="color:red">{{$category->game_name}}</b> sẽ xoá kèm theo {{count($category->listAcc)}} tài khoản game của chuyên mục này. <br>
        Đồng thời toàn bộ dữ liệu thuê acc của loại game này cũng sẽ bị xoá bỏ?

        </label><br>
        <button class="btn btn-success">Xác nhận</button>
        </form>
    </div>
</div>
@endsection