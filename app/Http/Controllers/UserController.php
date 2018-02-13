<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ListAcc;
use Auth;
use App\History;
use App\Recharge;
class UserController extends Controller
{
    protected $merchant_id = 3901;
    protected $api_user = '55109548b5ac2';
    protected $api_password = 'eabb8dac583544a16fdc394a6baf29c9';
    public function rentAccount(Request $request){
        $accountId = $request->id;
        $timeRent = $request->time;
        $account = ListAcc::find($accountId);
        if(is_null($account)){
            return response()->json(['error' => view('_dialog', [
                'html' => 'Không tìm thấy tài khoản nào. Vui lòng thử lại!'
            ])->render()], 422);
        }
        $price = json_decode($account->price, true);
        if(!array_key_exists($timeRent, $price)){
            return response()->json(['error' => view('_dialog', [
                'html' => 'Truy cập không hợp lệ. Vui lòng thử lại!'
            ])->render()], 422);
        }
        if(Auth::user()->balance < $price[$timeRent]){
            return response()->json(['error' => view('_dialog', [
                'html' => 'Tài khoản của bạn không đủ tiền. Vui lòng nạp thêm tiền để tiếp tục sử dụng dịch vụ!
                <br><center>
                <button class="btn btn-primary" onclick="popupRecharge()">NẠP TIỀN</button>
                </center>'
            ])->render()], 422);
        }
        if($account->status){
            return response()->json(['error' => view('_dialog', [
                'html' => 'Tài khoản đã có người thuê, hãy thử chọn tài khoản khác!
                <br><center>
                <button class="dialog__button-close">TIẾP TỤC</button>
                </center>'
            ])->render()], 422);
        }
        if(count(Auth::user()->history()->where('status', 1)->get()) == Auth::user()->limit){
            return response()->json(['error' => view('_dialog', [
                'html' => 'Bạn cần hoàn thành các lượt thuê trước để thuê '.(Auth::user()->limit+1).' acc cùng lúc!
                <br><center>
                <button class="dialog__button-close">TIẾP TỤC</button>
                </center>'
            ])->render()], 422);
        }
        $account->status = 1;
        $account->save();
        $history = new History;
        $history->user_id = Auth::id();
        $history->shop_id = $account->shop_id;
        $history->acc_id = $account->id;
        $history->game_id = $account->category->id;
        $history->username = $account->username;
        $history->password = $account->password;
        $history->status = 1;
        $history->total_time = $timeRent;
        $history->price = $price[$timeRent];
        $history->time_end = time()+$timeRent*3600;
        $history->save();
        Auth::user()->balance -= $price[$timeRent];
        Auth::user()->save();
        $account->shop->balance += $price[$timeRent]*0.7;
        $account->shop->save();
        return view('_dialog', [
            'html' => '<h3>THUÊ TÀI KHOẢN THÀNH CÔNG</h3><br><center>
            <a href="'.route('user.history').'">
            <button class="btn btn-primary">VÀO LỊCH SỬ GIAO DỊCH</button>
            </a></center>
            <script>
                $("#list-account").empty();
                loadGame(gameId, pageNumber);
            </script>'
        ]);
    }

