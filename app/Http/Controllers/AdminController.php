<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\History;
use App\Category;
use App\ListAcc;
use App\Withdraw;
use App\User;
class AdminController extends Controller
{
    //
    public function index(Request $request){
        $category = Category::all();
        $history = History::where('status', 1)->paginate(20);
        if(isset($request->sortByGame)){
            $history =  History::where([['status', 1], ['game_id', $request->sortByGame]])->paginate(20);            
        }
        return view('admin.index', ['request' => $request, 'history' => $history, 'category' => $category]);
    }
    public function user(Request $request, $action = null){
        switch($action){
        case 'lock':
            $user = User::findOrFail($request->id);
            $user->right = -1;
            $user->save();
            return redirect()->route('admin.user');
            break;
        case 'unlock':
            $user = User::findOrFail($request->id);
            $user->right = 0;
            $user->save();
            return redirect()->route('admin.user');
            break;
        default:
            $user = User::all();
            return view('admin.user', ['request' => $request, 'user' => $user]);
        }
    }
    public function pay(Request $request, $action = null){
        switch($action){
        case 'paid':
            $user = User::findOrFail($request->id);
            if($request->price < 200000 || $request->price > $user->balance){
                abort(404);
            }
            $wd = new Withdraw;
            $wd->user_id = $user->id;
            $wd->amount = $request->price;
            $wd->status = 1;
            $wd->save();
            $user->balance -= $request->price;
            $user->save();
        break;
        default:
            $user = User::where([['balance', '>=', '200000'], ['shop', 1]])->paginate(20);
            return view('admin.pay', ['request' => $request, 'user' => $user]);
        }
    }
    public function category(Request $request, $action = null){
        switch($action){
        case 'remove':
            $category = Category::findOrFail($request->id);
            if($request->method() == "POST"){
                $category->listAcc()->delete();
                $category->history()->delete();
                $category->delete();
                return redirect()->route('admin.category');                
            }
            return view('admin.removeCategory', ['category' => $category]);
            break;
        case 'edit':
            if($request->method() == "POST"){
                if(empty($request->game_name)){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Vui lòng nhập tên loại game</div>'
                    ], 422);
                }
                $category = Category::findOrFail($request->id);
                $category->game_name = $request->game_name;
                $category->game_description = $request->game_description;
                if($request->hasFile('game_avatar')){
                    $tmpName = md5(time()).'.'.$request->game_avatar->getClientOriginalExtension();
                    $path = $request->game_avatar->move('upload/category/', $tmpName);
                    $category->game_avatar = url('upload/category/'.$tmpName);
                } else if(!empty($request->game_avatar)) {
                    $category->game_avatar = $request->game_avatar;
                }
                $category->save();
                return route('admin.category');
            }
            $category = Category::findOrFail($request->id);
            return view('admin.editCategory', ['category' => $category]);
        break;
        case 'add':
            if($request->method() == "POST"){
                if(empty($request->game_name)){
                    return response()->json([
                        'error' => '<div class="alert alert-danger center">Vui lòng nhập tên loại game</div>'
                    ], 422);
                }
                $category = new Category;
                $category->game_name = $request->game_name;
                $category->game_description = $request->game_description;
                if($request->hasFile('game_avatar')){
                    $tmpName = md5(time()).'.'.$request->game_avatar->getClientOriginalExtension();
                    $path = $request->game_avatar->move('upload/category/', $tmpName);
                    $category->game_avatar = url('upload/category/'.$tmpName);
                } else if(!empty($request->game_avatar)){
                    $category->game_avatar = $request->game_avatar;
                } else {
                    $category->game_avatar = url('upload/default.png');
                }
                $category->save();
                return route('admin.category');
            }
            return view('admin.addCategory');
            break;
        default:
            $category = Category::all();
            return view('admin.category', ['request' => $request, 'category' => $category]);
        }
    }
    public function refund(Request $request){
        if($request->ajax()){
            $history = History::find($request->id);
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
}
