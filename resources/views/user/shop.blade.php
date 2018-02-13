@extends('master')
@section('content')
<div class="container">
    <h1 class="title">BẢNG QUẢN TRỊ SHOP</h1>
	<div class="row">
		<div class="col-md-4">
            <h3 class="title">QUẢN LÍ</h3>
            <ul class="list">
                <li class="list-item"><a href="{{route('user.shop.add')}}">THÊM ACC GAME</a></li>
                <li class="list-item"><a href="{{route('user.shop.list')}}">QUẢN LÍ ACC</a></li>
                <li class="list-item"><a href="{{route('user.shop.withdraw')}}">LỊCH SỬ THANH TOÁN</a></li>
                <li class="list-item"><a href="{{route('user.shop.bank')}}">THÔNG TIN THANH TOÁN</a></li>
            </ul>
        </div>
		<div class="col-md-8">
            <h3 class="title">THỐNG KÊ</h3>
            <table>
                <tr>
                    <th>SỐ ACC</th>
                    <th>ĐANG THUÊ</th>
                    <th>SẴN SÀNG</th>
                    <th>Doanh Thu</th>
                </tr>
                <tr>
                    <td>
                        {{count(App\ListAcc::all())}}
                    </td>
                    <td>{{count(Auth::user()->listAcc()->where('status', 1)->get())}}</td>
                    <td>{{count(Auth::user()->listAcc()->where('status', 0)->get())}}</td>
                    <td>{{Auth::user()->history()->sum('price')*0.7}}đ</td>
                </tr>
            </table>
			<h3 class="title">DANH SÁCH ACC ĐANG THUÊ</h3>
			@if (count($history) == 0)
			<table><tr>
					<td colspan="7">
						<div class="col-md-6">
							Lọc theo:
							<form>
							<select name="sortByGame" class="form-control" onchange="submitForm(this)">
								@foreach (App\Category::all() as $item)
								<option value="{{$item->id}}"
								@if($item->id == $request->sortByGame)
									selected
								@endif
								>{{$item->game_name}}</option>
								@endforeach
							</select>
							</form>
						</div>
						<div class="col-md-6">
							
						</div>
					</td>
				</tr>
				<tr>
					<td>
						Chưa có acc được thuê
					</td>
				</tr>
			</table>
			@else
			<table>
				<tr>
					<td colspan="7">
						<div class="col-md-6">
							Lọc theo:
							<form>
							<select name="sortByGame" class="form-control" onchange="submitForm(this)">
								@foreach (App\Category::all() as $item)
								<option value="{{$item->id}}"
								@if($item->id == $request->sortByGame)
									selected
								@endif
								>{{$item->game_name}}</option>
								@endforeach
							</select>
							</form>
						</div>
						<div class="col-md-6">
						</div>
					</td>
				</tr>
				<tr>
					<th class="hidden-xs">STT</th>
					<th>Người thuê</th>
					<th>Tình trạng</th>
					<th>Game</th>
					<th>Tài khoản</th>
					<th>Mật khẩu</th>
					<th>Lựa chọn</th>
				</tr>
				@foreach ($history as $item)
				<tr>
					<td class="hidden-xs">{{ $loop->index+1 }}</td>
					<td>{{ $item->user->email }}</td>
					<td id="time_count" data-time="{{$item->time_end}}">{{gmdate('H:i:s', $item->time_end-time())}}</td>
					<td>{{$item->category->game_name}}</td>
					<td>{{$item->username}}</td>
					<td>{{$item->password}}</td>
					<td>
						<button class="btn btn-primary" onclick="refundUserMoney({{$item->id}})">Hoàn tiền</button>
					</td>
				</tr>
				@endforeach
			</table>
			<center>{{$history->appends($request->all())->links()}}</center>
			@endif
		</div>
	</div>
</div>
@endsection