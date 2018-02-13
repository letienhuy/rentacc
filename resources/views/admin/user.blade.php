@extends('master')
@section('content')
<div class="container">
	<div class="row">
        <h3 class="title">DANH SÁCH THÀNH VIÊN</h3>
        <table>
            <tr>
                <th>STT</th>
                <th>EMAIL</th>
                <th>TÊN</th>
                <th>LOẠI</th>
                <th></th>
            </tr>
            @foreach ($user as $item)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$item->email}}</td>
                    <td>{{$item->name}}</td>
                    <td>
                        @if ($item->right == 1)
                            ADMIN
                        @elseif ($item->right == -1)
                            KHOÁ
                        @elseif ($item->right == 0)
                            @if ($item->shop)
                                SHOP
                            @else
                                THÀNH VIÊN
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($item->right == -1)
                        <a href="{{route('admin.user', ['action' => 'unlock', 'id' => $item->id])}}"><button class="btn btn-success">MỞ KHOÁ</button></a>
                        @elseif($item->right == 0)
                        <a href="{{route('admin.user', ['action' => 'lock', 'id' => $item->id])}}"><button class="btn btn-danger">KHOÁ</button></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection