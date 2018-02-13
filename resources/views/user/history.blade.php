@extends('master')
@section('content')
    <div class="container">
        <div class="row">
            <h3 class="title">LỊCH SỬ GIAO DỊCH</h3>
            @if (count($history) == 0)
                <table>
                    <tr>
                        <td>
                            Chưa có lịch sử!
                        </td>
                    </tr>
                </table>
            @else
            <table>
                <tr>
                    <th class="hidden-xs">STT</th>
                    <th class="hidden-xs">NGÀY THUÊ</th>
                    <th>TÌNH TRẠNG</th>
                    <th>TÀI KHOẢN</th>
                    <th>MẬT KHẨU</th>
                    <th>GAME</th>
                    <th class="hidden-xs">MÔ TẢ</th>
                </tr>
                @foreach ($history as $item)
                    <tr>
                        <td class="hidden-xs">{{ $loop->index+1 }}</td>
                        <td class="hidden-xs">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                        @if (time() > $item->time_end)
                            <td>HẾT HẠN</td>
                        @else
                        <td id="time_count" data-time="{{$item->time_end}}">{{gmdate('H:i:s', $item->time_end-time())}}</td>
                        @endif
                        <td>{{$item->username}}</td>
                        @if (time() > $item->time_end)
                            <td>********</td>
                        @else
                        <td>{{$item->password}} <button class="btn btn-primary" onclick="copyPassword('{{$item->password}}')">Copy</button></td>
                        @endif
                        <td>{{$item->category->game_name}}</td>
                        <td class="hidden-xs">{{$item->account->description ?? ''}}</td>
                    </tr>
                @endforeach
            </table>
            <center>
                {{$history->links()}}
            </center>
            @endif
        </div>
    </div>
@endsection