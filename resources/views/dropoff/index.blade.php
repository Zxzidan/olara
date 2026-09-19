@extends('layouts.app')

@section('title', 'Peta Mitra Bank Sampah (Drop-Off) — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full">
                Setor Mandiri (Drop-off)
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1B211E] tracking-tight mt-1">
                Peta Bank Sampah & Mitra Terdekat
            </h1>
            <p class="text-sm text-[#66716B] mt-1">
                Antar sampah daur ulang Anda langsung ke lokasi mitra resmi untuk mendapatkan insentif <strong>Bonus Eco-Points ekstra</strong>.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-[#0B4F38] bg-[#EEF9F2] border border-[#BFE7D0] px-3.5 py-2 rounded-2xl flex items-center gap-2 shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                <span>Mitra Khusus Resmi: <strong>+25 Pts Ekstra</strong></span>
            </span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <form action="{{ route('dropoff.index') }}" method="GET" class="bg-white border border-[#DDE3DF] rounded-2xl p-4 shadow-sm flex flex-col md:flex-row items-center gap-3">
        <div class="relative flex-1 w-full">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-3"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama bank sampah, TPS3R, kota, atau jalan..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-[#DDE3DF] text-xs focus:ring-2 focus:ring-[#168A5B] focus:outline-none" />
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto flex-wrap">
            <label class="cursor-pointer text-xs font-medium flex items-center gap-1.5 px-3 py-2 rounded-xl border border-[#DDE3DF] hover:bg-gray-50 transition {{ request('open_only') ? 'bg-[#EEF9F2] border-[#168A5B] text-[#168A5B] font-bold' : 'text-gray-700' }}">
                <input type="checkbox" name="open_only" value="1" {{ request('open_only') ? 'checked' : '' }} class="hidden" onchange="this.form.submit()" />
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Buka Sekarang
            </label>

            <label class="cursor-pointer text-xs font-medium flex items-center gap-1.5 px-3 py-2 rounded-xl border border-[#DDE3DF] hover:bg-gray-50 transition {{ request('premium_only') ? 'bg-amber-50 border-amber-300 text-amber-900 font-bold' : 'text-gray-700' }}">
                <input type="checkbox" name="premium_only" value="1" {{ request('premium_only') ? 'checked' : '' }} class="hidden" onchange="this.form.submit()" />
                <i data-lucide="award" class="w-3.5 h-3.5 text-amber-600"></i> Mitra Khusus (+25 Pts)
            </label>

            <button type="submit" class="py-2 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-xs font-bold transition shadow-sm">
                Terapkan Filter
            </button>

            @if(request()->hasAny(['search', 'open_only', 'premium_only']))
                <a href="{{ route('dropoff.index') }}" class="text-xs font-semibold text-gray-400 hover:text-gray-600 px-2 py-2">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Split-Screen View: Map (Left/Top) & Directory List (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left 7 Cols: Interactive Map Canvas -->
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3 px-2">
                    <div class="flex items-center gap-2">
                        <i data-lucide="map" class="w-4 h-4 text-[#168A5B]"></i>
                        <h3 class="text-xs font-bold text-[#1B211E]">Peta Lokasi Mitra Aktif</h3>
                    </div>
                    <span class="text-xs text-[#66716B]">{{ $partners->count() }} Mitra Ditemukan</span>
                </div>

                <div id="depotMap" class="w-full h-[520px] rounded-2xl overflow-hidden border border-gray-200 z-10"></div>
            </div>
        </div>

        <!-- Right 5 Cols: Directory Cards List -->
        <div class="lg:col-span-5 space-y-4 max-h-[580px] overflow-y-auto pr-1">
            @forelse($partners as $partner)
                <div class="bg-white border border-[#DDE3DF] hover:border-[#168A5B] rounded-2xl p-5 transition shadow-sm space-y-3" id="partner-card-{{ $partner->id }}">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            @if($partner->is_premium_partner)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-amber-800 bg-amber-100 border border-amber-300 px-2 py-0.5 rounded-full mb-1">
                                    <i data-lucide="award" class="w-3 h-3 text-amber-600"></i> Mitra Resmi OLARA (+{{ $partner->bonus_points }} Poin)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full mb-1">
                                    Mitra Terdaftar (+{{ $partner->bonus_points }} Poin)
                                </span>
                            @endif
                            <h4 class="text-sm font-extrabold text-[#1B211E]">{{ $partner->name }}</h4>
                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $partner->address }}</p>
                        </div>

                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md shrink-0 {{ $partner->is_open ? 'bg-[#EEF9F2] text-[#168A5B]' : 'bg-red-50 text-red-600' }}">
                            {{ $partner->is_open ? '● Buka' : 'Tutup' }}
                        </span>
                    </div>

                    <div class="text-[11px] text-gray-600 flex items-center justify-between pt-2 border-t border-gray-100">
                        <span class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-400"></i> {{ $partner->operating_hours }}
                        </span>
                        <span class="flex items-center gap-1 font-semibold text-gray-500">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i> {{ $partner->phone }}
                        </span>
                    </div>

                    <!-- Accepted Materials Chips -->
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-1.5">Material Diterima:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($partner->accepted_materials ?? [] as $mat)
                                <span class="text-[10px] font-medium bg-[#F7F8F6] text-gray-700 px-2 py-0.5 rounded-md border border-gray-200">
                                    {{ $mat }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Direct Google Maps Link -->
                    <div class="pt-2 flex items-center gap-2">
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $partner->latitude }},{{ $partner->longitude }}" target="_blank" class="flex-1 py-2 px-3 rounded-xl bg-[#EEF9F2] hover:bg-[#DDF4E8] text-[#0B4F38] text-xs font-bold border border-[#BFE7D0] flex items-center justify-center gap-1.5 transition">
                            <i data-lucide="navigation-2" class="w-3.5 h-3.5 text-[#168A5B]"></i> Buka Navigasi Rute
                        </a>
                        <button type="button" onclick="focusOnMap({{ $partner->latitude }}, {{ $partner->longitude }}, '{{ $partner->name }}')" class="py-2 px-3 rounded-xl border border-[#DDE3DF] hover:bg-gray-50 text-gray-700 text-xs font-semibold transition">
                            Lihat di Peta
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-[#DDE3DF] rounded-2xl p-8 text-center">
                    <i data-lucide="map-pin-off" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                    <p class="text-xs text-gray-500 font-medium">Tidak ada mitra yang sesuai dengan pencarian Anda.</p>
                </div>
            @endforelse
        </div>

    </div>

</div>

@push('scripts')
<script>
    let map;
    const partnersData = @json($partners);

    document.addEventListener('DOMContentLoaded', function() {
        // Center around Jakarta
        map = L.map('depotMap').setView([-6.225, 106.820], 12);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors, © CARTO',
            maxZoom: 19
        }).addTo(map);

        partnersData.forEach(p => {
            const isOfficial = p.is_premium_partner;
            const marker = L.marker([p.latitude, p.longitude]).addTo(map);

            const popupContent = `
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; max-width: 200px;">
                    <b style="color: #0B4F38; font-size: 13px;">${p.name}</b>
                    ${isOfficial ? '<span style="display:inline-block; font-size:9px; background:#FEF3C7; color:#92400E; padding:1px 5px; border-radius:4px; font-weight:bold; margin-top:2px;">MITRA RESMI (+25 PTS)</span>' : ''}
                    <p style="color: #66716B; margin: 4px 0;">${p.address}</p>
                    <p style="font-size:11px; color:#168A5B; font-weight:bold; margin-bottom:6px;">Jam: ${p.operating_hours}</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${p.latitude},${p.longitude}" target="_blank" style="display:block; text-align:center; background:#168A5B; color:white; padding:4px 8px; border-radius:6px; text-decoration:none; font-weight:bold; font-size:11px;">Rute Google Maps</a>
                </div>
            `;
            marker.bindPopup(popupContent);
        });
    });

    function focusOnMap(lat, lng, name) {
        if (map) {
            map.flyTo([lat, lng], 15, { duration: 1 });
        }
    }
</script>
@endpush
@endsection
