<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle(Request $request, Closure $next)
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            toastr()->error('Vui lòng đăng nhập để tiếp tục');
            return redirect()->route('login');
        }

        try {
            $user = JWTAuth::authenticate($token);
        } catch (JWTException $e) {
            toastr()->error($e->getMessage());
            return back();
        }

        return $next($request);
    }
}
