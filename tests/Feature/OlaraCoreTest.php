<?php

use App\Models\MarketplaceProduct;
use App\Models\Reward;
use App\Models\User;
use Database\Seeders\OlaraDatabaseSeeder;

beforeEach(function () {
    $this->seed(OlaraDatabaseSeeder::class);
    $this->user = User::where('email', 'zidan@olara.id')->first();
});

test('landing page renders successfully with softly theme and features', function () {
    $response = $this->get(route('landing'));

    $response->assertStatus(200);
    $response->assertSee('OLARA');
    $response->assertSee('Ubah sampah harian jadi berkah');
    $response->assertSee('Kamera AI Pintar');
    $response->assertSee('Marketplace B2B');
    $response->assertSee('Tanya Jawab Populer');
});

test('guest can view login and register pages', function () {
    $loginResponse = $this->get(route('login'));
    $loginResponse->assertStatus(200);
    $loginResponse->assertSee('Masuk ke Akun OLARA');

    $registerResponse = $this->get(route('register'));
    $registerResponse->assertStatus(200);
    $registerResponse->assertSee('Buat Akun Baru');
});

test('demo login sets session and redirects to dashboard', function () {
    $response = $this->get(route('demo.login'));

    $response->assertRedirect(route('home'));
    $this->assertAuthenticated();
});

test('dashboard page renders successfully with new layout', function () {
    $response = $this->actingAs($this->user)->get(route('home'));

    $response->assertStatus(200);
});

test('new user registration automatically receives 50 eco-points welcome bonus', function () {
    $email = 'pengguna.baru@olara.id';

    $response = $this->post(route('register.submit'), [
        'name' => 'Pengguna Baru',
        'email' => $email,
        'password' => 'password123',
        'phone' => '081299998888',
    ]);

    $response->assertRedirect(route('home'));

    $newUser = User::where('email', $email)->first();
    expect($newUser)->not->toBeNull();
    expect($newUser->eco_points)->toBe(50);

    // Verify registration bonus transaction logged
    $tx = $newUser->pointTransactions()->where('type', 'registration_bonus')->first();
    expect($tx)->not->toBeNull();
    expect($tx->amount)->toBe(50);
});

test('ai scanner page renders and can record waste analysis with scale photo bonus', function () {
    $initialPoints = $this->user->eco_points;

    $response = $this->actingAs($this->user)->post(route('scanner.save'), [
        'material_name' => 'Plastik Botol Minuman PET',
        'category' => 'Plastik',
        'confidence_rate' => 98,
        'contamination_rate' => 4,
        'estimated_price_per_kg' => 4800,
        'weight_kg' => 10.0,
        'has_scale_photo' => 1,
        'recommendation' => 'Kondisi sangat bersih.',
        'action_type' => 'save',
    ]);

    $response->assertRedirect(route('scanner.index'));

    // Check bonus awarded
    $this->user->refresh();
    expect($this->user->eco_points)->toBe($initialPoints + 10);
});

test('pickup request booking creates tracking code and transparent pricing', function () {
    $response = $this->actingAs($this->user)->post(route('pickup.store'), [
        'categories' => ['Plastik', 'Kertas/Kardus'],
        'scheduled_date' => now()->addDay()->format('Y-m-d'),
        'scheduled_slot' => 'pagi',
        'address' => 'Jl. Sudirman No. 12, Jakarta Pusat',
        'notes' => 'Tolong telepon sebelum sampai',
        'estimated_weight' => 12.0,
        'distance_km' => 3.0,
    ]);

    $response->assertRedirect();
    $pickup = $this->user->pickupRequests()->latest()->first();
    expect($pickup)->not->toBeNull();
    expect($pickup->status)->toBe('confirmed');
    expect($pickup->pickup_code)->toStartWith('PKP-');
});

test('dropoff directory page renders with interactive map data', function () {
    $response = $this->actingAs($this->user)->get(route('dropoff.index'));

    $response->assertStatus(200);
    $response->assertSee('Bank Sampah Mandiri Berseri');
    $response->assertSee('Mitra Resmi');
});

