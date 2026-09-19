<?php

namespace App\Http\Controllers;

use App\Models\MembershipOrder;
use App\Services\MidtransService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

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

        $pendingMembership = $user ? $user->membershipOrders()->where('payment_status', 'pending')->latest()->first() : null;

        return view('membership.index', compact('user', 'benefits', 'pendingMembership'));
    }

    /**
     * Initiate Membership Upgrade with Midtrans Snap.
     */
    public function upgrade(Request $request): JsonResponse|RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Silakan masuk untuk melanjutkan.'], 401);
            }

            return redirect()->route('login')->with('error', 'Silakan masuk untuk mengelola paket membership.');
        }

        $tier = $request->input('tier', 'premium');
        $planType = $request->input('plan_type', 'monthly');

        if ($tier === 'lite') {
            $user->update(['membership_tier' => 'lite']);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Paket Anda telah disesuaikan kembali ke paket Lite.']);
            }

            return redirect()->route('membership.index')->with('info', 'Paket Anda telah disesuaikan kembali ke paket Lite.');
        }

        // Tier is Premium -> Calculate amount
        $amount = ($planType === 'yearly') ? 390000 : 39000;
        $orderNumber = 'MEM-'.date('Ym').'-'.strtoupper(Str::random(5));

        $snapToken = null;
        try {
            $snapParams = [
                'transaction_details' => [
                    'order_id' => $orderNumber,
                    'gross_amount' => $amount,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '081234567890',
                ],
                'item_details' => [
                    [
                        'id' => 'MEM-PREMIUM-'.strtoupper($planType),
                        'price' => $amount,
                        'quantity' => 1,
                        'name' => 'Langganan OLARA Premium ('.($planType === 'yearly' ? 'Tahunan' : 'Bulanan').')',
                    ],
                ],
            ];

            $snapToken = $this->midtransService->createSnapToken($snapParams);
        } catch (Exception $e) {
            Log::error('Gagal generate Snap Token Midtrans untuk Membership Upgrade', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
        }

        $memOrder = MembershipOrder::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'tier' => 'premium',
            'plan_type' => $planType,
            'amount' => $amount,
            'payment_status' => 'pending',
            'payment_method' => 'Midtrans Digital Payment',
            'snap_token' => $snapToken,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'order_number' => $orderNumber,
                'snap_token' => $snapToken,
                'amount' => $amount,
                'message' => "Nomor pesanan {$orderNumber} berhasil dibuat.",
            ]);
        }

        return redirect()->route('membership.index')
            ->with('snap_token', $snapToken)
            ->with('order_number', $orderNumber)
            ->with('info', 'Silakan selesaikan pembayaran untuk mengaktifkan status OLARA Premium.');
    }

    /**
     * Confirm payment upon successful Midtrans Snap transaction.
     */
    public function confirmPayment(Request $request, string $orderNumber): JsonResponse|RedirectResponse
    {
        $memOrder = MembershipOrder::where('order_number', $orderNumber)->firstOrFail();
        $user = $memOrder->user;

        $memOrder->update([
            'payment_status' => 'paid',
            'payment_type' => $request->input('payment_type', 'midtrans'),
            'transaction_id' => $request->input('transaction_id', $memOrder->transaction_id),
            'paid_at' => now(),
        ]);

        if ($user) {
            $user->update(['membership_tier' => 'premium']);
            $user->addPoints(100, 'membership_reward', 'Bonus Upgrade Paket OLARA Premium (+100 Eco-Points)');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status membership Premium berhasil diaktifkan.',
            ]);
        }

        return redirect()->route('membership.index')->with('success', 'Selamat! Akun Anda kini berstatus OLARA Premium. Nikmati 5x penjemputan gratis, unlimited scan AI, dan multiplier 1.2x poin!');
    }
}
