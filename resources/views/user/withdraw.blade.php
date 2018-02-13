@extends('master')
@section('content')
    <div class="container">
        <div class="row">
            <h3 class="title">LỊCH SỬ RÚT TIỀN</h3>
            @if (count($withdraw) == 0)
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
                    <th>STT</th>
                    <th>NGÀY THANH TOÁN</th>
                    <th>TÌNH TRẠNG</th>
                    <th>SỐ TIỀN</th>
                </tr>
                @foreach ($withdraw as $item)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($item->created_at))}}</td>
                        <td>{!!$item->status ? '<b style="color:green">ĐÃ THANH TOÁN</b>':'<b style="color:red">THẤT BẠI</b>'!!}</td>
                        <td >{{$item->amount}}</td>
                    </tr>
                @endforeach
            </table>
            <center>
                {{$withdraw->links()}}
            </center>
            @endif
        </div>
    </div>
@endsection