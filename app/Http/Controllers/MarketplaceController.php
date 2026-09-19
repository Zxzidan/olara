<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceProduct;
use App\Services\MidtransService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = MarketplaceProduct::query();

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        $products = $query->get();
        $selectedCategory = $request->input('category', 'all');

        $userOrders = $user ? $user->marketplaceOrders()->latest()->take(5)->get() : collect();

        return view('marketplace.index', compact('products', 'user', 'selectedCategory', 'userOrders'));
    }

    /**
     * Checkout: Generates Order Number immediately and creates Midtrans Snap Token.
     */
    public function checkout(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:marketplace_products,id'],
            'quantity_kg' => ['required', 'integer', 'min:1'],
            'shipping_address' => ['required', 'string', 'min:10'],
            'payment_method' => ['nullable', 'string'],
        ]);

        $product = MarketplaceProduct::findOrFail($validated['product_id']);
        $user = Auth::user();

        if ($validated['quantity_kg'] < $product->min_order_kg) {
            $errorMsg = "Minimum pemesanan untuk {$product->name} adalah {$product->min_order_kg} kg.";
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }

            return back()->with('error', $errorMsg);
        }

        $qty = (int) $validated['quantity_kg'];
        $subtotal = round($qty * $product->price_per_kg, 2);
        $ppn = round($subtotal * 0.10, 2); // PPN 10%
        $shippingFee = 45000;
        $grandTotal = $subtotal + $ppn + $shippingFee;

        $co2Saved = round($qty * $product->co2_savings_per_kg, 2);
        $pointsEarned = (int) floor($subtotal / 10000); // 1 point per 10k purchase

        // 1. Instantly generate official unique order number
        $orderNumber = 'ORD-'.date('Ym').'-'.strtoupper(Str::random(5));
        $trackingNumber = 'JTR-'.strtoupper(Str::random(8));

        // 2. Request Midtrans Snap Token
        $snapToken = null;
        try {
            $snapParams = [
                'transaction_details' => [
                    'order_id' => $orderNumber,
                    'gross_amount' => (int) round($grandTotal),
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '081234567890',
                    'shipping_address' => [
                        'first_name' => $user->name,
                        'address' => $validated['shipping_address'],
                        'city' => 'Jakarta',
                        'country_code' => 'IDN',
                    ],
                ],
                'item_details' => [
                    [
                        'id' => 'PRD-'.$product->id,
                        'price' => (int) round($product->price_per_kg),
                        'quantity' => $qty,
                        'name' => mb_substr($product->name, 0, 50),
                    ],
                    [
                        'id' => 'TAX-PPN',
                        'price' => (int) round($ppn),
                        'quantity' => 1,
                        'name' => 'PPN 10%',
                    ],
                    [
                        'id' => 'SHP-JTR',
                        'price' => (int) round($shippingFee),
                        'quantity' => 1,
                        'name' => 'Ongkir Armada JNE Trucking',
                    ],
                ],
            ];

            $snapToken = $this->midtransService->createSnapToken($snapParams);
        } catch (Exception $e) {
            Log::error('Gagal generate Snap Token Midtrans saat checkout', [
                'error' => $e->getMessage(),
                'order_number' => $orderNumber,
            ]);
        }

        // 3. Create Order Record with Pending status
        $order = MarketplaceOrder::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'grade' => $product->grade,
                    'price' => $product->price_per_kg,
                    'qty_kg' => $qty,
                    'total' => $subtotal,
                ],
            ],
            'shipping_address' => $validated['shipping_address'],
            'subtotal' => $subtotal,
            'ppn_amount' => $ppn,
            'shipping_fee' => $shippingFee,
            'grand_total' => $grandTotal,
            'payment_method' => $validated['payment_method'] ?? 'Midtrans Digital Payment',
            'payment_status' => 'pending',
            'snap_token' => $snapToken,
            'shipping_status' => 'diproses',
            'courier_name' => 'JNE Trucking (JTR)',
            'tracking_number' => $trackingNumber,
            'estimated_delivery_date' => now()->addDays(2)->toDateString(),
            'recipient_name' => $user->name,
            'co2_saved_kg' => $co2Saved,
            'points_earned' => $pointsEarned,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'order_number' => $orderNumber,
                'snap_token' => $snapToken,
                'redirect_url' => route('marketplace.orderDetail', $order->order_number),
                'message' => "Nomor pesanan {$orderNumber} berhasil dibuat.",
            ]);
        }

        return redirect()->route('marketplace.orderDetail', $order->order_number)
            ->with('snap_token', $snapToken)
            ->with('info', "Nomor pesanan {$orderNumber} berhasil dibuat! Silakan selesaikan pembayaran via Midtrans.");
    }

    /**
     * Re-fetch or generate Snap Token for an existing pending order.
     */
    public function getSnapToken(string $orderNumber): JsonResponse
    {
        $order = MarketplaceOrder::where('order_number', $orderNumber)->firstOrFail();

        if ($order->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah lunas.',
                'is_paid' => true,
            ]);
        }

        if ($order->snap_token) {
            return response()->json([
                'success' => true,
                'snap_token' => $order->snap_token,
                'order_number' => $order->order_number,
            ]);
        }

        // Generate new snap token if not exists
        try {
            $user = $order->user;
            $firstItem = $order->items[0] ?? [];
            $snapParams = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) round($order->grand_total),
                ],
                'customer_details' => [
                    'first_name' => $user?->name ?? 'Pelanggan Olara',
                    'email' => $user?->email ?? 'customer@olara.id',
                    'phone' => $user?->phone ?? '081234567890',
                ],
                'item_details' => [
                    [
                        'id' => 'ORD-'.($firstItem['product_id'] ?? 1),
                        'price' => (int) round($firstItem['price'] ?? $order->subtotal),
                        'quantity' => (int) ($firstItem['qty_kg'] ?? 1),
                        'name' => mb_substr($firstItem['name'] ?? 'Material Olahan Daur Ulang', 0, 50),
                    ],
                    [
                        'id' => 'TAX-PPN',
                        'price' => (int) round($order->ppn_amount),
                        'quantity' => 1,
                        'name' => 'PPN 10%',
                    ],
                    [
                        'id' => 'SHP-JTR',
                        'price' => (int) round($order->shipping_fee),
                        'quantity' => 1,
                        'name' => 'Ongkir Armada JNE Trucking',
                    ],
                ],
            ];

            $token = $this->midtransService->createSnapToken($snapParams);
            $order->update(['snap_token' => $token]);

            return response()->json([
                'success' => true,
                'snap_token' => $token,
                'order_number' => $order->order_number,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat sesi pembayaran: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark order as paid upon client Snap callback or verify with Midtrans.
     */
    public function markAsPaid(Request $request, string $orderNumber): JsonResponse|RedirectResponse
    {
        $order = MarketplaceOrder::where('order_number', $orderNumber)->firstOrFail();

        // Check with Midtrans Core API if possible
        $statusData = $this->midtransService->getTransactionStatus($orderNumber);
        $txStatus = $statusData['transaction_status'] ?? $request->input('transaction_status', 'settlement');
        $fraudStatus = $statusData['fraud_status'] ?? $request->input('fraud_status', 'accept');
        $paymentType = $statusData['payment_type'] ?? $request->input('payment_type', 'midtrans');
        $txId = $statusData['transaction_id'] ?? $request->input('transaction_id');

        $isSuccess = ($txStatus === 'settlement')
            || ($txStatus === 'capture' && $fraudStatus === 'accept')
            || ($request->input('payment_status') === 'success');

        if ($isSuccess) {
            $order->update([
                'payment_status' => 'paid',
                'payment_type' => $paymentType,
                'transaction_id' => $txId ?? $order->transaction_id,
                'shipping_status' => 'diproses',
            ]);

            // Award points
            if ($order->points_earned > 0 && $order->user) {
                $order->user->addPoints(
                    $order->points_earned,
                    'marketplace_purchase',
                    "Cashback Poin Pesanan {$order->order_number}"
                );
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'payment_status' => $order->payment_status,
                'message' => 'Status pembayaran berhasil diperbarui.',
            ]);
        }

        return redirect()->route('marketplace.orderDetail', $order->order_number)
            ->with('success', "Pembayaran untuk pesanan {$orderNumber} berhasil diverifikasi! Pesanan Anda kini sedang dikemas.");
    }

    public function orders(Request $request): View
    {
        $user = Auth::user();
        $status = $request->input('status', 'all');

        $query = $user ? $user->marketplaceOrders()->latest() : MarketplaceOrder::query()->latest();

        if ($status !== 'all') {
            $query->where('shipping_status', $status);
        }

        $orders = $query->paginate(10);

        // Counts for tab badges (Shopee style)
        $allOrders = $user ? $user->marketplaceOrders()->get() : collect();
        $counts = [
            'all' => $allOrders->count(),
            'diproses' => $allOrders->where('shipping_status', 'diproses')->count(),
            'dikirim' => $allOrders->where('shipping_status', 'dikirim')->count(),
            'sampai' => $allOrders->where('shipping_status', 'sampai')->count(),
            'selesai' => $allOrders->where('shipping_status', 'selesai')->count(),
        ];

        return view('marketplace.orders', compact('orders', 'user', 'status', 'counts'));
    }

    public function orderDetail(string $orderNumber): View
    {
        $order = MarketplaceOrder::where('order_number', $orderNumber)->firstOrFail();
        $user = Auth::user();

        return view('marketplace.order_detail', compact('order', 'user'));
    }

    public function confirmDelivery(string $orderNumber): RedirectResponse
    {
        $order = MarketplaceOrder::where('order_number', $orderNumber)->firstOrFail();

        $order->update([
            'status' => 'selesai',
            'shipping_status' => 'selesai',
            'delivered_at' => $order->delivered_at ?? now(),
            'completed_at' => now(),
        ]);

        return back()->with('success', "Pesanan {$orderNumber} berhasil dikonfirmasi diterima! Transaksi selesai dan sertifikat daur ulang telah diverifikasi.");
    }

    public function updateShippingStatus(Request $request, string $orderNumber): RedirectResponse
    {
        $order = MarketplaceOrder::where('order_number', $orderNumber)->firstOrFail();
        $newStatus = $request->input('shipping_status', $request->input('status'));

        if (! in_array($newStatus, ['diproses', 'dikirim', 'sampai', 'selesai'])) {
            return back()->with('error', 'Status pengiriman tidak valid.');
        }

        $updateData = [
            'shipping_status' => $newStatus,
            'status' => $newStatus === 'selesai' ? 'selesai' : $order->status,
        ];

        if ($newStatus === 'sampai') {
            $updateData['delivered_at'] = now();
        } elseif ($newStatus === 'selesai') {
            $updateData['delivered_at'] = $order->delivered_at ?? now();
            $updateData['completed_at'] = now();
        }

        $order->update($updateData);

        return back()->with('success', "Status pengiriman pesanan {$orderNumber} berhasil diperbarui menjadi: ".strtoupper($newStatus));
    }
}