    public function recharge(Request $request){
        if($request->ajax()){
            if($request->method() == "POST"){
                $pin = $request->pin;
                $seri = $request->seri;
                $type = $request->type;
                $cardCharging = $this->cardCharging($seri, $pin, $type, $this->merchant_id, $this->api_user, $this->api_password);
                if($cardCharging->code === 0 && $cardCharging->info_card >= 10000){
                    return '<div class="alert alert-success center">Bạn đã nạp thành công thẻ cào mệnh giá: <b style="color:red">'.$cardCharging->info_card.'</b></div>
                    <center><button class="dialog__button-close">ĐÓNG</button><center>';
                    Auth::user()->balance += $cardCharging->info_card;
                    Auth::user()->save();
                    $recharge = new Recharge;
                    $recharge->card_pin = $pin;
                    $recharge->card_seri = $seri;
                    $recharge->card_price = $cardCharging->info_card;
                    $recharge->user_id = Auth::id();
                    $recharge->save();

                }else{
                    return '<div class="alert alert-danger center">'.$cardCharging->msg.'</div>
                    <center><button class="dialog__button-red" onclick="popupRecharge()">Thử lại</button><center>
                    ';
                }
            }
            return view('user.rechargeAjax');
        }
        abort(404);
    }
    public function history(Request $request){
        $history = Auth::user()->history()->orderBy('id', 'desc')->paginate(20);
        return view('user.history', ['history' => $history]);
    }
    public function historyRecharge(Request $request){
        $recharge = Auth::user()->recharge()->paginate(20);
        return view('user.historyRecharge', ['recharge' => $recharge]);
    }
    protected function cardCharging($seri,$pin,$card_type,$merchant_id,$api_user,$api_password)
    {
        $fields = array(
            'merchant_id' => $merchant_id,
            'pin' => $pin,
            'seri' => $seri,
            'card_type' => $card_type,
        );
        $ch = curl_init("https://sv.gamebank.vn/api/card");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_USERPWD, $api_user . ":" . $api_password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        $result = json_decode($result);
        return $result;
    }
    public static function updateTimeRent(){
        $history = History::where([['time_end', '<=', time()], ['status', 1]])->get();
        foreach($history as $item){
            if($item->account){
                $item->account->status = 0;
                $item->account->save();
                $item->status = 0;
                $item->save();
            }
        }
    }
    public function createShop(Request $request){
        if($request->ajax()){
            if($request->method() == "POST"){
                if(is_null($request->phone)){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Vui lòng nhập số điện thoại</div>'
                    ], 422);
                }
                if(!preg_match('/^[0-9]/',$request->phone) || strlen($request->phone) < 10 || strlen($request->phone) > 11){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Vui lòng nhập đúng số điện thoại</div>'
                    ], 422);
                }
                if(Auth::user()->balance < 20000){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Tài khoản của bạn không đủ để tạo shop</div>'
                    ], 422);
                }
                if(Auth::user()->shop){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Bạn đã là tài khoản shop</div>'
                    ], 422);
                }
                Auth::user()->balance -= 20000;
                Auth::user()->shop = 1;
                Auth::user()->phone = $request->phone;
                Auth::user()->save();
                return '<div class="alert alert-success center">Tạo shop thành công</div>';
            }
            return view('user.createShopAjax');
        }
    }
    public function shop(Request $request){
        $history = Auth::user()->shopHistory()->where('status', 1)->paginate(20);
        if(isset($request->sortByGame)){
            $history = Auth::user()->shopHistory()->where([['status', 1], ['game_id', $request->sortByGame]])->paginate(20);            
        }
        return view('user.shop', ['request' => $request, 'history' => $history]);
    }
    public function refund(Request $request){
        if($request->ajax()){
            $history = History::find($request->id);
            if($history->shop_id != Auth::id())
                return false;
            if($history->status){
                $history->shop->balance -= $history->price*0.7;
                $history->shop->save();
                $history->user->balance += $history->price;
                $history->user->save();
                if($history->account){
                    $history->account->status = 0;
                    $history->account->save();
                }
                $history->delete();
            }
        }
    }
    public function addAccount(Request $request){
        if($request->method() == "POST"){
            if(is_null($request->username)){
                return response()->json([
                    'error' => '<div class="alert alert-danger center">Vui lòng nhập tài khoản game</div>'
                ], 422);
            }
            if(is_null($request->password)){
                return response()->json([
                    'error' => '<div class="alert alert-danger center">Vui lòng nhập mật khẩu game</div>'
                ], 422);
            }
            foreach($request->hour as $h){
                if(empty($h)){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Vui lòng nhập thời gian thuê tương ứng</div>'
                    ], 422);
                }
                if(!preg_match('/^[0-9]/',$h) || $h < 1 || $h > 24){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Thời gian thuê phải là số và nằm trong khoảng 1-24h</div>'
                    ], 422);
                }
            }
            foreach($request->price as $p){
                if(empty($p)){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Vui lòng nhập giá thuê tương ứng</div>'
                    ], 422);
                }
                if(!preg_match('/^[0-9]/',$p) || $p < 1000){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Giá thuê phải là số và thấp nhất là 1000đ</div>'
                    ], 422);
                }
            }
            $price = array_combine($request->hour, $request->price);
            $account = new ListAcc;
            $account->shop_id = Auth::id();
            $account->game_id = $request->gameId;
            $account->username = $request->username;
            $account->password = $request->password;
            $account->description = $request->description;
            $account->price = json_encode($price);
            $account->save();
            return route('user.shop.list');
        }
        return view('user.addAccount');
    }
    public function listAccount(){
        $listAcc = Auth::user()->listAcc()->paginate(20);
        return view('user.listAccount', ['listAcc' => $listAcc]);
    }
    public function editAccount(Request $request, $id){
        if($request->method() == "POST"){
            if(is_null($request->username)){
                return response()->json([
                    'error' => '<div class="alert alert-danger center">Vui lòng nhập tài khoản game</div>'
                ], 422);
            }
            if(is_null($request->password)){
                return response()->json([
                    'error' => '<div class="alert alert-danger center">Vui lòng nhập mật khẩu game</div>'
                ], 422);
            }
            foreach($request->hour as $h){
                if(empty($h)){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Vui lòng nhập thời gian thuê tương ứng</div>'
                    ], 422);
                }
                if(!preg_match('/^[0-9]/',$h) || $h < 1 || $h > 24){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Thời gian thuê phải là số và nằm trong khoảng 1-24h</div>'
                    ], 422);
                }
            }
            foreach($request->price as $p){
                if(empty($p)){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Vui lòng nhập giá thuê tương ứng</div>'
                    ], 422);
                }
                if(!preg_match('/^[0-9]/',$p) || $p < 1000){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Giá thuê phải là số và thấp nhất là 1000đ</div>'
                    ], 422);
                }
            }
            $price = array_combine($request->hour, $request->price);
            $account = Auth::user()->listAcc()->findOrFail($id);
            $account->game_id = $request->gameId;
            $account->username = $request->username;
            $account->password = $request->password;
            $account->description = $request->description;
            $account->price = json_encode($price);
            $account->save();
            return route('user.shop.list');            
        }
        $account = Auth::user()->listAcc()->findOrFail($id);
        return view('user.editAccount', ['account' => $account]);
    }
    public function removeAccount(Request $request, $id){
        if($request->ajax()){
            $account = Auth::user()->listAcc()->findOrFail($id);
            $account->delete();
        }
    }
    public function updateBank(Request $request){
        if($request->method() == "POST"){
            $bank = Auth::user()->bank;
            $bank->fullname = $request->fullname;
            $bank->identity = $request->identity;
            $bank->bank_number = $request->bank_number;
            $bank->bank_name = $request->bank_name;
            $bank->bank_address = $request->bank_address;
            $bank->save();
            return redirect()->back()->withErrors([
                'success' => 'Cập nhật thành công'
            ]);
        }
        $bank = Auth::user()->bank;
        return view('user.updateBank', ['bank' => $bank]);
    }
    public function withdrawHistory(){
        $withdraw = Auth::user()->withdraw()->paginate(20);
        return view('user.withdraw', ['withdraw' => $withdraw]);
    }
}
