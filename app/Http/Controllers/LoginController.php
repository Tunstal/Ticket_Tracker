<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController
{
    public function create()
    {
        if (Auth::check()) {
            return Inertia::location('/');
        }
        return Inertia::render('Login');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate login request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['email'] = strip_tags($credentials['email']);
        $credentials['password'] = strip_tags($credentials['password']);

        // If the user is authenticated, redirect to the intended page they were trying to access
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy()
    {
        Auth::logout();
        return redirect('/login');
    }
}