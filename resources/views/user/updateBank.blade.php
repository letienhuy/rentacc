@extends('master')
@section('content')
    <div class="container">
        <div class="row">
            <h3 class="title">CHỈNH SỬA TÀI KHOẢN NGÂN HÀNG</h3>
            <div class="col-md-5">
                <div class="description">
                    <ul>
                        <li><b>Chú ý:</b> Hạn mức thanh toán là <b style="color:red">200.000đ</b> và sẽ được tự động thanh toán vào ngày 5 tháng n+1.</li>
                        <li>Tài khoản sẽ được cộng dồn nếu chưa đủ hạn mức thanh toán.</li>
                        <li>Vui lòng cập nhật chính xác thông tin thanh toán trước ngày thanh toán để có thể nhận được thanh toán</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-7">
                @if ($errors->has('success'))
                    <div class="alert alert-success">
                        {{$errors->first('success')}}
                    </div>
                @endif
                <form method="post">
                    {{csrf_field()}}
                    <label for="">Họ và tên:</label>
                    <input type="text" name="fullname" value="{{$bank->fullname}}">
                    <label for="">Số CMND:</label>
                    <input type="text" name="identity" value="{{$bank->identity}}">
                    <label for="">Số tài khoản:</label>
                    <input type="text" name="bank_number" value="{{$bank->bank_number}}">
                    <label for="">Tên ngân hàng: (Ngân hàng Vietcombank)</label>
                    <input type="text" name="bank_name" value="{{$bank->bank_name}}">
                    <label for="">Chi nhánh: (Vui lòng ghi rõ chi nhánh)</label>
                    <input type="text" name="bank_address" value="{{$bank->bank_address}}">
                    <button>CẬP NHẬT</button>
                </form>
            </div>
        </div>
    </div>
@endsection