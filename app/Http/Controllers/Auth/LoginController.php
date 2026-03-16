<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $loginField = $request->input('login'); // الحقل من الفورم (إيميل أو يوزرنيم)
        $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = $request->validate([
            $fieldType => ['required'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt([$fieldType => $loginField, 'password' => $request->password])) {
            return redirect()->intended('/dashboard');
        }
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin/price-modules');
            }
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Die angegebenen Zugangsdaten stimmen nicht mit unseren überein.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
