<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Please enter both your username and password.',
            'password.required' => 'Please enter both your username and password.',
        ]);

        $credentials = [
            'username' => strtolower(trim($data['username'])),
            'password' => $data['password']
        ];

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['username' => 'Invalid username or password.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('my-desk'))
            ->with('toast', ['success', 'Logged in as '.Auth::user()->name.'.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('toast', ['info', 'You have been logged out.']);
    }
}