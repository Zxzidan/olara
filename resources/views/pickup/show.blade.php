@extends('layouts.app')

@section('title', 'Lacak Penjemputan ' . $pickup->pickup_code . ' — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('pickup.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#168A5B] hover:text-[#0F6B47]">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Form Penjemputan
        </a>
        <span class="text-xs font-mono font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">
            KODE: {{ $pickup->pickup_code }}
        </span>
    </div>

    <!-- Stepper Status Tracker (PRD: Requested -> Confirmed -> Driver Assigned -> On The Way -> Arrived -> Completed) -->
    <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full">
                Status Pelacakan Armada
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-[#1B211E] mt-2">
                Kurir Menuju Lokasi Penjemputan
            </h2>
            <p class="text-xs text-[#66716B] mt-1">
                Estimasi tiba dalam waktu <strong>15–25 menit</strong>. Pastikan sampah telah disiapkan di area yang mudah diakses.
            </p>
        </div>

        <!-- 5-Step Stepper -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 relative">
            <!-- Step 1 -->
            <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0]">
                <div class="w-8 h-8 rounded-full bg-[#168A5B] text-white flex items-center justify-center text-xs font-bold mb-2">
                    ✓
                </div>
                <span class="text-xs font-bold text-[#0B4F38]">Pesanan Dibuat</span>
                <span class="text-[10px] text-[#168A5B] font-medium">Terkonfirmasi</span>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0]">
                <div class="w-8 h-8 rounded-full bg-[#168A5B] text-white flex items-center justify-center text-xs font-bold mb-2">
                    ✓
                </div>
                <span class="text-xs font-bold text-[#0B4F38]">Kurir Ditugaskan</span>
                <span class="text-[10px] text-[#168A5B] font-medium">Budi Santoso</span>
            </div>

            <!-- Step 3 (Active) -->
            <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-gradient-to-b from-[#EEF9F2] to-white border-2 border-[#168A5B] shadow-sm animate-pulse">
                <div class="w-8 h-8 rounded-full bg-[#168A5B] text-white flex items-center justify-center text-xs font-bold mb-2">
                    🚚
                </div>
                <span class="text-xs font-extrabold text-[#168A5B]">Dalam Perjalanan</span>
                <span class="text-[10px] text-gray-500 font-medium">Sedang OTW</span>
            </div>

            <!-- Step 4 -->
            <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-gray-50 border border-gray-200 opacity-60">
                <div class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center text-xs font-bold mb-2">
                    4
                </div>
                <span class="text-xs font-semibold text-gray-600">Penimbangan</span>
                <span class="text-[10px] text-gray-400">Di Lokasi Anda</span>
            </div>

            <!-- Step 5 -->
            <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-gray-50 border border-gray-200 opacity-60">
                <div class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center text-xs font-bold mb-2">
                    5
                </div>
                <span class="text-xs font-semibold text-gray-600">Selesai</span>
                <span class="text-[10px] text-gray-400">Poin Masuk</span>
            </div>
        </div>
    </div>

    <!-- Map & Driver Profile (2-Column Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left 8 Cols: Interactive Route Map -->
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3 px-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#168A5B] animate-ping"></span>
                        <h4 class="text-xs font-bold text-[#1B211E]">Live GPS Fleet Tracking</h4>
                    </div>
                    <span class="text-xs text-[#66716B]">Kebayoran Baru, Jakarta Selatan</span>
                </div>
                <div id="pickupMap" class="w-full h-96 rounded-2xl overflow-hidden border border-gray-200 z-10"></div>
            </div>
        </div>

        <!-- Right 4 Cols: Driver Details & Order Summary Card -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Driver Card -->
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Identitas Mitra Kurir</h4>
                
                <div class="flex items-center gap-3.5">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80" alt="{{ $pickup->courier_name }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-200" />
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h5 class="text-base font-extrabold text-[#1B211E]">{{ $pickup->courier_name ?? 'Budi Santoso' }}</h5>
                            <span class="text-[10px] bg-emerald-100 text-[#168A5B] font-bold px-1.5 py-0.5 rounded">Verified</span>
                        </div>
                        <p class="text-xs text-amber-500 font-bold flex items-center gap-1 mt-0.5">
                            ★ 4.9 <span class="text-gray-400 font-normal">(1.420 penjemputan)</span>
                        </p>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $pickup->courier_plate ?? 'B 4219 SZR' }} • Motor Roda Tiga</p>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="https://wa.me/6287811223344" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-[#EEF9F2] hover:bg-[#DDF4E8] text-[#0B4F38] text-xs font-bold border border-[#BFE7D0] flex items-center justify-center gap-2 transition">
                        <i data-lucide="phone-call" class="w-4 h-4 text-[#168A5B]"></i> Hubungi Kurir via WhatsApp
                    </a>
                </div>
            </div>

            <!-- Order Specs Card -->
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Rincian Penjemputan</h4>
                
                <div class="space-y-2 text-xs divide-y divide-gray-100">
                    <div class="flex items-center justify-between pt-1">
                        <span class="text-gray-500">Material Sampah:</span>
                        <span class="font-bold text-[#1B211E]">{{ implode(', ', $pickup->categories ?? ['Plastik']) }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-gray-500">Estimasi Berat:</span>
                        <span class="font-bold text-[#1B211E]">{{ $pickup->estimated_weight }} kg</span>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-gray-500">Slot Waktu:</span>
                        <span class="font-bold text-[#1B211E]">{{ ucfirst($pickup->scheduled_slot) }} ({{ $pickup->scheduled_date->format('d M Y') }})</span>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-gray-500">Total Tarif:</span>
                        <span class="font-extrabold text-[#168A5B] tabular-nums">Rp {{ number_format($pickup->total_fee) }}</span>
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <span class="block text-[11px] text-gray-400 mb-1">Alamat Tujuan:</span>
                    <p class="text-xs text-gray-700 leading-relaxed font-medium">{{ $pickup->address }}</p>
                </div>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userLat = -6.2391;
        const userLng = 106.7928;
        const driverLat = -6.2312;
        const driverLng = 106.8105;

        const map = L.map('pickupMap').setView([-6.235, 106.801], 14);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors, © CARTO',
            maxZoom: 19
        }).addTo(map);

        // User Home Marker
        const userMarker = L.marker([userLat, userLng]).addTo(map);
        userMarker.bindPopup('<b>Lokasi Anda</b><br>{{ $pickup->address }}').openPopup();

        // Courier Driver Marker
        const driverMarker = L.marker([driverLat, driverLng]).addTo(map);
        driverMarker.bindPopup('<b>Armada Kurir (Budi Santoso)</b><br>Plat: {{ $pickup->courier_plate }}');

        // Route line
        const latlngs = [
            [driverLat, driverLng],
            [-6.2340, 106.8050],
            [-6.2370, 106.7980],
            [userLat, userLng]
        ];
        L.polyline(latlngs, {color: '#168A5B', weight: 4, dashArray: '5, 10'}).addTo(map);
    });
</script>
@endpush
@endsection
