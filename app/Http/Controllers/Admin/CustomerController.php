<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Mail\WelcomeCustomerMail;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::orderBy('id', 'asc')->get();
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        $customer = new User();
        return view('admin.customers.form', compact('customer'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'firma'     => 'nullable|string|max:255',
            'tel'       => 'nullable|string|max:255',
            'strasse'   => 'nullable|string|max:255',
            'zip_stadt' => 'nullable|string|max:255',
            'role' => ['required', Rule::in(['admin', 'kunde'])],
            'is_confirmed' => 'nullable|boolean',
        ]);

        $rawPassword = Str::random(10);
        $data['password'] = Hash::make($rawPassword);
        $data['is_confirmed'] = $request->has('is_confirmed');
        $data['is_admin'] = ($data['role'] === 'admin');

        $user = User::create($data);

        try {
            Mail::to($user->email)->send(new WelcomeCustomerMail($user, $rawPassword));
        } catch (\Exception $e) {
            Log::error('Welcome mail failed: ' . $e->getMessage());
            // Trotzdem weiterleiten mit Hinweis:
            return redirect()->route('admin.customers.index')->with('warning', 'Benutzer erstellt, E-Mail konnte nicht gesendet werden.');
        }

        return redirect()->route('admin.customers.index')->with('success', 'Kunde erfolgreich erstellt.');
    }

    public function edit(User $customer)
    {
        return view('admin.customers.form', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($customer->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($customer->id)],
            'password' => 'nullable|string|min:8',
           'firma'     => 'nullable|string|max:255',
            'tel'       => 'nullable|string|max:255',
            'strasse'   => 'nullable|string|max:255',
            'zip_stadt' => 'nullable|string|max:255',
            'role' => ['required', Rule::in(['admin', 'kunde'])],
            'is_confirmed' => 'nullable|boolean',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_confirmed'] = $request->has('is_confirmed');
        $data['is_admin'] = ($data['role'] === 'admin');

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', 'Kunde erfolgreich aktualisiert.');
    }

    public function destroy(User $customer)
    {
        if ($customer->id === \Illuminate\Support\Facades\Auth::id()) {
            return redirect()->route('admin.customers.index')->with('error', 'Sie können Ihren eigenen Account nicht löschen.');
        }

        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Kunde erfolgreich gelöscht.');
    }
}
