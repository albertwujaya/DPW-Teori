<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UndanganHelper;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('dashboard.index');
        }
        return view('dashboard.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $storedUser = UndanganHelper::getSetting('admin_username', 'admin');
        $storedPass = UndanganHelper::getSetting('admin_password', 'admin123');

        if ($request->username === $storedUser && $request->password === $storedPass) {
            session(['admin_logged_in' => true]);
            session(['admin_user' => $request->username]);
            return redirect()->route('dashboard.index');
        }

        return back()->with('error', 'Username atau Password salah!');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        $request->session()->forget('admin_user');
        return redirect()->route('login');
    }
}
