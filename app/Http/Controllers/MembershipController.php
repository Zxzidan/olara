<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $benefits = [
            [
                'feature' => 'Laporan Analisis Dampak Lingkungan',
                'lite' => 'Dasar (Bulanan)',
                'premium' => 'Komprehensif (Mingguan + Insight AI)',
            ],
            [
                'feature' => 'Kuota Penjemputan Sampah Gratis',
                'lite' => '1x per bulan',
                'premium' => '5x per bulan (Bebas Biaya Dasar)',
            ],
            [
                'feature' => 'Prioritas Penugasan Kurir Armada',
                'lite' => 'Standar',
                'premium' => 'Prioritas Utama (Fast-Track)',
            ],
            [
                'feature' => 'Kredit Pindai Kamera AI Waste',
                'lite' => '5x per bulan',
                'premium' => 'Tanpa Batas (Unlimited)',
            ],
            [
                'feature' => 'Batas Maksimum Saldo Eco-Point',
                'lite' => '1.000 Poin',
                'premium' => '50.000 Poin',
            ],
            [
                'feature' => 'Multiplier Poin Reward Setiap Setoran',
                'lite' => '1.0x (Normal)',
                'premium' => '1.2x Poin Ekstra Otomatis',
            ],
            [
                'feature' => 'Kontribusi Otomatis Penanaman Pohon',
                'lite' => 'Manual via Donasi Poin',
                'premium' => '10% Biaya Langganan Otomatis untuk Reboisasi',
            ],
            [
                'feature' => 'Dukungan Pelanggan (Customer Support)',
                'lite' => 'Email Support',
                'premium' => 'WhatsApp Dedicated Eco-Consultant',
            ],
        ];

        return view('membership.index', compact('user', 'benefits'));
    }

    public function upgrade(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan masuk untuk mengelola paket membership.');
        }

        $tier = $request->input('tier', 'premium');

        if ($tier === 'premium') {
            $user->update(['membership_tier' => 'premium']);
            // Give 100 bonus points on premium upgrade
            $user->addPoints(100, 'membership_reward', 'Bonus Upgrade Paket OLARA Premium (+100 Eco-Points)');

            return redirect()->route('membership.index')->with('success', 'Selamat! Akun Anda kini berstatus OLARA Premium. Nikmati 5x penjemputan gratis, unlimited scan AI, dan multiplier 1.2x poin!');
        } else {
            $user->update(['membership_tier' => 'lite']);

            return redirect()->route('membership.index')->with('info', 'Paket Anda telah disesuaikan kembali ke paket Lite.');
        }
    }
}
