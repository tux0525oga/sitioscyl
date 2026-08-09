<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
                'max:190',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        $email = strtolower(
            trim($credentials['email'])
        );

        $authenticated = Auth::attempt([
            'email' => $email,
            'password' => $credentials['password'],
            'isActive' => true,
        ]);

        if (!$authenticated) {
            return back()
                ->withErrors([
                    'email' =>
                        'El correo o la contraseña no son correctos.',
                ])
                ->onlyInput('email');
        }

        $user = Auth::user();

        if (
            !$user instanceof UserAccount ||
            !$user->hasAdminAccess()
        ) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                        'Esta cuenta no tiene acceso administrativo.',
                ]);
        }

        $request->session()->regenerate();

        $user->lastLoginAt = now();
        $user->save();

        return redirect()->intended(
            route('admin.dashboard')
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}