test('rewards redemption deducts points and generates unique code', function () {
    $reward = Reward::where('points_required', 250)->first();
    expect($reward)->not->toBeNull();

    $initialPoints = $this->user->eco_points;

    $response = $this->actingAs($this->user)->post(route('rewards.redeem', $reward->id));
    $response->assertRedirect(route('rewards.index'));

    $this->user->refresh();
    expect($this->user->eco_points)->toBe($initialPoints - 250);

    $redemption = $this->user->rewardRedemptions()->where('reward_id', $reward->id)->latest()->first();
    expect($redemption)->not->toBeNull();
    expect($redemption->redemption_code)->not->toBeEmpty();
});

test('analytics page renders ecological impact and carbon calculations', function () {
    $response = $this->actingAs($this->user)->get(route('analytics.index'));

    $response->assertStatus(200);
    $response->assertSee('Laporan Dampak Ekologis');
    $response->assertSee('kg CO₂e');
});

test('marketplace allows order checkout with 10 percent PPN and awards points', function () {
    $product = MarketplaceProduct::first();
    expect($product)->not->toBeNull();

    $response = $this->actingAs($this->user)->post(route('marketplace.checkout'), [
        'product_id' => $product->id,
        'quantity_kg' => $product->min_order_kg,
        'shipping_address' => 'Gudang PT Maju Bersama, Kawasan Pulogadung, Jakarta Timur',
        'payment_method' => 'BCA Virtual Account',
    ]);

    $order = $this->user->marketplaceOrders()->latest()->first();
    expect($order)->not->toBeNull();
    $response->assertRedirect(route('marketplace.orderDetail', $order->order_number));
    expect((float) $order->ppn_amount)->toBeGreaterThan(0);
});

test('membership upgrade switches tier and awards bonus points', function () {
    expect($this->user->membership_tier)->toBe('lite');

    $response = $this->actingAs($this->user)->post(route('membership.upgrade'), [
        'tier' => 'premium',
    ]);

    $response->assertRedirect(route('membership.index'));

    $this->user->refresh();
    expect($this->user->membership_tier)->toBe('premium');
});

test('marketplace orders list page renders and can be filtered by shipping status', function () {
    $response = $this->actingAs($this->user)->get(route('marketplace.orders'));

    $response->assertStatus(200);
    $response->assertSee('Pelacakan Pengiriman');
    $response->assertSee('ORD-202609-001');

    $filterResponse = $this->actingAs($this->user)->get(route('marketplace.orders', ['status' => 'dikirim']));
    $filterResponse->assertStatus(200);
    $filterResponse->assertSee('ORD-202609-001');
});

test('marketplace order tracking detail page displays courier and timeline stepper', function () {
    $order = $this->user->marketplaceOrders()->where('shipping_status', 'dikirim')->first();
    expect($order)->not->toBeNull();

    $response = $this->actingAs($this->user)->get(route('marketplace.orderDetail', $order->order_number));

    $response->assertStatus(200);
    $response->assertSee($order->courier_name);
    $response->assertSee($order->tracking_number);
    $response->assertSee('Konfirmasi Barang Sudah Sampai');
});

test('user can confirm delivery arrival like shopee and completes order', function () {
    $order = $this->user->marketplaceOrders()->where('shipping_status', 'dikirim')->first();
    expect($order)->not->toBeNull();

    $detailUrl = route('marketplace.orderDetail', $order->order_number);
    $response = $this->actingAs($this->user)
        ->from($detailUrl)
        ->post(route('marketplace.confirmDelivery', $order->order_number));

    $response->assertRedirect($detailUrl);
    $response->assertSessionHas('success');

    $order->refresh();
    expect($order->shipping_status)->toBe('selesai');
    expect($order->delivered_at)->not->toBeNull();
    expect($order->completed_at)->not->toBeNull();
});
