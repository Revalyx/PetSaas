<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ====================================
    // MOSTRAR LOGIN
    // ====================================
    public function showLogin()
    {
        return view('auth.login');
    }

    // ====================================
    // LOGIN
    // ====================================
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            $user = Auth::user();

            // SUPERADMIN → no pertenece a un tenant
            if ($user->tenant_id === null) {
                return redirect()->route('superadmin.dashboard');
            }

            // USUARIO JEFE NORMAL → su panel tenant
            return redirect()->route('tenant.dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ]);
    }

    // ====================================
    // LOGOUT
    // ====================================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
