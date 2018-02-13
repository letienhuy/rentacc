@extends('master')
@section('content')
<div class="container">
	<div class="row">
        <h3 class="title">DANH SÁCH SHOP ĐỦ ĐIỀU KIỆN THANH TOÁN</h3>
        <table>
            <tr>
                <td colspan="5">
                    <div class="alert alert-success center">
                        Hôm nay ngày {{date('d/m/Y',time())}}
                    </div>
                </td>
            </tr>
            <tr>
                <th>STT</th>
                <th>EMAIL</th>
                <th>THÔNG TIN THANH TOÁN</th>
                <th>SỐ DƯ</th>
                <th></th>
            </tr>
            @foreach ($user as $item)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$item->email}}</td>
                    <td>
                        Họ tên: {{$item->bank->fullname}}<br>
                        CMND: {{$item->bank->identity}}<br>
                        Ngân Hàng: {{$item->bank->bank_name}}<br>
                        Số TK: {{$item->bank->bank_number}}<br>
                        Chi nhánh: {{$item->bank->bank_address}}<br>
                    </td>
                    <td id="user-price">{{$item->balance}}</td>
                    <td>
                        <button data-id="{{$item->id}}" class="btn btn-success" id="btn-change-price">THANH TOÁN</button>
                    </td>
                </tr>
            @endforeach
        </table>
        <center>{{$user->links()}}</center>
    </div>
</div>
@endsection