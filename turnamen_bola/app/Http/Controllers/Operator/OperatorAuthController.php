<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorAuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if ($request->session()->has('operator_id')) {
            return redirect()->route('operator.dashboard');
        }

        return view('operator.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $operator = Operator::where('username', $credentials['username'])->first();

        if ($operator && Hash::check($credentials['password'], $operator->password)) {
            if ($operator->status !== 'active') {
                return back()->withErrors(['username' => 'Akun Operator Anda tidak aktif. Silakan hubungi Panitia Pusat.']);
            }

            $operator->update(['last_login_at' => now()]);
            $request->session()->put('operator_id', $operator->id);
            $request->session()->regenerate();

            return redirect()->route('operator.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password operator salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('operator_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('operator.login');
    }
}
