<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // Menampilkan halaman login
    public function loginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            if (Auth::user()->role == 'admin') {

                return redirect('/dashboard');
            } elseif (Auth::user()->role == 'operator') {

                return redirect('/operator/dashboard');
            } elseif (Auth::user()->role == 'manager') {

                return redirect()
                    ->route('manager.dashboard')
                    ->with(
                        'success',
                        'Login Manager Berhasil'
                    );
            }
        }

        return back()->with('error', 'Login gagal');
    }

    // Logout
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
