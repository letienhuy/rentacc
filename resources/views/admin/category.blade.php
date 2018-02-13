@extends('master')
@section('content')
<div class="container">
	<div class="row">
        <h3 class="title">DANH SÁCH LOẠI GAME</h3>
        <table>
            <tr>
                <th>STT</th>
                <th>TÊN GAME</th>
                <th>ẢNH</th>
                <th>MÔ TẢ</th>
                <th></th>
            </tr>
            @foreach ($category as $item)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$item->game_name}}</td>
                    <td><img class="category__thumb" src="{{$item->game_avatar}}"></td>
                    <td>{{$item->game_description}}</td>
                    <td>
                        <a href="{{route('admin.category', ['action' => 'edit', 'id' => $item->id])}}"><button class="btn btn-success">SỬA</button></a>
                        <a href="{{route('admin.category', ['action' => 'remove', 'id' => $item->id])}}"><button class="btn btn-danger">XOÁ</button></a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5">
                        <a href="{{route('admin.category', ['action' => 'add'])}}"><button class="btn btn-success">THÊM GAME</button></a>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection