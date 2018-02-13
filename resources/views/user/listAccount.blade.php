@extends('master')
@section('content')
    <div class="container">
        <div class="row">
            <h3 class="title">DANH SÁCH ACC GAME</h3>
            @if (count($listAcc) == 0)
                <table>
                    <tr>
                        <td>
                            Chưa có tài khoản nào!
                        </td>
                    </tr>
                </table>
            @else
            <table>
                <tr>
                    <th class="hidden-xs">STT</th>
                    <th>GAME</th>
                    <th>TÀI KHOẢN</th>
                    <th>MẬT KHẨU</th>
                    <th>GIÁ</th>
                    <th class="hidden-xs">MÔ TẢ</th>
                    <th></th>
                </tr>
                @foreach ($listAcc as $item)
                    <tr>
                        <td class="hidden-xs">{{ $loop->index+1 }}</td>
                        <td>{{$item->category->game_name}}</td>
                        <td>{{$item->username}}</td>
                        <td>{{$item->password}}</td>
                        <td>
                            @foreach (json_decode($item->price,true) as $k => $v)
                                {{$k}}h - {{$v}}đ<br>
                            @endforeach
                        </td>
                        <td style="width:20%" class="hidden-xs">{{$item->description}}</td>
                        <td>
                            <a href="{{route('user.shop.edit', ['id' => $item->id])}}"><button class="btn btn-success">Sửa</button></a>
                            <button class="btn btn-danger" onclick="removeAccount({{$item->id}})">Xoá</button>
                        </td>
                    </tr>
                @endforeach
            </table>
            <center>
                {{$listAcc->links()}}
            </center>
            @endif
        </div>
    </div>
@endsection