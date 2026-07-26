<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginInput = trim($credentials['email']);

        // Check if input is email or username "admin"
        $user = User::where('email', $loginInput)
            ->orWhere('name', 'like', "%{$loginInput}%")
            ->first();

        if (! $user && ($loginInput === 'admin' || $loginInput === 'superadmin')) {
            $user = User::where('role', 'super_admin')->first();
        }

        if ($user && Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();

            return back()->withErrors(['email' => 'Akun Anda tidak memiliki akses Super Admin.']);
        }

        return back()->withErrors([
            'email' => 'Email / Username atau password administrator salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
