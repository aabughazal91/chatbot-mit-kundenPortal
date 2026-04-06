<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'username'          => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'confirmed'],
            'email_confirmation' => ['required', 'string', 'email'],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
            'company'           => ['nullable', 'string', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:50'],
            'street'            => ['nullable', 'string', 'max:255'],
            'zip'               => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'company'  => $request->company,
            'phone'    => $request->phone,
            'street'   => $request->street,
            'zip'      => $request->city ?? $request->zip,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('login', absolute: false));
    }
}
