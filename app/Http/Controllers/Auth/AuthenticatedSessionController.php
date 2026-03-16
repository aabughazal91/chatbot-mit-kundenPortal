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
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    // 1. تحديد ما إذا كان المدخل إيميل أم اسم مستخدم
    $login = $request->input('login'); // سنقوم بتغيير اسم الحقل في واجهة الـ Blade لاحقاً إلى login
    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // 2. دمج الحقل الصحيح في الطلب ليتمكن Breeze من معالجته
    $request->merge([$fieldType => $login]);

    // 3. محاولة تسجيل الدخول
    // ملاحظة: يجب تعديل دالة authenticate() في LoginRequest أو القيام بالتحقق هنا
    if (! Auth::attempt($request->only($fieldType, 'password'), $request->boolean('remember'))) {
        return back()->withErrors([
            'login' => __('auth.failed'),
        ]);
    }

    // 4. التحقق من أن الحساب مؤكد (is_confirmed)
    // هذا الجزء مهم جداً لمشروعك لضمان أن الأدمن قد وافق على العميل
    if (! Auth::user()->is_confirmed) {
        Auth::logout();
        return redirect()->route('login')->with('error', 'Ihr Konto wurde noch nicht aktiviert.');
    }

    $request->session()->regenerate();

    // 5. توجيه المستخدم حسب دوره (Admin -> Dashboard, Customer -> My Project)
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
