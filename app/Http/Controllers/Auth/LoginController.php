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

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->is_banned) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been banned.']);
            }

            return redirect()->intended(route('home'))->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }
}
