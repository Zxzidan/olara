<?php

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceProduct;
use App\Models\MembershipOrder;
use App\Models\PickupRequest;
use App\Models\User;
use Database\Seeders\OlaraDatabaseSeeder;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->seed(OlaraDatabaseSeeder::class);
    $this->user = User::where('email', 'zidan@olara.id')->first();
});

test('marketplace checkout immediately generates order number and midtrans snap token', function () {
    Http::fake([
        'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
            'token' => 'snap-test-token-778899',
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/snap-test-token-778899',
        ], 200),
    ]);

    $product = MarketplaceProduct::first();

    $response = $this->actingAs($this->user)->postJson(route('marketplace.checkout'), [
        'product_id' => $product->id,
        'quantity_kg' => $product->min_order_kg,
        'shipping_address' => 'Gudang PT Circular Mitra, Kawasan Industri Pulogadung',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'snap_token' => 'snap-test-token-778899',
    ]);

    $orderNumber = $response->json('order_number');
    expect($orderNumber)->toStartWith('ORD-');

    $order = MarketplaceOrder::where('order_number', $orderNumber)->first();
    expect($order)->not->toBeNull();
    expect($order->payment_status)->toBe('pending');
    expect($order->snap_token)->toBe('snap-test-token-778899');
    expect((float) $order->grand_total)->toBeGreaterThan(0);
});

test('marketplace pending order can be paid and marked as paid with points awarded', function () {
    $order = MarketplaceOrder::create([
        'order_number' => 'ORD-202609-TEST1',
        'user_id' => $this->user->id,
        'items' => [['name' => 'PET Flakes', 'qty_kg' => 50, 'total' => 500000]],
        'shipping_address' => 'Alamat Uji Coba Cikarang',
        'subtotal' => 500000,
        'ppn_amount' => 50000,
        'shipping_fee' => 45000,
        'grand_total' => 595000,
        'payment_method' => 'Midtrans',
        'payment_status' => 'pending',
        'points_earned' => 50,
        'shipping_status' => 'diproses',
    ]);

    Http::fake([
        'https://api.sandbox.midtrans.com/v2/*' => Http::response([
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'qris',
            'transaction_id' => 'midtrans-trx-12345',
        ], 200),
    ]);

    $initialPoints = $this->user->eco_points;

    $response = $this->actingAs($this->user)->postJson(route('marketplace.markPaid', $order->order_number), [
        'transaction_status' => 'settlement',
        'transaction_id' => 'midtrans-trx-12345',
        'payment_type' => 'qris',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $order->refresh();
    expect($order->payment_status)->toBe('paid');
    expect($order->payment_type)->toBe('qris');

    $this->user->refresh();
    expect($this->user->eco_points)->toBe($initialPoints + 50);
});

test('membership upgrade generates MEM order number and snap token', function () {
    Http::fake([
        'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
            'token' => 'mem-snap-token-9988',
        ], 200),
    ]);

    $response = $this->actingAs($this->user)->postJson(route('membership.upgrade'), [
        'tier' => 'premium',
        'plan_type' => 'monthly',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'snap_token' => 'mem-snap-token-9988',
        'amount' => 39000,
    ]);

    $orderNumber = $response->json('order_number');
    expect($orderNumber)->toStartWith('MEM-');

    $memOrder = MembershipOrder::where('order_number', $orderNumber)->first();
    expect($memOrder)->not->toBeNull();
    expect($memOrder->payment_status)->toBe('pending');
});

test('membership payment confirmation updates user tier to premium and gives 100 points', function () {
    $memOrder = MembershipOrder::create([
        'order_number' => 'MEM-202609-TEST2',
        'user_id' => $this->user->id,
        'tier' => 'premium',
        'plan_type' => 'monthly',
        'amount' => 39000,
        'payment_status' => 'pending',
    ]);

    $initialPoints = $this->user->eco_points;
    expect($this->user->membership_tier)->toBe('lite');

    $response = $this->actingAs($this->user)->postJson(route('membership.confirm', $memOrder->order_number), [
        'transaction_status' => 'settlement',
        'payment_type' => 'bank_transfer',
        'transaction_id' => 'midtrans-mem-trx-77',
    ]);

    $response->assertStatus(200);
    $memOrder->refresh();
    expect($memOrder->payment_status)->toBe('paid');

    $this->user->refresh();
    expect($this->user->membership_tier)->toBe('premium');
    // Premium tier receives 1.2x points multiplier: 100 * 1.2 = 120
    expect($this->user->eco_points)->toBe($initialPoints + 120);
});

