<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user profile edit form.
     */
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $stats = [
            'eco_points' => $user->eco_points,
            'recycler_level' => $user->recycler_level ?? 'Level 1 — Starter',
            'membership_tier' => $user->membership_tier ?? 'lite',
            'total_pickups' => $user->pickupRequests()->count(),
            'total_scans' => $user->wasteAnalyses()->count(),
            'total_orders' => $user->marketplaceOrders()->count(),
        ];

        return view('profile.edit', compact('user', 'stats'));
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'origin' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'avatar_preset' => ['nullable', 'string', 'max:500'],
        ], [
            'first_name.required' => 'Nama depan wajib diisi.',
            'email.required' => 'Alamat surel wajib diisi.',
            'email.email' => 'Format surel tidak valid.',
            'email.unique' => 'Surel ini sudah digunakan oleh akun lain.',
            'avatar.image' => 'File avatar harus berupa gambar (JPEG, PNG, WEBP).',
            'avatar.max' => 'Ukuran file avatar maksimal 2MB.',
        ]);

        $firstName = trim($validated['first_name']);
        $lastName = trim($validated['last_name'] ?? '');
        $fullName = $lastName !== '' ? "{$firstName} {$lastName}" : $firstName;

        $updateData = [
            'name' => $fullName,
            'first_name' => $firstName,
            'last_name' => $lastName !== '' ? $lastName : null,
            'origin' => $validated['origin'] ?? $user->origin,
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar_url'] = Storage::url($path);
        } elseif ($request->filled('avatar_preset')) {
            $updateData['avatar_url'] = $validated['avatar_preset'];
        }

        $user->update($updateData);

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Update user account password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Kata sandi berhasil diperbarui dengan aman.');
    }
}
