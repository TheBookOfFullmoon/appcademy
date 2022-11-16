<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function postLogin(PostLoginRequest $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)){
            if (Auth::user()->role == 'admin'){
                return redirect()->route('admin.index')
                    ->with('success', 'You have successfully logged in.');
            }
        }

        return redirect()->route('login')
            ->with('error', "Oops! You have entered invalid credentials");
    }

    public function logout(){
        Session::flush();
        Auth::logout();

        return redirect('login');
    }
}
