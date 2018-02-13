@extends('master')
@section('content')
    <div class="container">
        <div class="row">
            <h3 class="title">LỊCH SỬ NẠP THẺ</h3>
            @if (count($recharge) == 0)
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
                    <th class="hidden-xs">NGÀY NẠP</th>
                    <th>MÃ PIN</th>
                    <th>SERI</th>
                    <th>MỆNH GIÁ</th>
                </tr>
                @foreach ($recharge as $item)
                    <tr>
                        <td class="hidden-xs">{{ $loop->index+1 }}</td>
                        <td class="hidden-xs">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                        <td>{{$item->card_pin}}</td>
                        <td>{{$item->card_seri}}</td>
                        <td>{{$item->card_price}}</td>
                    </tr>
                @endforeach
            </table>
            <center>
                {{$recharge->links()}}
            </center>
            @endif
        </div>
    </div>
@endsection