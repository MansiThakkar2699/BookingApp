<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email','password'))) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        if (!Auth::user()->email_verified_at) {
            Auth::logout();
            return back()->withErrors(['email' => 'Please verify your email first.']);
        }

        return redirect('/booking');
    }


    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //        'email' => 'required|email|unique:users',
    //        'password' => 'required',
    //     ]);

    //     // Using Auth::guard()
    //     if (Auth::guard()->attempt($credentials, $request->boolean('remember'))) {
    //         $request->session()->regenerate();
    //         return redirect()->intended(route('dashboard'));
    //     }

    //     return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    // }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.show');
    }
}
