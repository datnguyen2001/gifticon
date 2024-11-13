<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
                $avatarUrl = $socialUser->getAvatar();
                $avatarContents = file_get_contents($avatarUrl);
                $avatarName = 'avatar/' . Str::random(10) . '.png';
                Storage::disk('public')->put($avatarName, $avatarContents);

                $user = User::create([
                    'full_name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => Storage::url($avatarName),
                    'google_id' => $socialUser->getId(),
                ]);
            }

            Auth::login($user);

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
                $avatarUrl = $socialUser->getAvatar();
                $avatarContents = file_get_contents($avatarUrl);
                $avatarName = 'avatar/' . Str::random(10) . '.png';
                Storage::disk('public')->put($avatarName, $avatarContents);

                $user = User::create([
                    'full_name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => Storage::url($avatarName),
                    'facebook_id' => $socialUser->getId(),
                ]);
            }

            Auth::login($user);

            return redirect()->route('home');
        }catch (\Exception $exception){
            return back()->with(['error'=>$exception->getMessage()]);
        }
    }

    public function zaloCallback(){
        try{
            $socialUser  = Socialite::driver('zalo')->user();
            dd($socialUser);
            $user = User::where('facebook_id', $socialUser->getId())->first();

            if (!$user) {
                $avatarUrl = $socialUser->getAvatar();
                $avatarContents = file_get_contents($avatarUrl);
                $avatarName = 'avatar/' . Str::random(10) . '.png';
                Storage::disk('public')->put($avatarName, $avatarContents);

                $user = User::create([
                    'full_name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => Storage::url($avatarName),
                    'facebook_id' => $socialUser->getId(),
                ]);
            }

            Auth::login($user);

            return redirect()->route('home');
        }catch (\Exception $exception){
            return back()->with(['error'=>$exception->getMessage()]);
        }
    }

}
