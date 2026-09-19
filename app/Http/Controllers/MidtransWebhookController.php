<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceOrder;
use App\Models\MembershipOrder;
use App\Models\PickupRequest;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

    /**
     * Handle incoming asynchronous webhook notifications from Midtrans.
     */
    public function handleNotification(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received', $payload);

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');
        $paymentType = (string) ($payload['payment_type'] ?? '');
        $transactionId = (string) ($payload['transaction_id'] ?? '');

        if (! $orderId || ! $statusCode || ! $grossAmount || ! $signatureKey) {
            return response()->json(['message' => 'Missing required notification fields.'], 400);
        }

        // Verify SHA512 signature from Midtrans
        if (! $this->midtransService->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning("Midtrans Webhook Invalid Signature for Order: {$orderId}");

            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        $isSuccess = ($transactionStatus === 'settlement')
            || ($transactionStatus === 'capture' && $fraudStatus === 'accept');

        // Route notification handling based on Order ID prefix
        if (str_starts_with($orderId, 'ORD-')) {
            $this->handleMarketplaceOrder($orderId, $isSuccess, $transactionStatus, $paymentType, $transactionId, $payload);
        } elseif (str_starts_with($orderId, 'MEM-')) {
            $this->handleMembershipOrder($orderId, $isSuccess, $transactionStatus, $paymentType, $transactionId);
        } elseif (str_starts_with($orderId, 'PKP-')) {
            $this->handlePickupRequest($orderId, $isSuccess, $transactionStatus, $paymentType, $transactionId);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notification processed successfully.',
        ]);
    }

    protected function handleMarketplaceOrder(string $orderId, bool $isSuccess, string $transactionStatus, string $paymentType, string $transactionId, array $payload): void
    {
        $order = MarketplaceOrder::where('order_number', $orderId)->first();
        if (! $order) {
            Log::warning("Marketplace Order not found for Webhook: {$orderId}");

            return;
        }

        if ($isSuccess) {
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_type' => $paymentType,
                    'transaction_id' => $transactionId,
                    'shipping_status' => 'diproses',
                    'payment_response' => $payload,
                ]);

                // Award cashback points if not already awarded
                if ($order->points_earned > 0 && $order->user) {
                    $order->user->addPoints(
                        $order->points_earned,
                        'marketplace_purchase',
                        "Cashback Poin Pesanan {$order->order_number}"
                    );
                }
            }
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update([
                'payment_status' => 'failed',
                'payment_type' => $paymentType,
                'transaction_id' => $transactionId,
            ]);
        } elseif ($transactionStatus === 'pending') {
            $order->update([
                'payment_status' => 'pending',
                'payment_type' => $paymentType,
                'transaction_id' => $transactionId,
            ]);
        }
    }

    protected function handleMembershipOrder(string $orderId, bool $isSuccess, string $transactionStatus, string $paymentType, string $transactionId): void
    {
        $memOrder = MembershipOrder::where('order_number', $orderId)->first();
        if (! $memOrder) {
            Log::warning("Membership Order not found for Webhook: {$orderId}");

            return;
        }

        if ($isSuccess) {
            if ($memOrder->payment_status !== 'paid') {
                $memOrder->update([
                    'payment_status' => 'paid',
                    'payment_type' => $paymentType,
                    'transaction_id' => $transactionId,
                    'paid_at' => now(),
                ]);

                $user = $memOrder->user;
                if ($user) {
                    $user->update(['membership_tier' => 'premium']);
                    $user->addPoints(100, 'membership_reward', 'Bonus Upgrade Paket OLARA Premium (+100 Eco-Points)');
                }
            }
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $memOrder->update([
                'payment_status' => 'failed',
                'payment_type' => $paymentType,
                'transaction_id' => $transactionId,
            ]);
        }
    }

    protected function handlePickupRequest(string $orderId, bool $isSuccess, string $transactionStatus, string $paymentType, string $transactionId): void
    {
        $pickup = PickupRequest::where('pickup_code', $orderId)->first();
        if (! $pickup) {
            Log::warning("Pickup Request not found for Webhook: {$orderId}");

            return;
        }

        if ($isSuccess) {
            $pickup->update([
                'payment_status' => 'paid',
                'status' => 'driver_assigned',
                'payment_method' => $paymentType,
                'transaction_id' => $transactionId,
            ]);

            // Award points based on waste weight upon payment completion (if not already awarded)
            if (! $pickup->points_awarded && $pickup->user) {
                $points = $pickup->points_earned > 0
                    ? (int) $pickup->points_earned
                    : PickupRequest::calculatePoints((float) $pickup->estimated_weight);

                $pickup->user->addPoints(
                    $points,
                    'pickup_reward',
                    "Poin Penjemputan Sampah {$pickup->pickup_code} ({$pickup->estimated_weight} kg)"
                );

                $pickup->update([
                    'points_earned' => $points,
                    'points_awarded' => true,
                ]);
            }
        }
    }
}
