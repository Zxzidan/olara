@extends('layouts.app')

@section('title', 'Lacak Penjemputan ' . $pickup->pickup_code . ' — OLARA')

@section('content')
@php
    $isPaid = ($pickup->payment_status === 'paid' || (float) $pickup->total_fee == 0);
    $displayPoints = $pickup->points_earned > 0 ? $pickup->points_earned : \App\Models\PickupRequest::calculatePoints((float) $pickup->estimated_weight);
@endphp

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

    <!-- Stepper Status Tracker -->
    <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            @if($isPaid)
                <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#168A5B] animate-pulse"></span>
                    Status Pelacakan Armada
                </span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#1B211E] mt-2">
                    Kurir Menuju Lokasi Penjemputan
                </h2>
                <p class="text-xs text-[#66716B] mt-1">
                    Estimasi tiba dalam waktu <strong>15–25 menit</strong>. Pastikan sampah telah disiapkan di area yang mudah diakses.
                </p>
            @else
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full inline-flex items-center gap-1.5">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                    Menunggu Pembayaran
                </span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#1B211E] mt-2">
                    Pesanan Dibuat — Menunggu Pembayaran
                </h2>
                <p class="text-xs text-[#66716B] mt-1">
                    Pesanan penjemputan berhasil dibuat. Silakan selesaikan pembayaran biaya armada sebesar <strong class="text-[#168A5B]">Rp {{ number_format($pickup->total_fee) }}</strong> agar kurir mitra dapat segera ditugaskan menuju lokasi Anda.
                </p>
            @endif
        </div>

        <!-- 5-Step Stepper -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 relative">
            <!-- Step 1: Pesanan Dibuat -->
            @if($isPaid)
                <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0]">
                    <div class="w-8 h-8 rounded-full bg-[#168A5B] text-white flex items-center justify-center text-xs font-bold mb-2">
                        ✓
                    </div>
                    <span class="text-xs font-bold text-[#0B4F38]">Pesanan Dibuat</span>
                    <span class="text-[10px] text-[#168A5B] font-medium">Terkonfirmasi</span>
                </div>
            @else
                <!-- Active step when unpaid -->
                <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-[#EEF9F2] border-2 border-[#168A5B] shadow-sm">
                    <div class="w-8 h-8 rounded-full bg-[#168A5B] text-white flex items-center justify-center text-xs font-bold mb-2">
                        ✓
                    </div>
                    <span class="text-xs font-extrabold text-[#0B4F38]">Pesanan Dibuat</span>
                    <span class="text-[10px] text-[#168A5B] font-bold">Terkonfirmasi</span>
                </div>
            @endif

            <!-- Step 2: Kurir Ditugaskan -->
            @if($isPaid)
                <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0]">
                    <div class="w-8 h-8 rounded-full bg-[#168A5B] text-white flex items-center justify-center text-xs font-bold mb-2">
                        ✓
                    </div>
                    <span class="text-xs font-bold text-[#0B4F38]">Kurir Ditugaskan</span>
                    <span class="text-[10px] text-[#168A5B] font-medium">{{ $pickup->courier_name ?? 'Dedi Kurniawan' }}</span>
                </div>
            @else
                <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-gray-50 border border-gray-200 opacity-60">
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center text-xs font-bold mb-2">
                        2
                    </div>
                    <span class="text-xs font-semibold text-gray-600">Kurir Ditugaskan</span>
                    <span class="text-[10px] text-gray-400">Menunggu Bayar</span>
                </div>
            @endif

            <!-- Step 3: Dalam Perjalanan -->
            @if($isPaid)
                <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-gradient-to-b from-[#EEF9F2] to-white border-2 border-[#168A5B] shadow-sm animate-pulse">
                    <div class="w-8 h-8 rounded-full bg-[#168A5B] text-white flex items-center justify-center text-xs font-bold mb-2">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs font-extrabold text-[#168A5B]">Dalam Perjalanan</span>
                    <span class="text-[10px] text-gray-500 font-medium">Sedang OTW</span>
                </div>
            @else
                <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-gray-50 border border-gray-200 opacity-60">
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center text-xs font-bold mb-2">
                        3
                    </div>
                    <span class="text-xs font-semibold text-gray-600">Dalam Perjalanan</span>
                    <span class="text-[10px] text-gray-400">Sedang OTW</span>
                </div>
            @endif

            <!-- Step 4: Penimbangan -->
            <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-gray-50 border border-gray-200 opacity-60">
                <div class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center text-xs font-bold mb-2">
                    4
                </div>
                <span class="text-xs font-semibold text-gray-600">Penimbangan</span>
                <span class="text-[10px] text-gray-400">Di Lokasi Anda</span>
            </div>

            <!-- Step 5: Selesai -->
            <div class="flex flex-col items-center text-center p-3 rounded-2xl bg-gray-50 border border-gray-200 opacity-60">
                <div class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center text-xs font-bold mb-2">
                    5
                </div>
                <span class="text-xs font-semibold text-gray-600">Selesai</span>
                <span class="text-[10px] text-gray-400">Poin Masuk (+{{ $displayPoints }} Pts)</span>
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
                        @if($isPaid)
                            <span class="w-2.5 h-2.5 rounded-full bg-[#168A5B] animate-ping"></span>
                            <h4 class="text-xs font-bold text-[#1B211E]">Live GPS Fleet Tracking</h4>
                        @else
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <h4 class="text-xs font-bold text-[#1B211E]">Lokasi Titik Penjemputan</h4>
                        @endif
                    </div>
                    <span class="text-xs text-[#66716B]">Kebayoran Baru, Jakarta Selatan</span>
                </div>
                <div id="pickupMap" class="w-full h-96 rounded-2xl overflow-hidden border border-gray-200 z-10"></div>
                @if(!$isPaid)
                    <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-2 text-xs text-amber-800">
                        <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0"></i>
                        <span>Pelacakan rute armada kurir secara langsung (live GPS) akan aktif seketika setelah pembayaran diverifikasi.</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right 4 Cols: Driver Details & Order Summary Card -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Driver Card -->
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Identitas Mitra Kurir</h4>
                
                @if($isPaid)
                    <div class="flex items-center gap-3.5">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80" alt="{{ $pickup->courier_name }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-200" />
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h5 class="text-base font-extrabold text-[#1B211E]">{{ $pickup->courier_name ?? 'Dedi Kurniawan' }}</h5>
                                <span class="text-[10px] bg-emerald-100 text-[#168A5B] font-bold px-1.5 py-0.5 rounded">Verified</span>
                            </div>
                            <p class="text-xs text-amber-500 font-bold flex items-center gap-1 mt-0.5">
                                <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i> 4.9 <span class="text-gray-400 font-normal">(1.420 penjemputan)</span>
                            </p>
                            <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $pickup->courier_plate ?? 'B 5542 PQM' }} • Motor Roda Tiga</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pickup->courier_phone ?? '6285644332211') }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-[#EEF9F2] hover:bg-[#DDF4E8] text-[#0B4F38] text-xs font-bold border border-[#BFE7D0] flex items-center justify-center gap-2 transition">
                            <i data-lucide="phone-call" class="w-4 h-4 text-[#168A5B]"></i> Hubungi Kurir via WhatsApp
                        </a>
                    </div>
                @else
                    <div class="flex items-center gap-3.5">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 shrink-0">
                            <i data-lucide="truck" class="w-6 h-6 text-amber-500"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h5 class="text-sm font-bold text-[#1B211E]">Menunggu Penugasan</h5>
                                <span class="text-[10px] bg-amber-100 text-amber-700 font-bold px-1.5 py-0.5 rounded">Pending</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 leading-snug">Kurir mitra terdekat akan ditugaskan segera setelah pembayaran biaya armada diverifikasi.</p>
                        </div>
                    </div>
                @endif
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
                        <span class="text-gray-500">Reward Eco-Points:</span>
                        <span class="font-extrabold text-[#168A5B] flex items-center gap-1">
                            <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                            +{{ $displayPoints }} Poin
                            @if($isPaid || $pickup->points_awarded)
                                <span class="text-[9px] font-bold bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded-full">Sudah Masuk</span>
                            @else
                                <span class="text-[9px] font-medium bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded-full">Masuk Saat Lunas</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-gray-500">Slot Waktu:</span>
                        <span class="font-bold text-[#1B211E]">{{ ucfirst($pickup->scheduled_slot) }} ({{ $pickup->scheduled_date->format('d M Y') }})</span>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-gray-500">Total Tarif:</span>
                        <span class="font-extrabold text-[#168A5B] tabular-nums">Rp {{ number_format($pickup->total_fee) }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-gray-500">Status Bayar:</span>
                        @if($isPaid)
                            <span class="font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full text-[10px] flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> {{ $pickup->total_fee == 0 ? 'Gratis (Kuota)' : 'Lunas (Midtrans)' }}
                            </span>
                        @else
                            <span class="font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full text-[10px] flex items-center gap-1 animate-pulse">
                                <i data-lucide="clock" class="w-3 h-3"></i> Menunggu Bayar
                            </span>
                        @endif
                    </div>
                </div>

                @if(!$isPaid)
                    <div class="pt-2">
                        <button type="button" id="payButton" onclick="payPickupSnap()" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-bold shadow-md transition flex items-center justify-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4"></i> Bayar Biaya Armada (Midtrans)
                        </button>
                    </div>
                @endif

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
        const isPaid = {{ $isPaid ? 'true' : 'false' }};
        const userLat = -6.2391;
        const userLng = 106.7928;
        const driverLat = -6.2312;
        const driverLng = 106.8105;

        const centerLat = isPaid ? -6.235 : userLat;
        const centerLng = isPaid ? 106.801 : userLng;
        const map = L.map('pickupMap').setView([centerLat, centerLng], isPaid ? 14 : 15);

        // Standard OpenStreetMap tiles (free, no API key watermark)
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // User Home Marker
        const userMarker = L.marker([userLat, userLng]).addTo(map);
        userMarker.bindPopup('<b>Lokasi Anda</b><br>{{ addslashes($pickup->address) }}').openPopup();

        if (isPaid) {
            // Courier Driver Marker
            const driverMarker = L.marker([driverLat, driverLng]).addTo(map);
            driverMarker.bindPopup('<b>Armada Kurir ({{ addslashes($pickup->courier_name ?? "Dedi Kurniawan") }})</b><br>Plat: {{ addslashes($pickup->courier_plate ?? "B 5542 PQM") }}');

            // Route line
            const latlngs = [
                [driverLat, driverLng],
                [-6.2340, 106.8050],
                [-6.2370, 106.7980],
                [userLat, userLng]
            ];
            L.polyline(latlngs, {color: '#168A5B', weight: 4, dashArray: '5, 10'}).addTo(map);
        }
    });

    async function payPickupSnap() {
        if (!window.snap) {
            alert('Midtrans SDK belum siap. Silakan muat ulang halaman.');
            return;
        }

        const payBtn = document.getElementById('payButton');
        const originalHtml = payBtn ? payBtn.innerHTML : '';
        if (payBtn) {
            payBtn.disabled = true;
            payBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyiapkan Pembayaran...';
        }

        let token = "{{ $pickup->snap_token ?? '' }}";
        if (!token) {
            try {
                const res = await fetch("{{ route('pickup.snapToken', $pickup->pickup_code) }}");
                const data = await res.json();
                if (data.success && data.snap_token) {
                    token = data.snap_token;
                } else {
                    alert('Gagal menyiapkan token pembayaran: ' + (data.message || 'Token tidak valid.'));
                    if (payBtn) { payBtn.disabled = false; payBtn.innerHTML = originalHtml; }
                    return;
                }
            } catch (err) {
                alert('Gagal menghubungi server pembayaran.');
                if (payBtn) { payBtn.disabled = false; payBtn.innerHTML = originalHtml; }
                return;
            }
        }

        if (payBtn) { payBtn.disabled = false; payBtn.innerHTML = originalHtml; }

        window.snap.pay(token, {
            onSuccess: function(result) {
                fetch("{{ route('pickup.markPaid', $pickup->pickup_code) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        transaction_id: result.transaction_id || '',
                        payment_type: result.payment_type || 'midtrans',
                    })
                }).then(res => res.json()).then(data => {
                    if (data && data.points_earned) {
                        alert(`Pembayaran armada penjemputan berhasil! +${data.points_earned} Eco-Points telah ditambahkan ke saldo dompet akun Anda.`);
                    }
                }).finally(() => {
                    window.location.reload();
                });
            },
            onPending: function() { window.location.reload(); },
            onError: function() { alert('Pembayaran armada gagal atau dibatalkan.'); },
            onClose: function() {}
        });
    }
</script>
@endpush
@endsection
