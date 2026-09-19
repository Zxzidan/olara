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
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login', ['mode' => 'login']);
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register', ['mode' => 'register']);
    }

    public function login(Request $request): RedirectResponse
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $email = strtolower(trim($credentials['email']));
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

            if ($user) {
                $isValid = false;
                try {
                    $isValid = Hash::check($credentials['password'], $user->password);
                } catch (\Throwable) {
                    $isValid = password_verify($credentials['password'], $user->password);
                }

                if ($isValid) {
                    Auth::login($user, $request->boolean('remember'));
                    $request->session()->regenerate();

                    return redirect()->route('home')->with('success', 'Selamat datang kembali di OLARA!');
                }
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
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'origin' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'first_name.required' => 'Nama depan wajib diisi.',
            'origin.required' => 'Asal (kota / daerah / instansi) wajib diisi.',
            'email.required' => 'Alamat surel wajib diisi.',
            'email.unique' => 'Surel ini sudah terdaftar. Silakan gunakan surel lain atau masuk.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $firstName = trim($validated['first_name']);
        $lastName = trim($validated['last_name'] ?? '');
        $fullName = $lastName !== '' ? "{$firstName} {$lastName}" : $firstName;
        $origin = trim($validated['origin']);

        $user = User::create([
            'name' => $fullName,
            'first_name' => $firstName,
            'last_name' => $lastName !== '' ? $lastName : null,
            'origin' => $origin,
            'address' => $origin,
            'email' => strtolower(trim($validated['email'])),
            'password' => password_hash($validated['password'], PASSWORD_BCRYPT, ['cost' => 12]),
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
