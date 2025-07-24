<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/admin');
        }

        return back()->with('error', 'username atau password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard'); // sudah login, langsung ke dashboard
        }

        return view('auth.login'); // kalau belum login, tampilkan halaman login
    }


}
