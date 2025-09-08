<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    //
    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        Auth::login($user);
        return redirect('create');
    }

    public function loginUser(LoginRequest $request)
    {
        $credentials=$request->only('email', 'password');
        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('index');
        }

    }
}
