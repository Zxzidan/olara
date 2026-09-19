<?php

namespace Database\Seeders;

use App\Models\DropoffPartner;
use App\Models\EcoPointTransaction;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceProduct;
use App\Models\PickupRequest;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\User;
use App\Models\WasteAnalysis;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OlaraDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default User (Zidan)
        $user = User::create([
            'name' => 'Zidan Ramadhan',
            'email' => 'zidan@olara.id',
            'password' => Hash::make('password123'),
            'eco_points' => 1450,
            'recycler_level' => 'Level 4 — Eco Champion',
            'membership_tier' => 'lite',
            'phone' => '+62 812-3456-7890',
            'address' => 'Jl. Senopati Raya No. 42, RT 04/RW 02, Kebayoran Baru, Jakarta Selatan',
            'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
        ]);

        // 2. Point Transactions for Zidan
        $transactions = [
            ['amount' => 50, 'type' => 'registration_bonus', 'description' => 'Bonus Selamat Datang Pengguna Baru OLARA', 'balance' => 50, 'days' => 45],
            ['amount' => 120, 'type' => 'waste_deposit', 'description' => 'Setor 12.4 kg Botol PET Bersih ke Bank Sampah Mandiri', 'balance' => 170, 'days' => 40],
            ['amount' => 250, 'type' => 'pickup_reward', 'description' => 'Penjemputan Armada 25 kg Kardus Press Gelombang', 'balance' => 420, 'days' => 32],
            ['amount' => 10, 'type' => 'scan_bonus', 'description' => 'Bonus Verifikasi Foto Timbangan Digital (AI Scanner)', 'balance' => 430, 'days' => 28],
            ['amount' => -250, 'type' => 'reward_redeem', 'description' => 'Penukaran Voucher Belanja Tokopedia Rp 25.000', 'balance' => 180, 'days' => 24],
            ['amount' => 320, 'type' => 'waste_deposit', 'description' => 'Setor 8 kg Kaleng Minuman Aluminium Grade A', 'balance' => 500, 'days' => 18],
            ['amount' => 150, 'type' => 'marketplace_purchase', 'description' => 'Cashback Poin Pembelian Bahan Daur Ulang PET Flakes', 'balance' => 650, 'days' => 14],
            ['amount' => -200, 'type' => 'reward_redeem', 'description' => 'Donasi Penanaman 1 Bibit Pohon Mangrove Pesisir Jawa', 'balance' => 450, 'days' => 10],
            ['amount' => 450, 'type' => 'waste_deposit', 'description' => 'Setor 15 kg E-Waste Elektronik & Kabel Komputer', 'balance' => 900, 'days' => 7],
            ['amount' => 100, 'type' => 'waste_deposit', 'description' => 'Setor 10 kg Botol Kaca Kering ke TPS3R', 'balance' => 1000, 'days' => 4],
            ['amount' => 25, 'type' => 'dropoff_bonus', 'description' => 'Bonus Insentif Mitra Resmi (Bank Sampah Mandiri Berseri)', 'balance' => 1025, 'days' => 3],
            ['amount' => 425, 'type' => 'waste_deposit', 'description' => 'Setoran Terverifikasi: 35 kg Campuran Plastik HDPE & PET', 'balance' => 1450, 'days' => 1],
        ];

        foreach ($transactions as $tx) {
            EcoPointTransaction::create([
                'user_id' => $user->id,
                'amount' => $tx['amount'],
                'type' => $tx['type'],
                'description' => $tx['description'],
                'balance_after' => $tx['balance'],
                'created_at' => now()->subDays($tx['days']),
            ]);
        }

        // 3. AI Waste Analyses
        WasteAnalysis::create([
            'user_id' => $user->id,
            'material_name' => 'Plastik PET (Polyethylene Terephthalate)',
            'category' => 'Plastik',
            'confidence_rate' => 98,
            'contamination_rate' => 4,
            'estimated_price_per_kg' => 4800,
            'weight_kg' => 12.4,
            'has_scale_photo' => true,
            'total_estimated_value' => 59520,
            'recommendation' => 'Kondisi sangat bersih. Lepas tutup botol dan label untuk memaksimalkan nilai jual daur ulang.',
            'status' => 'dropped_off',
            'created_at' => now()->subDays(40),
        ]);

        WasteAnalysis::create([
            'user_id' => $user->id,
            'material_name' => 'Kardus Gelombang Press (OCC)',
            'category' => 'Kertas',
            'confidence_rate' => 96,
            'contamination_rate' => 6,
            'estimated_price_per_kg' => 2100,
            'weight_kg' => 25.0,
            'has_scale_photo' => true,
            'total_estimated_value' => 52500,
            'recommendation' => 'Bahan kering dan bebas minyak makanan. Lipat rapat dan ikat dengan tali rami.',
            'status' => 'scheduled_pickup',
            'created_at' => now()->subDays(32),
        ]);

        WasteAnalysis::create([
            'user_id' => $user->id,
            'material_name' => 'Kaleng Minuman Aluminium UBC',
            'category' => 'Logam',
            'confidence_rate' => 99,
            'contamination_rate' => 2,
            'estimated_price_per_kg' => 18500,
            'weight_kg' => 8.0,
            'has_scale_photo' => false,
            'total_estimated_value' => 148000,
            'recommendation' => 'Kempeskan kaleng untuk meminimalkan volume pengangkutan.',
            'status' => 'analyzed',
            'created_at' => now()->subDays(18),
        ]);

        // 4. Pickup Requests
        PickupRequest::create([
            'pickup_code' => 'PKP-202609-089',
            'user_id' => $user->id,
            'categories' => ['Plastik', 'Kertas/Kardus'],
            'scheduled_date' => now()->addDay(),
            'scheduled_slot' => 'pagi',
            'address' => 'Jl. Senopati Raya No. 42, RT 04/RW 02, Kebayoran Baru, Jakarta Selatan',
            'notes' => 'Sampah sudah dipilah dalam 2 karung terpisah, ditaruh di dekat gerbang depan.',
            'estimated_weight' => 15.0,
            'base_fee' => 10000,
            'volume_surcharge' => 5000,
            'distance_km' => 3.2,
            'distance_fee' => 6000,
            'service_fee' => 2000,
            'total_fee' => 23000,
            'status' => 'on_the_way',
            'courier_name' => 'Budi Santoso',
            'courier_plate' => 'B 4219 SZR',
            'courier_phone' => '+62 878-1122-3344',
            'created_at' => now()->subHours(2),
        ]);

        PickupRequest::create([
            'pickup_code' => 'PKP-202608-012',
            'user_id' => $user->id,
            'categories' => ['Kertas/Kardus', 'Logam'],
            'scheduled_date' => now()->subDays(32),
            'scheduled_slot' => 'siang',
            'address' => 'Jl. Senopati Raya No. 42, Kebayoran Baru, Jakarta Selatan',
            'notes' => 'Kardus dan drum kaleng bekas.',
            'estimated_weight' => 28.0,
            'base_fee' => 10000,
            'volume_surcharge' => 10000,
            'distance_km' => 3.0,
            'distance_fee' => 6000,
            'service_fee' => 2000,
            'total_fee' => 28000,
            'status' => 'completed',
            'courier_name' => 'Ahmad Faisal',
            'courier_plate' => 'B 3108 TKL',
            'courier_phone' => '+62 813-9988-7766',
            'created_at' => now()->subDays(32),
        ]);

        // 5. Dropoff Partners
        $partners = [
            [
                'name' => 'Bank Sampah Mandiri Berseri',
                'address' => 'Jl. Cisadane No. 18, Cikini, Menteng, Jakarta Pusat',
                'city' => 'Jakarta Pusat',
                'latitude' => -6.1954120,
                'longitude' => 106.8375120,
                'phone' => '(021) 314-2201',
                'operating_hours' => 'Senin - Sabtu: 08.00 - 17.00 WIB',
                'is_open' => true,
                'is_premium_partner' => true,
                'bonus_points' => 25,
                'accepted_materials' => ['Plastik PET', 'Plastik HDPE', 'Kardus OCC', 'Logam Aluminium', 'Kaca'],
            ],
            [
                'name' => 'TPS3R Kebayoran Hijau Lestari',
                'address' => 'Jl. Hang Lekiu III No. 5, Kebayoran Baru, Jakarta Selatan',
                'city' => 'Jakarta Selatan',
                'latitude' => -6.2391450,
                'longitude' => 106.7928230,
                'phone' => '(021) 720-8891',
                'operating_hours' => 'Senin - Minggu: 07.30 - 16.30 WIB',
                'is_open' => true,
                'is_premium_partner' => true,
                'bonus_points' => 25,
                'accepted_materials' => ['Plastik', 'Kertas', 'Organik Kompos', 'Logam'],
            ],
            [
                'name' => 'Pengepul Logam & Kertas Berkah Jaya',
                'address' => 'Jl. Tebet Barat Raya No. 44, Tebet, Jakarta Selatan',
                'city' => 'Jakarta Selatan',
                'latitude' => -6.2312000,
                'longitude' => 106.8523000,
                'phone' => '0812-8877-6655',
                'operating_hours' => 'Senin - Sabtu: 08.30 - 18.00 WIB',
                'is_open' => true,
                'is_premium_partner' => false,
                'bonus_points' => 15,
                'accepted_materials' => ['Kardus', 'Kertas Duplek', 'Aluminium', 'Besi Tua', 'Tembaga'],
            ],
            [
                'name' => 'Bank Sampah Induk Gesit Cempaka Putih',
                'address' => 'Jl. Cempaka Putih Tengah XX No. 12, Jakarta Pusat',
                'city' => 'Jakarta Pusat',
                'latitude' => -6.1772100,
                'longitude' => 106.8711400,
                'phone' => '(021) 424-9988',
                'operating_hours' => 'Senin - Jumat: 08.00 - 16.00 WIB',
                'is_open' => true,
                'is_premium_partner' => true,
                'bonus_points' => 25,
                'accepted_materials' => ['Semua Plastik', 'Kertas', 'Minyak Jelantah', 'E-Waste'],
            ],
            [
                'name' => 'Sentra Daur Ulang BSD Eco Center',
                'address' => 'Kawasan Intermoda BSD City, Cisauk, Tangerang',
                'city' => 'Tangerang',
                'latitude' => -6.3214500,
                'longitude' => 106.6508900,
                'phone' => '(021) 538-4422',
                'operating_hours' => 'Senin - Sabtu: 09.00 - 17.00 WIB',
                'is_open' => true,
                'is_premium_partner' => true,
                'bonus_points' => 20,
                'accepted_materials' => ['Plastik Botol', 'TetraPak', 'Kardus', 'E-Waste'],
            ],
            [
                'name' => 'TPS3R Rawamangun Asri Bersatu',
                'address' => 'Jl. Balai Pustaka Timur No. 10, Rawamangun, Jakarta Timur',
                'city' => 'Jakarta Timur',
                'latitude' => -6.1965000,
                'longitude' => 106.8872000,
                'phone' => '(021) 478-1200',
                'operating_hours' => 'Senin - Sabtu: 08.00 - 16.00 WIB',
                'is_open' => true,
                'is_premium_partner' => false,
                'bonus_points' => 15,
                'accepted_materials' => ['Plastik', 'Kertas', 'Kaca', 'Organik'],
            ],
        ];

        foreach ($partners as $partner) {
            DropoffPartner::create($partner);
        }

        // 6. Rewards Catalog
        $rewards = [
            [
                'title' => 'Saldo GoPay Rp 50.000',
                'category' => 'e_wallet',
                'provider' => 'GoPay',
                'points_required' => 500,
                'value_idr' => 50000,
                'stock' => 150,
                'description' => 'Saldo e-wallet GoPay instan langsung masuk ke nomor ponsel terdaftar.',
                'badge_text' => 'Cair Instan',
            ],
            [
                'title' => 'Saldo OVO Cash Rp 100.000',
                'category' => 'e_wallet',
                'provider' => 'OVO',
                'points_required' => 1000,
                'value_idr' => 100000,
                'stock' => 80,
                'description' => 'Saldo OVO Cash dapat digunakan untuk belanja, transportasi, dan pembayaran tagihan.',
                'badge_text' => 'Terpopuler',
            ],
            [
                'title' => 'Voucher Diskon Belanja Tokopedia Rp 25.000',
                'category' => 'voucher',
                'provider' => 'Tokopedia',
                'points_required' => 250,
                'value_idr' => 25000,
                'stock' => 200,
                'description' => 'Voucher potongan belanja tanpa minimum transaksi di official store Tokopedia.',
                'badge_text' => 'Belanja Ritel',
            ],
            [
                'title' => 'Voucher Kuliner Solaria Rp 50.000',
                'category' => 'voucher',
                'provider' => 'Solaria',
                'points_required' => 500,
                'value_idr' => 50000,
                'stock' => 60,
                'description' => 'Voucher makan lezat berlaku di seluruh gerai Solaria seluruh Indonesia.',
                'badge_text' => 'F&B Hemat',
            ],
            [
                'title' => '1x Tiket Nonton Cinema XXI (Studio Regular)',
                'category' => 'voucher',
                'provider' => 'Cinema XXI',
                'points_required' => 400,
                'value_idr' => 45000,
                'stock' => 90,
                'description' => 'E-voucher tiket bioskop XXI untuk hari Senin hingga Kamis di bioskop rekanan.',
                'badge_text' => 'Hiburan',
            ],
            [
                'title' => 'Voucher Bonus Saldo Bermain Timezone Rp 35.000',
                'category' => 'voucher',
                'provider' => 'Timezone',
                'points_required' => 300,
                'value_idr' => 35000,
                'stock' => 120,
                'description' => 'Bonus saldo Tizo bermain game arcade keluarga di Timezone.',
                'badge_text' => 'Keluarga',
            ],
            [
                'title' => 'Voucher Potongan Frame Kacamata Optik Seis 25%',
                'category' => 'voucher',
                'provider' => 'Optik Seis',
                'points_required' => 600,
                'value_idr' => 75000,
                'stock' => 50,
                'description' => 'Diskon 25% pembelian frame kacamata merek ternama di Optik Seis.',
                'badge_text' => 'Fashion',
            ],
            [
                'title' => 'Paket Medical Check-Up Mandiri (Kolesterol & Gula Darah)',
                'category' => 'voucher',
                'provider' => 'Klinik Kimia Farma',
                'points_required' => 1200,
                'value_idr' => 150000,
                'stock' => 40,
                'description' => 'Pemeriksaan kesehatan preventif di klinik laboratorium Kimia Farma terdekat.',
                'badge_text' => 'Kesehatan',
            ],
            [
                'title' => 'Donasi 1 Bibit Pohon Mangrove Pesisir Jawa',
                'category' => 'donasi',
                'provider' => 'Yayasan LindungiHutan',
                'points_required' => 200,
                'value_idr' => 25000,
                'stock' => 9999,
                'description' => 'Poin dikonversikan menjadi 1 bibit mangrove bersertifikat untuk mencegah abrasi pesisir.',
                'badge_text' => 'Aksi Hijau Nyata',
            ],
        ];

        foreach ($rewards as $reward) {
            $createdReward = Reward::create($reward);

            if ($createdReward->title === 'Voucher Diskon Belanja Tokopedia Rp 25.000') {
                RewardRedemption::create([
                    'user_id' => $user->id,
                    'reward_id' => $createdReward->id,
                    'points_spent' => 250,
                    'redemption_code' => 'TKPD-OLARA-9281',
                    'status' => 'active',
                    'created_at' => now()->subDays(24),
                ]);
            }
        }

        // 7. Marketplace Products
        $products = [
            [
                'name' => 'PET Flakes Bening Bening Grade A (Hot Washed)',
                'grade' => 'Grade A Hot Washed',
                'category' => 'Plastik',
                'price_per_kg' => 11500,
                'min_order_kg' => 50,
                'stock_kg' => 4500,
                'description' => 'Serpihan cacahan botol plastik PET bening berkualitas tinggi, dicuci air panas (hot washed), kadar kelembapan < 1%, bebas kontaminasi PVC. Cocok untuk industri benang serat poliester dan kemasan rPET pangan.',
                'co2_savings_per_kg' => 2.1,
            ],
            [
                'name' => 'Bubur Kertas Putih Daur Ulang (De-inked Pulp)',
                'grade' => 'Industrial De-inked',
                'category' => 'Kertas',
                'price_per_kg' => 6200,
                'min_order_kg' => 100,
                'stock_kg' => 8000,
                'description' => 'Bubur kertas daur ulang siap cetak yang telah melalui proses penghilangan tinta (de-inking). Sangat ideal untuk pabrik pembuatan karton telur, paper cup, kardus box duplex, dan packaging ramah lingkungan.',
                'co2_savings_per_kg' => 1.5,
            ],
            [
                'name' => 'Ingot Aluminium Remelted 99% Kemurnian',
                'grade' => 'Alloy 99.0% Purity',
                'category' => 'Logam',
                'price_per_kg' => 28000,
                'min_order_kg' => 25,
                'stock_kg' => 1200,
                'description' => 'Batangan aluminium cor hasil peleburan kembali kaleng minuman bekas UBC (Used Beverage Cans). Bebas terak, standar industri komponen otomotif dan konstruksi profil aluminium.',
                'co2_savings_per_kg' => 8.5,
            ],
            [
                'name' => 'Kardus Press Gelombang Bale (Grade OCC Premium)',
                'grade' => 'Old Corrugated Container (OCC)',
                'category' => 'Kertas',
                'price_per_kg' => 2100,
                'min_order_kg' => 200,
                'stock_kg' => 15000,
                'description' => 'Kardus bekas gelombang yang telah diikat kencang dengan hidrolik baler machine (bale press). Kadar air rendah (<12%), bersih dari perekat lakban berlebih dan plastik pembungkus.',
                'co2_savings_per_kg' => 1.2,
            ],
            [
                'name' => 'HDPE Regrind Pellet Biru & Merah Sortir',
                'grade' => 'Blow Moulding Grade',
                'category' => 'Plastik',
                'price_per_kg' => 9800,
                'min_order_kg' => 50,
                'stock_kg' => 3200,
                'description' => 'Cacahan plastik High-Density Polyethylene dari jerigen dan botol sampo bekas. Melt flow index stabil, siap langsung masuk mesin injection moulding atau blow extrusion.',
                'co2_savings_per_kg' => 1.8,
            ],
            [
                'name' => 'Kain Perca Katun Sortir Warna Konveksi',
                'grade' => 'Sorted 100% Cotton',
                'category' => 'Tekstil',
                'price_per_kg' => 4500,
                'min_order_kg' => 30,
                'stock_kg' => 2000,
                'description' => 'Sisa potongan kain katun murni bersih dari pabrik garmen. Sangat dicari untuk industri pengrajin keset daur ulang, peredam suara interior otomotif, dan kain majun pabrik.',
                'co2_savings_per_kg' => 2.4,
            ],
        ];

        foreach ($products as $prod) {
            MarketplaceProduct::create($prod);
        }

        // 8. Sample Past Marketplace Orders (In-transit and Completed)
        MarketplaceOrder::create([
            'order_number' => 'ORD-202609-001',
            'user_id' => $user->id,
            'items' => [
                [
                    'name' => 'PET Flakes Bening Grade A',
                    'grade' => 'Grade A (Pencucian Panas)',
                    'price' => 11500,
                    'qty_kg' => 50,
                    'total' => 575000,
                ],
            ],
            'shipping_address' => 'Gudang Workshop Olara, Jl. Industri Hijau No. 8, Cikarang, Jawa Barat',
            'subtotal' => 575000,
            'ppn_amount' => 57500,
            'shipping_fee' => 45000,
            'grand_total' => 677500,
            'payment_method' => 'BCA Virtual Account',
            'payment_status' => 'paid',
            'shipping_status' => 'dikirim',
            'courier_name' => 'JNE Trucking (JTR)',
            'tracking_number' => 'JTR-982103891',
            'estimated_delivery_date' => now()->addDay()->toDateString(),
            'recipient_name' => 'Zidan Ramadhan',
            'co2_saved_kg' => 105.0,
            'points_earned' => 57,
            'created_at' => now()->subDays(2),
        ]);

        MarketplaceOrder::create([
            'order_number' => 'ORD-202608-088',
            'user_id' => $user->id,
            'items' => [
                [
                    'name' => 'HDPE Pellet Daur Ulang Biru',
                    'grade' => 'Injeksi / Blow Molding MFI 0.8',
                    'price' => 13500,
                    'qty_kg' => 100,
                    'total' => 1350000,
                ],
            ],
            'shipping_address' => 'Gudang Workshop Olara, Jl. Industri Hijau No. 8, Cikarang, Jawa Barat',
            'subtotal' => 1350000,
            'ppn_amount' => 135000,
            'shipping_fee' => 65000,
            'grand_total' => 1550000,
            'payment_method' => 'Mandiri Virtual Account',
            'payment_status' => 'paid',
            'shipping_status' => 'selesai',
            'courier_name' => 'SiCepat Cargo (GOKIL)',
            'tracking_number' => '004128917821',
            'estimated_delivery_date' => now()->subDays(5)->toDateString(),
            'delivered_at' => now()->subDays(4),
            'completed_at' => now()->subDays(4),
            'recipient_name' => 'Zidan Ramadhan (Penerima Satpam)',
            'co2_saved_kg' => 210.0,
            'points_earned' => 135,
            'created_at' => now()->subDays(6),
        ]);
    }
}
