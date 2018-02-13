<!doctype html>
<html lang="vi-VN">
    <head>
        <base href="{{route('app.home')}}">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <title>RentAcc - Thuê Acc Game Giá Tốt</title>
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('css/font-awesome.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
        <script src="{{asset('js/jquery-3.2.1.js')}}"></script>
        <script src="{{asset('js/jquery-ui.js')}}"></script>
        <script src="{{asset('js/bootstrap.js')}}"></script>
    </head>
    <body>
        <header>
            <nav class="header">
                <div class="header header-top__fixed">
                    <div class="container">
                        <button class="header-toggle" data-id="#slide-header-top">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div class="header-top__slide left" id="slide-header-top">
                            <a href="{{route('app.home')}}">TRANG CHỦ</a>
                            @if(Auth::check() && !Auth::user()->shop)
                            <a id="create-shop">TẠO SHOP</a>
                            @endif
                            <a href="{{route('app.howToRent')}}">HƯỚNG DẪN THUÊ ACC</a>
                        </div>
                        <div class="right">
                            @if (Auth::check())
                                <div class="dropdown">
                                    <a class="dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                      {{Auth::user()->name}}
                                      <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                                        <li>Số dư: <b style="color:red">{{Auth::user()->balance}}đ</b></li>
                                        @if(Auth::user()->right == 1)
                                        <li><a href="{{route('admin.home')}}"><b style="color:red">Admin Panel</b></a></li>
                                        @endif
                                        @if (Auth::user()->shop)
                                        <li><a href="{{route('user.shop')}}"><b style="color:red">Shop Panel</b></a></li>
                                        @endif
                                        <li><a onclick="popupRecharge()">Nạp tiền</a></li>
                                        <li><a href="{{route('user.history')}}">Lịch sử thuê acc</a></li>
                                        <li><a href="{{route('user.history.recharge')}}">Lịch sử nạp tiền</a></li>
                                        <li><a href="{{route('app.logout')}}">Đăng xuất</a></li>
                                    </ul>
                                </div>
                            @else
                                <a href="{{route('app.login')}}">ĐĂNG NHẬP</a>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <div id="content">
            @yield('content')
        </div>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                    </div>
                    <div class="col-md-5">
                        <b>
                        Địa Chỉ: Văn Trì, Phường Minh Khai, Quận Bắc Từ Liên, TP.Hà Nội<br>
                        Email hỗ trợ: support.rentacc@gmail.com<br>
                        Hotline: 1234.567.890
                        </b>
                    </div>
                </div>
            </div>
        </footer>
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>
