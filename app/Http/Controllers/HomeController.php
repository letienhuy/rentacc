<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\ListAcc;
class HomeController extends Controller
{
    public function index(Request $request){
        $gameList = Category::all();
        return view('home.index', ['gameList' => $gameList]);
    }
    public function howToRent(Request $request){
        return view('home.rentAccount');
    }
    public function accountList(Request $request){
        $gameId = $request->id;
        if(isset($gameId)){
            $accountList = ListAcc::where([['game_id' , $gameId], ['status', 0]])->paginate(8);
            $accountReady = count(ListAcc::where([['game_id' , $gameId], ['status', 0]])->get());
            $accountRenting = count(ListAcc::where([['game_id' , $gameId], ['status', 1]])->get());
        } else {
            $accountList = ListAcc::where('status', 0)->paginate(8);
            $accountReady = count(ListAcc::where('status', 0)->get());
            $accountRenting = count(ListAcc::where('status', 1)->get());
        }
        $html = view('home.listAccount', ['accountList' => $accountList])->render();
        return response()->json(['html' => $html, 'account_ready' => $accountReady, 'account_renting' => $accountRenting]);
    } 
}
