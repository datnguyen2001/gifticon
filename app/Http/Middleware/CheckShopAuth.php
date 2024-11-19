<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckShopAuth
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::guard('shop')->check())
        {
            toastr()->error("Vui lòng đăng nhập để tiếp tục");
            return redirect()->route('shop.login');
        }
        return $next($request);
    }
}
