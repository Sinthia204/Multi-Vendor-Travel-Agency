<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'login')
                ->withInput($request->only('email'))
                ->with('show_login', true);
        }

        $credentials = $validator->validated();

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = $request->user();
            $isAdmin = $user && (($user->role ?? null) === 'admin' || ($user->is_admin ?? false));
            if ($isAdmin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect('/');
        }

        return back()
            ->withErrors(['login' => 'The provided credentials do not match our records.'], 'login')
            ->withInput($request->only('email'))
            ->with('show_login', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
