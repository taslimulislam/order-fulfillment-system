<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐19

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    /**
     * Attempt user login
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()
            ->route('orders.index')
            ->with('success', ucfirst(auth()->user()->role) . ' ' . 'Login Successfully');
        }

        return back()->withErrors([
            'login' => 'Invalid credentials.',
        ]);
    }

    /**
     * Log out user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
