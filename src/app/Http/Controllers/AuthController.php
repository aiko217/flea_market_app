<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; 
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
        
        $user->sendEmailVerificationNotification();

        Auth::login($user);
        return redirect()->route('verification.notice');
    }

    public function loginForm()
    {
        return view('auth.login');
    }
    public function loginUser(LoginRequest $request)
    {
        $credentials=$request->only('email', 'password');
        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if(!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('verification.notice')->with('warning', 'メール認証を完了してください ');
            }
            return redirect('/');
        }
        return back()->withErrors([
        'login' => 'メールアドレスまたはパスワードが正しくありません',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
