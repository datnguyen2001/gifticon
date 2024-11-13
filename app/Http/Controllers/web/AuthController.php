<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('web.auth.login');
    }

    public function loginSubmit()
    {

    }

    public function register()
    {
        return view('web.auth.register');
    }

    public function registerSubmit()
    {

    }
}
