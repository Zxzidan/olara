<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login', ['mode' => 'login']);
    }

    public function showRegister(): View
    {
        return view('auth.register', ['mode' => 'register']);
    }

    public function login(Request $request): RedirectResponse
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali di OLARA!');
            }

            return back()->withErrors([
                'email' => 'Surel atau kata sandi yang Anda masukkan tidak sesuai.',
            ])->onlyInput('email');
        } catch (\Throwable $e) {
            return back()->withErrors([
                'email' => 'Gagal masuk: '.$e->getMessage(),
            ])->onlyInput('email');
        }
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $name = ! empty($validated['name'])
            ? $validated['name']
            : ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $validated['email'])[0]));

        // Create user with 50 Eco-Point welcome bonus as strictly specified by OLARA PRD
        $user = User::create([
            'name' => $name,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'eco_points' => 50,
            'recycler_level' => 'Level 1 — Starter',
            'membership_tier' => 'lite',
        ]);

        // Log registration bonus transaction
        $user->pointTransactions()->create([
            'amount' => 50,
            'type' => 'registration_bonus',
            'description' => 'Bonus Registrasi Akun Baru OLARA (+50 Eco-Points)',
            'balance_after' => 50,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Bonus 50 Eco-Point telah ditambahkan ke dompet Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah keluar dari akun.');
    }

    public function demoLogin(Request $request): RedirectResponse
    {
        $user = User::where('email', 'zidan@olara.id')->first();
        if (! $user) {
            $user = User::first();
        }

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', 'Berhasil beralih ke akun Demo: '.$user->name);
        }

        return redirect()->route('login');
    }
}
