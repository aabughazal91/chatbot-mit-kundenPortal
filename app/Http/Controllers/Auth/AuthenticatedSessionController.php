<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Eine eingehende Authentifizierungsanfrage bearbeiten.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    // 1. Bestimmen, ob die Eingabe eine E-Mail-Adresse oder ein Benutzername ist
    $login = $request->input('login'); // Wir werden den Feldnamen in der Blade-Vorlage später zu 'login' ändern
    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // 2. Das korrekte Feld in der Anfrage zusammenführen, damit Breeze es verarbeiten kann
    $request->merge([$fieldType => $login]);

    // 3. محاولة تسجيل الدخول
    // ملاحظة: يجب تعديل دالة authenticate() في LoginRequest أو القيام بالتحقق هنا
    if (! Auth::attempt($request->only($fieldType, 'password'), $request->boolean('remember'))) {
        return back()->withErrors([
            'login' => __('auth.failed'),
        ]);
    }

    // User Confirmation
    if (! Auth::user()->is_confirmed) {
        Auth::logout();
        return redirect()->route('login')->with('error', 'Ihr Konto wurde noch nicht aktiviert.');
    }

    $request->session()->regenerate();

    if (Auth::user()->role === 'admin') {
        return redirect()->intended(route('admin.dashboard'));
    }

    return redirect()->intended(route('customer.dashboard'));
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