test('midtrans webhook notification verifies signature and settles marketplace order', function () {
    $order = MarketplaceOrder::create([
        'order_number' => 'ORD-202609-WEBHOOK1',
        'user_id' => $this->user->id,
        'items' => [['name' => 'PET Flakes', 'qty_kg' => 50, 'total' => 200000]],
        'shipping_address' => 'Jl. Merdeka No 1',
        'subtotal' => 200000,
        'ppn_amount' => 20000,
        'shipping_fee' => 45000,
        'grand_total' => 265000,
        'payment_method' => 'Midtrans',
        'payment_status' => 'pending',
        'points_earned' => 20,
    ]);

    $orderId = $order->order_number;
    $statusCode = '200';
    $grossAmount = '265000.00';
    $serverKey = config('midtrans.server_key');
    $signatureKey = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

    $response = $this->postJson(route('midtrans.notification'), [
        'order_id' => $orderId,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'signature_key' => $signatureKey,
        'transaction_status' => 'settlement',
        'payment_type' => 'qris',
        'transaction_id' => 'midtrans-uuid-999',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['status' => 'success']);

    $order->refresh();
    expect($order->payment_status)->toBe('paid');
    expect($order->payment_type)->toBe('qris');
    expect($order->transaction_id)->toBe('midtrans-uuid-999');
});

test('midtrans webhook notification rejects invalid signature', function () {
    $response = $this->postJson(route('midtrans.notification'), [
        'order_id' => 'ORD-FAKE-999',
        'status_code' => '200',
        'gross_amount' => '100000.00',
        'signature_key' => 'invalid-signature-hash',
        'transaction_status' => 'settlement',
    ]);

    $response->assertStatus(403);
});

test('pickup request tracking displays only pesanan dibuat when unpaid', function () {
    $pickup = PickupRequest::create([
        'pickup_code' => 'PKP-202609-UNPAID',
        'user_id' => $this->user->id,
        'categories' => ['Plastik'],
        'scheduled_date' => now()->addDay(),
        'scheduled_slot' => 'pagi',
        'address' => 'Jl. Senopati Raya No. 42, Kebayoran Baru, Jakarta Selatan',
        'estimated_weight' => 5.0,
        'base_fee' => 10000,
        'volume_surcharge' => 0,
        'distance_km' => 3.5,
        'distance_fee' => 7000,
        'service_fee' => 2000,
        'total_fee' => 19000,
        'payment_status' => 'unpaid',
        'payment_method' => 'Midtrans Digital',
        'snap_token' => 'mock-snap-token-unpaid',
        'status' => 'confirmed',
        'courier_name' => 'Dedi Kurniawan',
        'courier_plate' => 'B 5542 PQM',
        'courier_phone' => '+62 856-4433-2211',
    ]);

    $response = $this->actingAs($this->user)->get(route('pickup.show', $pickup->pickup_code));

    $response->assertStatus(200);
    $response->assertSee('Pesanan Dibuat — Menunggu Pembayaran');
    $response->assertSee('Menunggu Bayar');
    $response->assertSee('Bayar Biaya Armada (Midtrans)');
    $response->assertSee('Menunggu Penugasan');
    $response->assertDontSee('Estimasi tiba dalam waktu');
});

test('pickup request tracking displays driver assigned and on the way when paid', function () {
    $pickup = PickupRequest::create([
        'pickup_code' => 'PKP-202609-PAID',
        'user_id' => $this->user->id,
        'categories' => ['Plastik'],
        'scheduled_date' => now()->addDay(),
        'scheduled_slot' => 'pagi',
        'address' => 'Jl. Senopati Raya No. 42, Kebayoran Baru, Jakarta Selatan',
        'estimated_weight' => 5.0,
        'base_fee' => 10000,
        'volume_surcharge' => 0,
        'distance_km' => 3.5,
        'distance_fee' => 7000,
        'service_fee' => 2000,
        'total_fee' => 19000,
        'payment_status' => 'paid',
        'payment_method' => 'Midtrans',
        'snap_token' => 'mock-snap-token-paid',
        'status' => 'driver_assigned',
        'courier_name' => 'Dedi Kurniawan',
        'courier_plate' => 'B 5542 PQM',
        'courier_phone' => '+62 856-4433-2211',
    ]);

    $response = $this->actingAs($this->user)->get(route('pickup.show', $pickup->pickup_code));

    $response->assertStatus(200);
    $response->assertSee('Kurir Menuju Lokasi Penjemputan');
    $response->assertSee('Estimasi tiba dalam waktu');
    $response->assertSee('Dedi Kurniawan');
    $response->assertSee('Hubungi Kurir via WhatsApp');
    $response->assertSee('Lunas (Midtrans)');
});

test('midtrans webhook notification marks pickup request as paid and updates status to driver_assigned', function () {
    $pickup = PickupRequest::create([
        'pickup_code' => 'PKP-202609-WEBHOOK1',
        'user_id' => $this->user->id,
        'categories' => ['Plastik'],
        'scheduled_date' => now()->addDay(),
        'scheduled_slot' => 'pagi',
        'address' => 'Jl. Senopati Raya No. 42, Kebayoran Baru, Jakarta Selatan',
        'estimated_weight' => 5.0,
        'base_fee' => 10000,
        'volume_surcharge' => 0,
        'distance_km' => 3.5,
        'distance_fee' => 7000,
        'service_fee' => 2000,
        'total_fee' => 19000,
        'payment_status' => 'unpaid',
        'payment_method' => 'Midtrans Digital',
        'snap_token' => 'mock-snap-token-webhook',
        'status' => 'confirmed',
        'courier_name' => 'Dedi Kurniawan',
        'courier_plate' => 'B 5542 PQM',
    ]);

    $orderId = $pickup->pickup_code;
    $statusCode = '200';
    $grossAmount = '19000.00';
    $serverKey = config('midtrans.server_key');
    $signatureKey = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

    $response = $this->postJson(route('midtrans.notification'), [
        'order_id' => $orderId,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'signature_key' => $signatureKey,
        'transaction_status' => 'settlement',
        'payment_type' => 'qris',
        'transaction_id' => 'midtrans-pkp-trx-101',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['status' => 'success']);

    $pickup->refresh();
    expect($pickup->payment_status)->toBe('paid');
    expect($pickup->status)->toBe('driver_assigned');
    expect($pickup->payment_method)->toBe('qris');
    expect($pickup->transaction_id)->toBe('midtrans-pkp-trx-101');
});
