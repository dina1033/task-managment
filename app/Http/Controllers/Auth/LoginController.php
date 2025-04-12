<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserType;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if ($this->guard()->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $user = Auth::user();
            if ($user->type->value != 'user') {
                return redirect()->route('login')->with('error' , 'Only Regular User can access this site.');
            }
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    protected function guard()
    {
        return Auth::guard('web');
    }
}
