<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تحقق إذا كان المستخدم مسجل دخول وهل هو أدمن (role = admin)
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // إذا لم يكن أدمن، أعد توجيهه للصفحة الرئيسية مع رسالة خطأ
        return redirect('/')->with('error', 'Sie haben keine Berechtigung für diesen Bereich.');
    }
}
