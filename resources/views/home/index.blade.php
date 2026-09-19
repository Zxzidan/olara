@extends('layouts.app')

@section('title', 'Beranda — OLARA Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Top Greeting & Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1B211E] tracking-tight">
                    Halo, {{ $user ? explode(' ', $user->name)[0] : 'Pejuang Bumi' }}! 👋
                </h1>
                <span class="inline-flex items-center gap-1 bg-[#EEF9F2] text-[#168A5B] border border-[#BFE7D0] text-xs font-bold px-2.5 py-1 rounded-full">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i> {{ $user?->recycler_level ?? 'Starter' }}
                </span>
            </div>
            <p class="text-sm text-[#66716B] mt-1">Mari olah sampah hari ini untuk menjaga kelestarian bumi dan menambah pundi Eco-Points Anda.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="openOnboardingModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl border border-[#DDE3DF] bg-white text-[#66716B] hover:bg-gray-50 transition shadow-sm">
                <i data-lucide="compass" class="w-4 h-4 text-[#168A5B]"></i> Panduan Pilah
            </button>
            <a href="{{ route('scanner.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white shadow-sm transition">
                <i data-lucide="scan" class="w-4 h-4"></i> Scan Sampah Sekarang
            </a>
        </div>
    </div>

    <!-- Hero Card: Points Balance & Recycler Level Progress (PRD Section B) -->
    <div class="rounded-3xl gradient-card text-white p-6 sm:p-8 shadow-xl shadow-emerald-950/10 relative overflow-hidden">
        <!-- Background subtle watermark leaf -->
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
            <i data-lucide="leaf" class="w-64 h-64 text-white"></i>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center relative z-10">
            <!-- Left 7 cols: Points & Financial Worth -->
            <div class="lg:col-span-7 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-200 bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                        Saldo Dompet Eco-Point Aktif
                    </span>
                    @if($user && $user->membership_tier === 'premium')
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-300 bg-amber-400/20 px-2.5 py-1 rounded-full border border-amber-300/30 flex items-center gap-1">
                            <i data-lucide="zap" class="w-3 h-3"></i> 1.2x Multiplier Aktif
                        </span>
                    @endif
                </div>

                <div class="flex flex-wrap items-baseline gap-3">
                    <h2 class="text-4xl sm:text-5xl font-extrabold tracking-tight tabular-nums">
                        {{ number_format($user?->eco_points ?? 0) }}
                    </h2>
                    <span class="text-xl font-bold text-emerald-200">Eco-Points</span>
                    <span class="text-xs font-medium text-emerald-100/80 bg-black/20 px-2.5 py-1 rounded-lg">
                        ≈ Rp {{ number_format(($user?->eco_points ?? 0) * 100) }} nilai tukar
                    </span>
                </div>

                <!-- Recycler Level Progression Bar -->
                <div class="space-y-1.5 pt-2 max-w-xl">
                    <div class="flex items-center justify-between text-xs font-medium text-emerald-100">
                        <span>Level Saat Ini: <strong class="text-white">{{ $tierName }}</strong></span>
                        <span>Target: <strong class="text-white">{{ $nextTierName }}</strong> ({{ $pointsRemaining }} poin lagi)</span>
                    </div>
                    <div class="w-full h-3 bg-black/30 rounded-full overflow-hidden p-0.5 backdrop-blur-sm">
                        <div class="h-full bg-gradient-to-r from-emerald-300 to-mint-400 rounded-full transition-all duration-500 shadow-sm" style="width: {{ $tierProgressPercent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Right 5 cols: Fast CTA Actions inside Card -->
            <div class="lg:col-span-5 flex flex-col sm:flex-row lg:flex-col gap-3 justify-center">
                <a href="{{ route('rewards.index') }}" class="flex items-center justify-between bg-white text-[#0B4F38] hover:bg-emerald-50 px-5 py-3.5 rounded-2xl font-bold text-sm transition shadow-sm group">
                    <span class="flex items-center gap-2.5">
                        <i data-lucide="gift" class="w-5 h-5 text-[#168A5B]"></i> Tukar Saldo & Voucher
                    </span>
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('pickup.index') }}" class="flex items-center justify-between bg-white/15 hover:bg-white/20 border border-white/20 text-white px-5 py-3.5 rounded-2xl font-semibold text-sm transition backdrop-blur-sm group">
                    <span class="flex items-center gap-2.5">
                        <i data-lucide="truck" class="w-5 h-5 text-emerald-200"></i> Jadwalkan Jemput Sampah
                    </span>
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Pickup Tracking Banner (PRD Section D - On-Demand Pickup) -->
    @if($activePickup)
        <div class="bg-white border border-[#BFE7D0] rounded-3xl p-5 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#EEF9F2] text-[#168A5B] flex items-center justify-center shrink-0">
                        <i data-lucide="truck" class="w-6 h-6 animate-pulse"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2 py-0.5 rounded">Penjemputan Aktif</span>
                            <span class="text-xs text-gray-500 font-mono">{{ $activePickup->pickup_code }}</span>
                        </div>
                        <h4 class="text-base font-bold text-[#1B211E] mt-0.5">Kurir Armada Sedang Ditugaskan</h4>
                        <p class="text-xs text-[#66716B]">Kurir: <strong>{{ $activePickup->courier_name }}</strong> ({{ $activePickup->courier_plate }}) • Estimasi penjemputan: {{ $activePickup->scheduled_slot === 'pagi' ? 'Pagi (08:00 - 11:00)' : 'Siang (13:00 - 16:00)' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('pickup.show', $activePickup->pickup_code) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-xs font-bold shadow-sm transition">
                        <i data-lucide="map-pin" class="w-4 h-4"></i> Lacak Status & Peta Kurir
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- 5 Core Quick Actions Grid (PRD Section B) -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-[#1B211E]">Aksi Cepat Pengelolaan</h3>
            <span class="text-xs text-[#66716B]">Pilih layanan daur ulang yang Anda butuhkan</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
            <!-- 1. AI Scanner -->
            <a href="{{ route('scanner.index') }}" class="bg-white hover:bg-[#EEF9F2] border border-[#DDE3DF] hover:border-[#BFE7D0] p-4 rounded-2xl transition group flex flex-col items-center text-center shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
                <div class="w-12 h-12 rounded-2xl bg-[#EEF9F2] group-hover:bg-[#DDF4E8] text-[#168A5B] flex items-center justify-center mb-3 transition">
                    <i data-lucide="scan-line" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-[#1B211E] group-hover:text-[#168A5B] transition">Kamera AI</h4>
                <p class="text-[11px] text-[#66716B] mt-0.5">Deteksi & Valuasi</p>
            </a>

            <!-- 2. Pickup -->
            <a href="{{ route('pickup.index') }}" class="bg-white hover:bg-[#EEF9F2] border border-[#DDE3DF] hover:border-[#BFE7D0] p-4 rounded-2xl transition group flex flex-col items-center text-center shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
                <div class="w-12 h-12 rounded-2xl bg-[#EEF9F2] group-hover:bg-[#DDF4E8] text-[#168A5B] flex items-center justify-center mb-3 transition">
                    <i data-lucide="truck" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-[#1B211E] group-hover:text-[#168A5B] transition">Jemput Sampah</h4>
                <p class="text-[11px] text-[#66716B] mt-0.5">Armada On-Demand</p>
            </a>

            <!-- 3. Drop-off -->
            <a href="{{ route('dropoff.index') }}" class="bg-white hover:bg-[#EEF9F2] border border-[#DDE3DF] hover:border-[#BFE7D0] p-4 rounded-2xl transition group flex flex-col items-center text-center shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
                <div class="w-12 h-12 rounded-2xl bg-[#EEF9F2] group-hover:bg-[#DDF4E8] text-[#168A5B] flex items-center justify-center mb-3 transition">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-[#1B211E] group-hover:text-[#168A5B] transition">Antar ke Mitra</h4>
                <p class="text-[11px] text-[#66716B] mt-0.5">Peta Bank Sampah</p>
            </a>

            <!-- 4. Rewards -->
            <a href="{{ route('rewards.index') }}" class="bg-white hover:bg-[#EEF9F2] border border-[#DDE3DF] hover:border-[#BFE7D0] p-4 rounded-2xl transition group flex flex-col items-center text-center shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
                <div class="w-12 h-12 rounded-2xl bg-[#EEF9F2] group-hover:bg-[#DDF4E8] text-[#168A5B] flex items-center justify-center mb-3 transition">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-[#1B211E] group-hover:text-[#168A5B] transition">Tukar Poin</h4>
                <p class="text-[11px] text-[#66716B] mt-0.5">Voucher & E-Wallet</p>
            </a>

            <!-- 5. Marketplace -->
            <a href="{{ route('marketplace.index') }}" class="bg-white hover:bg-[#EEF9F2] border border-[#DDE3DF] hover:border-[#BFE7D0] p-4 rounded-2xl transition group flex flex-col items-center text-center shadow-[0_1px_3px_rgba(0,0,0,0.02)] col-span-2 sm:col-span-1">
                <div class="w-12 h-12 rounded-2xl bg-[#EEF9F2] group-hover:bg-[#DDF4E8] text-[#168A5B] flex items-center justify-center mb-3 transition">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-[#1B211E] group-hover:text-[#168A5B] transition">Marketplace</h4>
                <p class="text-[11px] text-[#66716B] mt-0.5">Bahan Daur Ulang</p>
            </a>
        </div>
    </div>

    <!-- Main Content 2-Column: Left (Recent Activities Table) & Right (Eco Impact KPI + Membership Banner) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Recent Activity Feed (PRD Section B) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-[#1B211E]">Riwayat Aktivitas & Poin Terbaru</h3>
                        <p class="text-xs text-[#66716B]">Transparansi pencatatan setoran, penjemputan, dan transaksi reward</p>
                    </div>
                    <a href="{{ route('rewards.index') }}" class="text-xs font-semibold text-[#168A5B] hover:text-[#0F6B47] flex items-center gap-1">
                        Lihat Semua <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                @if($recentTransactions->isNotEmpty())
                    <div class="divide-y divide-gray-100">
                        @foreach($recentTransactions as $tx)
                            <div class="py-3.5 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $tx->amount > 0 ? 'bg-[#EEF9F2] text-[#168A5B]' : 'bg-rose-50 text-rose-600' }}">
                                        @if($tx->type === 'registration_bonus')
                                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                                        @elseif($tx->type === 'waste_deposit')
                                            <i data-lucide="recycle" class="w-4 h-4"></i>
                                        @elseif($tx->type === 'pickup_reward')
                                            <i data-lucide="truck" class="w-4 h-4"></i>
                                        @elseif($tx->type === 'reward_redeem')
                                            <i data-lucide="gift" class="w-4 h-4"></i>
                                        @elseif($tx->type === 'scan_bonus')
                                            <i data-lucide="scale" class="w-4 h-4"></i>
                                        @else
                                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-semibold text-[#1B211E]">{{ $tx->description }}</h5>
                                        <p class="text-xs text-gray-400 font-medium">{{ $tx->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="inline-flex items-center font-bold text-sm tabular-nums {{ $tx->amount > 0 ? 'text-[#168A5B]' : 'text-rose-600' }}">
                                        {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount) }} Pts
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="inbox" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-xs text-gray-500">Belum ada aktivitas tercatat. Mulai dengan memindai sampah pertama Anda!</p>
                    </div>
                @endif
            </div>

            <!-- Past Scans Summary -->
            @if($recentAnalysis->isNotEmpty())
                <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                        <h4 class="text-sm font-bold text-[#1B211E]">Hasil Pemindaian AI Terakhir</h4>
                        <a href="{{ route('scanner.index') }}" class="text-xs font-semibold text-[#168A5B]">Buka Scanner</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach($recentAnalysis as $scan)
                            <div class="p-3.5 rounded-2xl border border-[#DDE3DF] bg-[#F7F8F6] space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2 py-0.5 rounded">{{ $scan->category }}</span>
                                    <span class="text-[11px] text-gray-500 font-medium">{{ $scan->confidence_rate }}% AI Match</span>
                                </div>
                                <h5 class="text-xs font-bold text-[#1B211E] line-clamp-1">{{ $scan->material_name }}</h5>
                                <div class="text-[11px] text-[#66716B] flex items-center justify-between pt-1 border-t border-gray-200">
                                    <span>{{ $scan->weight_kg }} kg</span>
                                    <span class="font-bold text-[#0B4F38]">Rp {{ number_format($scan->total_estimated_value) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Eco Impact Metrics & Membership Plan Banner -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Impact Card (PRD Section G) -->
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-[#1B211E]">Dampak Ekologis Anda</h3>
                    <a href="{{ route('analytics.index') }}" class="text-xs font-semibold text-[#168A5B]">Detail Grafik</a>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    <div class="p-3.5 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0] flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#168A5B] text-white flex items-center justify-center shrink-0 font-bold">
                            📦
                        </div>
                        <div>
                            <span class="text-xs text-[#66716B] font-medium">Total Sampah Terpilah</span>
                            <p class="text-lg font-extrabold text-[#0B4F38] tabular-nums">{{ $totalWasteManaged }} kg</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0] flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#168A5B] text-white flex items-center justify-center shrink-0 font-bold">
                            🌱
                        </div>
                        <div>
                            <span class="text-xs text-[#66716B] font-medium">Emisi Karbon Dicegah</span>
                            <p class="text-lg font-extrabold text-[#0B4F38] tabular-nums">{{ $totalCo2Avoided }} kg CO₂e</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0] flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#168A5B] text-white flex items-center justify-center shrink-0 font-bold">
                            🌳
                        </div>
                        <div>
                            <span class="text-xs text-[#66716B] font-medium">Setara Pohon Diselamatkan</span>
                            <p class="text-lg font-extrabold text-[#0B4F38] tabular-nums">{{ $treesEquivalent }} Pohon Dewasa</p>
                        </div>
                    </div>
                </div>

                <p class="text-[11px] text-[#66716B] text-center leading-relaxed">
                    Setiap kilogram yang Anda pilah mencegah penumpukan sampah liar dan mengurangi jejak karbon industri.
                </p>
            </div>

            <!-- Single Elegant Membership Banner (PRD Section I & Layout Principle: only 1 relevant banner) -->
            <div class="rounded-3xl border border-[#BFE7D0] bg-gradient-to-br from-[#EEF9F2] to-white p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full flex items-center gap-1">
                        <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-500"></i> Olara Membership
                    </span>
                </div>

                <div>
                    <h4 class="text-base font-extrabold text-[#0B4F38]">
                        @if($user && $user->membership_tier === 'premium')
                            Anda adalah Member Premium
                        @else
                            Tingkatkan ke Olara Premium
                        @endif
                    </h4>
                    <p class="text-xs text-[#66716B] mt-1 leading-relaxed">
                        @if($user && $user->membership_tier === 'premium')
                            Nikmati kuota 5x penjemputan gratis, unlimited scan AI, dan multiplier 1.2x poin di setiap setoran.
                        @else
                            Dapatkan 5x kuota penjemputan gratis per bulan, kamera AI tanpa batas, dan poin multiplier 1.2x.
                        @endif
                    </p>
                </div>

                <a href="{{ route('membership.index') }}" class="block w-full text-center py-2.5 px-4 rounded-xl font-bold text-xs {{ $user && $user->membership_tier === 'premium' ? 'bg-white border border-[#BFE7D0] text-[#168A5B] hover:bg-[#EEF9F2]' : 'bg-[#168A5B] hover:bg-[#0F6B47] text-white shadow-sm' }} transition">
                    {{ $user && $user->membership_tier === 'premium' ? 'Kelola Langganan Premium' : 'Pelajari Benefit Premium (Rp 39rb/bln)' }}
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
