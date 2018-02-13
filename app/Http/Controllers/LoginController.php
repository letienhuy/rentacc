<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Bank;
use Auth;
class LoginController extends Controller
{
    protected $app_id;
    protected $app_secret;
    protected $scope;
    protected $redirectUri;
    public function __construct(){
        $this->app_id = '209653146271346';
        $this->app_secret = '79bac167283d04b3b020b36a2471effb';
        $this->scope = 'email,public_profile';
        $this->redirectUri = route('app.login.done');
    }
    protected function index(Request $request){
        return redirect("https://www.facebook.com/v2.11/dialog/oauth?client_id=$this->app_id&redirect_uri=$this->redirectUri&scope=$this->scope");
    }
    public function done(Request $request){
        if($data = file_get_contents("https://graph.facebook.com/v2.11/oauth/access_token?client_id=$this->app_id&client_secret=$this->app_secret&code=$request->code&redirect_uri=$this->redirectUri")){
            $data = json_decode($data);
            $access_token = $data->access_token;
            if($data = file_get_contents("https://graph.facebook.com/me/?access_token=$access_token&fields=id,email,name")){
                $data = json_decode($data);
                $user = User::where('email', $data->email ?? $data->id)->get()->first();
                if(is_null($user)){
                    $user = User::create([
                        'name' => $data->name,
                        'email' => $data->email ?? $data->id,
                        'facebook_id' => $data->id
                    ]);
                    $bank = new Bank();
                    $bank->user_id = $user->id;
                    $bank->save();
                    Auth::login($user, true);
                    return redirect()->route('app.home');
                }else{
                    if($user->right === -1){
                        return redirect()->route('app.home')->withErrors(['login' => 'Tài khoản của bạn đã bị khoá do vi phạm, vui lòng liên hệ Admin để được giải quyết!']);
                    }else{
                        Auth::login($user, true);
                        return redirect()->route('app.home');
                    }
                }
            }
            return redirect()->route('app.login');
        }
        return redirect()->route('app.login');
    }
}
