<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;

    protected string $clientKey;

    protected string $merchantId;

    protected bool $isProduction;

    protected string $snapUrl;

    protected string $apiUrl;

    public function __construct()
    {
        $this->serverKey = (string) config('midtrans.server_key');
        $this->clientKey = (string) config('midtrans.client_key');
        $this->merchantId = (string) config('midtrans.merchant_id');
        $this->isProduction = (bool) config('midtrans.is_production');
        $this->snapUrl = (string) config('midtrans.snap_url');
        $this->apiUrl = (string) config('midtrans.api_url');
    }

    /**
     * Generate Midtrans Snap Token for transaction popup or redirect.
     *
     * @param  array<string, mixed>  $params
     *
     * @throws Exception
     */
    public function createSnapToken(array $params): string
    {
        $authHeader = base64_encode($this->serverKey.':');

        $response = Http::withoutVerifying()->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.$authHeader,
        ])->timeout(10)->post($this->snapUrl, $params);

        if (! $response->successful()) {
            Log::error('Midtrans Snap Token Generation Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'params' => $params,
            ]);

            $errorMessage = $response->json('error_messages.0') ?? $response->body();
            throw new Exception("Gagal membuat Snap Token Midtrans: {$errorMessage}");
        }

        $token = $response->json('token');
        if (! $token) {
            throw new Exception('Midtrans tidak mengembalikan Snap Token yang valid.');
        }

        return $token;
    }

    /**
     * Get transaction status from Midtrans Core API.
     *
     * @return array<string, mixed>|null
     */
    public function getTransactionStatus(string $orderId): ?array
    {
        try {
            $authHeader = base64_encode($this->serverKey.':');

            $response = Http::withoutVerifying()->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic '.$authHeader,
            ])->timeout(5)->get("{$this->apiUrl}/{$orderId}/status");

            if (! $response->successful()) {
                Log::warning("Gagal mengambil status transaksi Midtrans untuk {$orderId}", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::warning("Exception saat cek status Midtrans untuk {$orderId}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Verify SHA512 signature key sent from Midtrans webhook notification.
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$this->serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }
}
