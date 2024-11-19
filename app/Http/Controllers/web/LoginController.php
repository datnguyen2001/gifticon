<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function redirect($provider){
        try{
            return Socialite::driver($provider)->redirect();
        }catch (\Exception $exception){
            return back()->with(['error'=>$exception->getMessage()]);
        }
    }

    public function googleCallback(){
        try{
            $socialUser  = Socialite::driver('google')->user();
            $user = User::where('google_id', $socialUser->getId())->first();

            if (!$user) {
                $avatar = null;
                $avatarUrl = $socialUser->getAvatar();
                if ($avatarUrl){
                    $avatarContents = file_get_contents($avatarUrl);
                    $avatarName = 'avatar/' . Str::random(10) . '.png';
                    Storage::disk('public')->put($avatarName, $avatarContents);
                    $avatar = Storage::url($avatarName);
                }

                $user = User::create([
                    'full_name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => $avatar,
                    'google_id' => $socialUser->getId(),
                ]);
            }

            $token = JWTAuth::fromUser($user);
            $user->token = $token;
            $user->save();

            session(['jwt_token' => $token]);

            return redirect()->route('home');
        }catch (\Exception $exception){
            return back()->with(['error'=>$exception->getMessage()]);
        }
    }

    public function facebookCallback(){
        try{
            $socialUser  = Socialite::driver('facebook')->user();
            $user = User::where('facebook_id', $socialUser->getId())->first();

            if (!$user) {
                $avatar = null;
                $avatarUrl = $socialUser->getAvatar();
                if ($avatarUrl){
                    $avatarContents = file_get_contents($avatarUrl);
                    $avatarName = 'avatar/' . Str::random(10) . '.png';
                    Storage::disk('public')->put($avatarName, $avatarContents);
                    $avatar = Storage::url($avatarName);
                }

                $user = User::create([
                    'full_name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => $avatar,
                    'facebook_id' => $socialUser->getId(),
                ]);
            }

            $token = JWTAuth::fromUser($user);
            $user->token = $token;
            $user->save();

            session(['jwt_token' => $token]);

            return redirect()->route('home');
        }catch (\Exception $exception){
            return back()->with(['error'=>$exception->getMessage()]);
        }
    }

    public function zaloCallback(){
        try{
            $socialUser  = Socialite::driver('zalo')->user();
            $user = User::where('zalo_id', $socialUser->getId())->first();

            if (!$user) {
                $avatar = null;
                $avatarUrl = $socialUser->getAvatar();
                if ($avatarUrl){
                    $avatarContents = file_get_contents($avatarUrl);
                    $avatarName = 'avatar/' . Str::random(10) . '.png';
                    Storage::disk('public')->put($avatarName, $avatarContents);
                    $avatar = Storage::url($avatarName);
                }

                $user = User::create([
                    'full_name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => $avatar,
                    'zalo_id' => $socialUser->getId(),
                ]);
            }

            $token = JWTAuth::fromUser($user);
            $user->token = $token;
            $user->save();

            session(['jwt_token' => $token]);

            return redirect()->route('home');
        }catch (\Exception $exception){
            return back()->with(['error'=>$exception->getMessage()]);
        }
    }

}
