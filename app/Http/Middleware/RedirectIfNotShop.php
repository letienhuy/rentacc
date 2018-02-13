<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class RedirectIfNotShop
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
        if(Auth::check()){
            if(Auth::user()->shop != 1){
                return redirect()->route('app.home');
            }
        }else{
            return redirect()->route('app.home');
        }
        return $next($request);
    }
}
