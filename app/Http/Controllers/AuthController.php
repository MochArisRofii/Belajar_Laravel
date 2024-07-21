<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index() 
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->level == 'admin') {
                return redirect()->intended('admin');
            } elseif ($user->level == 'customer') {
                return redirect()->intended('pelanggan');
            }
            return redirect()->intended('/');
        }
        return view('login');
    }

    public function proses_login (Request $request)
    {
        request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $kredensil = $request->only('username', 'password');

        if (Auth::attempt($kredensil)) {
            $user = Auth::user();
            if ($user->level == 'admin') {
                return redirect()->intended('admin');
            }elseif ($user->level == 'customer') {
                return redirect()->intended('pelanggan');
            }
            return redirect()->intended('/');
        }

        return redirect()->route('login')
            ->withInput()
            ->withErrors(['login_gagal' => 'Wong Gak Cocok Ambek Sistem E Blokk']);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect()->route('login');
    }
}
