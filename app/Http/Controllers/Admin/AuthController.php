<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function showLogin(){
        return view('admin.auth.login');
    }
    public function login(Request $req){
        $credentials = $req->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $credentials['is_admin'] = true;
        if (Auth::attempt($credentials)) {
            $req->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));

        }
        return back()
            ->withErrors([
                'email' => 'Invalid administrator credentials.',
            ])
            ->onlyInput('email');

    }
    public function logout(Request $req){
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->route('admin.login');


    }

}
