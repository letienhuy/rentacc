<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check()){
            if($request->ajax()){
                return response()->json(['error' => view('_dialog', ['html' => 'Vui lòng đăng nhập để thực hiện chức năng này!<br><center><a href="'.route('app.login').'"><button class="dialog__button-blue">Đăng nhập</button></a></center>'])->render()], 422);
            }
            return redirect()->route('app.home')->withErrors([
                'login' => 'Vui lòng đăng nhập để thực hiện chức năng này!<br><center><a href="'.route('app.login').'"><button class="dialog__button-blue">Đăng nhập</button></a></center>'
            ]);
        }
        return $next($request);
    }
}
