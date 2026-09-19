<?php

namespace App\Http\Controllers;

use App\Models\PickupRequest;
use App\Services\MidtransService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PickupController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();
        $activePickup = $user ? $user->pickupRequests()
            ->whereIn('status', ['requested', 'confirmed', 'driver_assigned', 'on_the_way'])
            ->first() : null;

        $pastPickups = $user ? $user->pickupRequests()
            ->where('status', 'completed')
            ->take(5)
            ->get() : collect();

        $prefillCategory = $request->query('category');
        $prefillWeight = $request->query('weight', 5.0);

        return view('pickup.index', compact('user', 'activePickup', 'pastPickups', 'prefillCategory', 'prefillWeight'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'categories' => ['required', 'array', 'min:1'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_slot' => ['required', 'in:pagi,siang'],
            'address' => ['required', 'string', 'min:10'],
            'notes' => ['nullable', 'string'],
            'estimated_weight' => ['required', 'numeric', 'min:1'],
            'distance_km' => ['nullable', 'numeric'],
        ]);

        $user = Auth::user();
        $weight = (float) $validated['estimated_weight'];
        $distance = (float) ($validated['distance_km'] ?? 3.5);

        // Pricing formula according to PRD:
        $baseFee = 10000;
        $volumeSurcharge = ($weight > 10) ? ceil(($weight - 10) / 10) * 5000 : 0;
        $distanceFee = round($distance * 2000);
        $serviceFee = 2000;
        $totalFee = $baseFee + $volumeSurcharge + $distanceFee + $serviceFee;

        // If user is premium member, they get free pickup quota (PRD: 5x free pickups / month)
        if ($user && $user->membership_tier === 'premium') {
            $baseFee = 0;
            $serviceFee = 0;
            $totalFee = $volumeSurcharge + $distanceFee;
        }

        $courierPool = [
            ['name' => 'Budi Santoso', 'plate' => 'B 4219 SZR', 'phone' => '+62 878-1122-3344'],
            ['name' => 'Ahmad Faisal', 'plate' => 'B 3108 TKL', 'phone' => '+62 813-9988-7766'],
            ['name' => 'Dedi Kurniawan', 'plate' => 'B 5542 PQM', 'phone' => '+62 856-4433-2211'],
        ];
        $selectedCourier = $courierPool[array_rand($courierPool)];

        $code = 'PKP-'.date('Ym').'-'.strtoupper(Str::random(4));

        $snapToken = null;
        if ($totalFee > 0) {
            try {
                $snapParams = [
                    'transaction_details' => [
                        'order_id' => $code,
                        'gross_amount' => (int) round($totalFee),
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone ?? '081234567890',
                    ],
                    'item_details' => [
                        [
                            'id' => 'PICKUP-FEE',
                            'price' => (int) round($totalFee),
                            'quantity' => 1,
                            'name' => 'Biaya Armada Penjemputan Sampah Olara',
                        ],
                    ],
                ];

                $snapToken = $this->midtransService->createSnapToken($snapParams);
            } catch (Exception $e) {
                Log::error('Gagal generate Snap Token untuk Pickup Request', [
                    'error' => $e->getMessage(),
                    'code' => $code,
                ]);
            }
        }

        $pickup = PickupRequest::create([
            'pickup_code' => $code,
            'user_id' => $user->id,
            'categories' => $validated['categories'],
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_slot' => $validated['scheduled_slot'],
            'address' => $validated['address'],
            'notes' => $validated['notes'] ?? null,
            'estimated_weight' => $weight,
            'base_fee' => $baseFee,
            'volume_surcharge' => $volumeSurcharge,
            'distance_km' => $distance,
            'distance_fee' => $distanceFee,
            'service_fee' => $serviceFee,
            'total_fee' => $totalFee,
            'payment_status' => $totalFee > 0 ? 'unpaid' : 'paid',
            'payment_method' => $totalFee > 0 ? 'Midtrans Digital' : 'Gratis (Kuota Premium)',
            'snap_token' => $snapToken,
            'status' => 'confirmed',
            'courier_name' => $selectedCourier['name'],
            'courier_plate' => $selectedCourier['plate'],
            'courier_phone' => $selectedCourier['phone'],
        ]);

        return redirect()->route('pickup.show', $pickup->pickup_code)
            ->with('snap_token', $snapToken)
            ->with('success', 'Jadwal penjemputan berhasil dibuat! Kurir mitra kami akan segera ditugaskan.');
    }

    public function show(string $code): View
    {
        $pickup = PickupRequest::where('pickup_code', $code)->firstOrFail();
        $user = Auth::user();

        return view('pickup.show', compact('pickup', 'user'));
    }

    /**
     * Mark Pickup Fee as paid from Snap onSuccess.
     */
    public function markPaid(Request $request, string $code): JsonResponse|RedirectResponse
    {
        $pickup = PickupRequest::where('pickup_code', $code)->firstOrFail();

        $pickup->update([
            'payment_status' => 'paid',
            'status' => 'driver_assigned',
            'transaction_id' => $request->input('transaction_id', $pickup->transaction_id),
            'payment_method' => $request->input('payment_type', 'Midtrans'),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Biaya penjemputan berhasil dibayar.']);
        }

        return redirect()->route('pickup.show', $code)->with('success', 'Biaya armada penjemputan berhasil dibayar via Midtrans!');
    }
}
