<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
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
        $token = JWTAuth::getToken() ?: session('jwt_token');
        if (!$token) {
            toastr()->error('Vui lòng đăng nhập để tiếp tục');
            return redirect()->route('login');
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();
            if (!$user) {
                toastr()->error('Không thể xác thực người dùng.');
                return redirect()->route('login');
            }
        } catch (TokenExpiredException $e) {
            toastr()->error('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
            return redirect()->route('login');
        } catch (JWTException $e) {
            toastr()->error('Đăng nhập thất bại, vui lòng thử lại.');
            return redirect()->route('login');
        }

        return $next($request);
    }
}
