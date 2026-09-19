@extends('layouts.app')

@section('title', 'Jemput Sampah On-Demand — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full">
                Layanan Logistik Hijau
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1B211E] tracking-tight mt-1">
                Penjemputan Sampah On-Demand
            </h1>
            <p class="text-sm text-[#66716B] mt-1">
                Armada kurir mitra OLARA siap menjemput sampah daur ulang Anda langsung dari depan pintu rumah.
            </p>
        </div>

        @if($user && $user->membership_tier === 'premium')
            <div class="inline-flex items-center gap-2 bg-amber-50 border border-amber-300 px-3.5 py-2 rounded-2xl text-amber-800 text-xs font-bold shadow-sm">
                <i data-lucide="crown" class="w-4 h-4 text-amber-500"></i>
                <span>Member Premium: Bebas Biaya Dasar Penjemputan (Sisa 4x kuota)</span>
            </div>
        @endif
    </div>

    <!-- Active Pickup Banner if exists -->
    @if($activePickup)
        <div class="bg-white border-2 border-[#168A5B] rounded-3xl p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#EEF9F2] text-[#168A5B] flex items-center justify-center shrink-0">
                    <i data-lucide="truck" class="w-6 h-6 animate-pulse"></i>
                </div>
                <div>
                    <span class="text-xs font-mono font-bold text-[#168A5B]">{{ $activePickup->pickup_code }}</span>
                    <h3 class="text-base font-bold text-[#1B211E]">Kurir Sedang Dalam Perjalanan Penjemputan</h3>
                    <p class="text-xs text-[#66716B]">Mitra Kurir: <strong>{{ $activePickup->courier_name }}</strong> ({{ $activePickup->courier_plate }})</p>
                </div>
            </div>
            <a href="{{ route('pickup.show', $activePickup->pickup_code) }}" class="py-2.5 px-5 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-xs font-bold transition shadow-sm flex items-center gap-2">
                <i data-lucide="navigation" class="w-4 h-4"></i> Lacak Peta Real-Time
            </a>
        </div>
    @endif

    <!-- Booking Form & Price Calculator (2-Column Grid) -->
    <form action="{{ route('pickup.store') }}" method="POST" id="pickupForm" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf

        <!-- Left 7 Cols: Booking Steps -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Step 1: Kategori Multi-Sampah -->
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#168A5B] text-white text-xs font-bold flex items-center justify-center">1</span>
                        <h3 class="text-base font-bold text-[#1B211E]">Pilih Kategori Sampah</h3>
                    </div>
                    <span class="text-xs text-gray-400 font-medium">Bisa pilih lebih dari satu</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @php
                        $categories = [
                            ['id' => 'Plastik', 'name' => 'Plastik', 'icon' => 'recycle', 'desc' => 'PET, HDPE, Galon'],
                            ['id' => 'Kertas/Kardus', 'name' => 'Kertas / Kardus', 'icon' => 'file-text', 'desc' => 'Kardus, Buku, Duplek'],
                            ['id' => 'Logam', 'name' => 'Logam / Kaleng', 'icon' => 'box', 'desc' => 'Aluminium, Besi, Kaleng'],
                            ['id' => 'Kaca', 'name' => 'Kaca & Beling', 'icon' => 'wine', 'desc' => 'Botol Sirup, Toples'],
                            ['id' => 'Organik', 'name' => 'Organik Bersih', 'icon' => 'sprout', 'desc' => 'Sisa Sayur, Daun Kering'],
                            ['id' => 'E-Waste', 'name' => 'Elektronik Rusak', 'icon' => 'cpu', 'desc' => 'Kabel, HP, Komputer'],
                        ];
                    @endphp

                    @foreach($categories as $cat)
                        <label class="category-card cursor-pointer p-3.5 rounded-2xl border border-[#DDE3DF] hover:border-[#168A5B] flex flex-col items-center text-center transition bg-[#F7F8F6] has-[:checked]:bg-[#EEF9F2] has-[:checked]:border-[#168A5B]">
                            <input type="checkbox" name="categories[]" value="{{ $cat['id'] }}" class="hidden" {{ ($prefillCategory === $cat['name'] || $cat['id'] === 'Plastik') ? 'checked' : '' }} onchange="calculatePickupFees()" />
                            <div class="w-10 h-10 rounded-xl bg-white text-[#168A5B] flex items-center justify-center mb-2 shadow-xs">
                                <i data-lucide="{{ $cat['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-[#1B211E]">{{ $cat['name'] }}</span>
                            <span class="text-[10px] text-gray-400 mt-0.5">{{ $cat['desc'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Step 2: Estimasi Berat & Volume -->
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#168A5B] text-white text-xs font-bold flex items-center justify-center">2</span>
                        <h3 class="text-base font-bold text-[#1B211E]">Estimasi Berat Total (kg)</h3>
                    </div>
                    <span class="text-xs text-gray-400 font-medium">Batas armada: s/d 100 kg</span>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <input type="range" min="1" max="50" step="1" id="weightRange" value="{{ $prefillWeight ?? 5 }}" class="w-full accent-[#168A5B]" oninput="syncWeight(this.value)" />
                        <div class="flex items-center gap-1 bg-[#EEF9F2] border border-[#BFE7D0] px-3 py-1.5 rounded-xl shrink-0">
                            <input type="number" min="1" max="100" name="estimated_weight" id="weightNum" value="{{ $prefillWeight ?? 5 }}" class="w-12 text-center text-sm font-extrabold text-[#0B4F38] bg-transparent focus:outline-none" oninput="syncWeightRange(this.value)" />
                            <span class="text-xs font-bold text-[#168A5B]">kg</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400">
                        *Untuk berat di atas 10 kg, dikenakan biaya penanganan volume sebesar Rp 5.000 per kelipatan 10 kg.
                    </p>
                </div>
            </div>

            <!-- Step 3: Waktu & Alamat Penjemputan -->
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#168A5B] text-white text-xs font-bold flex items-center justify-center">3</span>
                        <h3 class="text-base font-bold text-[#1B211E]">Waktu & Alamat Penjemputan</h3>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Date & Slot Picker -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#1B211E] mb-1.5">Tanggal Penjemputan</label>
                            <input type="date" name="scheduled_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-xs font-medium focus:ring-2 focus:ring-[#168A5B] focus:outline-none" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#1B211E] mb-1.5">Pilihan Slot Waktu</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer p-2 rounded-xl border border-[#DDE3DF] text-center has-[:checked]:bg-[#EEF9F2] has-[:checked]:border-[#168A5B]">
                                    <input type="radio" name="scheduled_slot" value="pagi" checked class="hidden" />
                                    <span class="block text-xs font-bold text-[#1B211E]">Pagi</span>
                                    <span class="block text-[10px] text-gray-500">08:00 - 11:00</span>
                                </label>
                                <label class="cursor-pointer p-2 rounded-xl border border-[#DDE3DF] text-center has-[:checked]:bg-[#EEF9F2] has-[:checked]:border-[#168A5B]">
                                    <input type="radio" name="scheduled_slot" value="siang" class="hidden" />
                                    <span class="block text-xs font-bold text-[#1B211E]">Siang</span>
                                    <span class="block text-[10px] text-gray-500">13:00 - 16:00</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Address Input -->
                    <div>
                        <label class="block text-xs font-bold text-[#1B211E] mb-1.5">Alamat Lengkap Rumah / Kantor</label>
                        <textarea name="address" rows="2" required class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-xs focus:ring-2 focus:ring-[#168A5B] focus:outline-none" placeholder="Masukkan nama jalan, nomor rumah, RT/RW, dan patokan">{{ $user->address ?? 'Jl. Senopati Raya No. 42, RT 04/RW 02, Kebayoran Baru, Jakarta Selatan' }}</textarea>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-bold text-[#1B211E] mb-1.5">Catatan Khusus untuk Kurir (Opsional)</label>
                        <input type="text" name="notes" class="w-full px-4 py-2 rounded-xl border border-[#DDE3DF] text-xs focus:ring-2 focus:ring-[#168A5B] focus:outline-none" placeholder="Contoh: Sampah diletakkan di samping pos sekuriti, sudah diikat dalam karung" />
                    </div>
                </div>
            </div>

        </div>

        <!-- Right 5 Cols: Transparent Fee Breakdown & Sticky Confirmation CTA -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-5 sticky top-24">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2 py-0.5 rounded">Transparansi Biaya</span>
                    <h3 class="text-lg font-extrabold text-[#1B211E] mt-1">Rincian Tarif Penjemputan</h3>
                    <p class="text-xs text-[#66716B]">Biaya dapat dipotong otomatis dari estimasi nilai tukar poin sampah Anda.</p>
                </div>

                <!-- Price Breakdown Table (PRD Section 4 Spec) -->
                <div class="space-y-2.5 text-xs divide-y divide-gray-100">
                    <div class="flex items-center justify-between pt-1">
                        <span class="text-gray-600">Biaya Dasar Penjemputan:</span>
                        <span id="feeBase" class="font-bold text-[#1B211E] tabular-nums">
                            {{ $user && $user->membership_tier === 'premium' ? 'Rp 0 (Gratis Premium)' : 'Rp 10.000' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-2.5">
                        <span class="text-gray-600">Surcharge Volume (>10 kg):</span>
                        <span id="feeVolume" class="font-bold text-[#1B211E] tabular-nums">Rp 0</span>
                    </div>

                    <div class="flex items-center justify-between pt-2.5">
                        <div class="flex items-center gap-1 text-gray-600">
                            <span>Biaya Jarak Armada:</span>
                            <span class="text-[10px] bg-gray-100 px-1.5 py-0.5 rounded">3.2 km</span>
                        </div>
                        <span id="feeDistance" class="font-bold text-[#1B211E] tabular-nums">Rp 6.000</span>
                    </div>

                    <div class="flex items-center justify-between pt-2.5">
                        <span class="text-gray-600">Biaya Layanan & Asuransi:</span>
                        <span id="feeService" class="font-bold text-[#1B211E] tabular-nums">
                            {{ $user && $user->membership_tier === 'premium' ? 'Rp 0 (Gratis Premium)' : 'Rp 2.000' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-3 text-sm">
                        <span class="font-extrabold text-[#0B4F38]">Total Biaya Penjemputan:</span>
                        <span id="feeTotal" class="font-extrabold text-[#168A5B] text-lg tabular-nums">Rp 18.000</span>
                    </div>
                </div>

                <!-- Incentive Eco-Point Notice -->
                <div class="p-3.5 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0] flex items-center gap-3">
                    <span class="text-xl">🌿</span>
                    <p class="text-[11px] text-[#0B4F38] leading-tight">
                        Setelah material ditimbang oleh kurir, Anda akan mendapatkan <strong>Eco-Points</strong> yang langsung dikreditkan ke dompet Anda!
                    </p>
                </div>

                <!-- Submit CTA Button -->
                <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-sm font-bold shadow-md transition flex items-center justify-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Konfirmasi Penjemputan Sekarang
                </button>

                <p class="text-[10px] text-gray-400 text-center">
                    Dengan menekan tombol, Anda menyetujui jadwal penjemputan oleh kurir resmi OLARA.
                </p>
            </div>
        </div>

    </form>

</div>

<script>
    const isPremium = {{ ($user && $user->membership_tier === 'premium') ? 'true' : 'false' }};

    function syncWeight(val) {
        document.getElementById('weightNum').value = val;
        calculatePickupFees();
    }

    function syncWeightRange(val) {
        document.getElementById('weightRange').value = val;
        calculatePickupFees();
    }

    function calculatePickupFees() {
        const weight = parseFloat(document.getElementById('weightNum').value) || 5;
        const distance = 3.2;

        let baseFee = isPremium ? 0 : 10000;
        let serviceFee = isPremium ? 0 : 2000;
        let volumeSurcharge = (weight > 10) ? Math.ceil((weight - 10) / 10) * 5000 : 0;
        let distanceFee = Math.round(distance * 2000);

        let total = baseFee + volumeSurcharge + distanceFee + serviceFee;

        document.getElementById('feeBase').textContent = isPremium ? 'Rp 0 (Gratis Premium)' : `Rp ${baseFee.toLocaleString('id-ID')}`;
        document.getElementById('feeVolume').textContent = `Rp ${volumeSurcharge.toLocaleString('id-ID')}`;
        document.getElementById('feeDistance').textContent = `Rp ${distanceFee.toLocaleString('id-ID')}`;
        document.getElementById('feeService').textContent = isPremium ? 'Rp 0 (Gratis Premium)' : `Rp ${serviceFee.toLocaleString('id-ID')}`;
        document.getElementById('feeTotal').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    // Run on init
    calculatePickupFees();
</script>
@endsection
