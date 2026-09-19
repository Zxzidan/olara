@extends('layouts.app')

@section('title', 'Dashboard Utama — OLARA')

@section('content')
<div class="space-y-4">

    <!-- Top Greeting & Action Cluster -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Halo, {{ $user ? explode(' ', $user->name)[0] : 'Pejuang Bumi' }}! 👋
                </h1>
                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/80 dark:text-emerald-300 text-xs font-bold px-2.5 py-0.5 rounded-full">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i> {{ $tierName }}
                </span>
                @if($user && $user->membership_tier === 'premium')
                    <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 dark:bg-amber-900/80 dark:text-amber-200 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-500"></i> Premium 1.2x
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kelola sampah daur ulang Anda hari ini, jaga bumi, dan tingkatkan saldo Eco-Points.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="openOnboardingModal()" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                <i data-lucide="compass" class="w-4 h-4 text-primary-600 dark:text-primary-400"></i> Panduan
            </button>
            <a href="{{ route('scanner.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-lg bg-primary-600 hover:bg-primary-700 text-white shadow-sm transition">
                <i data-lucide="scan-line" class="w-4 h-4"></i> Scan Kamera AI
            </a>
        </div>
    </div>

    <!-- 4 Key Stat Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1: Eco-Points Balance -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Saldo Eco-Points</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mt-1 tabular-nums">
                        {{ number_format($user?->eco_points ?? 0) }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="coins" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs pt-2 border-t border-gray-100 dark:border-gray-700">
                <span class="text-gray-500 dark:text-gray-400">≈ Rp {{ number_format(($user?->eco_points ?? 0) * 100) }} e-wallet</span>
                <a href="{{ route('rewards.index') }}" class="text-emerald-600 dark:text-emerald-400 font-bold hover:underline inline-flex items-center gap-0.5">
                    Tukar <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        <!-- Metric 2: Waste Managed -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sampah Terkelola</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mt-1 tabular-nums">
                        {{ number_format($totalWasteManaged, 1) }} <span class="text-base font-medium text-gray-500 dark:text-gray-400">kg</span>
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <i data-lucide="recycle" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs pt-2 border-t border-gray-100 dark:border-gray-700">
                <span class="text-emerald-600 dark:text-emerald-400 font-medium">Bebas dari TPA</span>
                <a href="{{ route('pickup.index') }}" class="text-primary-600 dark:text-primary-400 font-bold hover:underline inline-flex items-center gap-0.5">
                    Jemput <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        <!-- Metric 3: CO2 Avoided -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">CO₂ Terhindar</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mt-1 tabular-nums">
                        {{ number_format($totalCo2Avoided, 1) }} <span class="text-base font-medium text-gray-500 dark:text-gray-400">kg</span>
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-teal-100 dark:bg-teal-950 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                    <i data-lucide="cloud-off" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs pt-2 border-t border-gray-100 dark:border-gray-700">
                <span class="text-gray-500 dark:text-gray-400">Setara jejak karbon</span>
                <a href="{{ route('analytics.index') }}" class="text-teal-600 dark:text-teal-400 font-bold hover:underline inline-flex items-center gap-0.5">
                    Analisis <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        <!-- Metric 4: Trees Equivalent -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pohon Diselamatkan</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mt-1 tabular-nums">
                        {{ number_format($treesEquivalent, 1) }} <span class="text-base font-medium text-gray-500 dark:text-gray-400">Pohon</span>
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-green-100 dark:bg-green-950 text-green-600 dark:text-green-400 flex items-center justify-center">
                    <i data-lucide="trees" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs pt-2 border-t border-gray-100 dark:border-gray-700">
                <span class="text-emerald-600 dark:text-emerald-400 font-medium">Reboisasi mitra</span>
                <a href="{{ route('membership.index') }}" class="text-green-600 dark:text-green-400 font-bold hover:underline inline-flex items-center gap-0.5">
                    Tanam <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recycler Level Progression & Quick Shortcuts -->
    <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <!-- Left: Level Progress -->
            <div class="lg:col-span-7 space-y-2">
                <div class="flex items-center justify-between text-xs font-semibold">
                    <span class="text-gray-700 dark:text-gray-300">
                        Tingkat Recycler: <strong class="text-primary-600 dark:text-primary-400">{{ $tierName }}</strong>
                    </span>
                    <span class="text-gray-500 dark:text-gray-400">
                        Target: <strong class="text-gray-900 dark:text-white">{{ $nextTierName }}</strong> ({{ number_format($pointsRemaining) }} poin lagi)
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3.5 dark:bg-gray-700 overflow-hidden">
                    <div class="bg-primary-600 h-3.5 rounded-full transition-all duration-500" style="width: {{ $tierProgressPercent }}%"></div>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">
                    Kumpulkan poin dengan memindai sampah dengan kamera AI (+10 Pts) atau setor ke bank sampah terdekat untuk naik level.
                </p>
            </div>

            <!-- Right: Fast Shortcut Pills -->
            <div class="lg:col-span-5 grid grid-cols-3 gap-2">
                <a href="{{ route('scanner.index') }}" class="flex flex-col items-center justify-center p-2.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-center transition">
                    <i data-lucide="scan-line" class="w-5 h-5 text-primary-600 dark:text-primary-400 mb-1"></i>
                    <span class="text-xs font-semibold text-gray-900 dark:text-white">Scan AI</span>
                </a>
                <a href="{{ route('pickup.index') }}" class="flex flex-col items-center justify-center p-2.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-center transition">
                    <i data-lucide="truck" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mb-1"></i>
                    <span class="text-xs font-semibold text-gray-900 dark:text-white">Jemput</span>
                </a>
                <a href="{{ route('dropoff.index') }}" class="flex flex-col items-center justify-center p-2.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-center transition">
                    <i data-lucide="map-pin" class="w-5 h-5 text-amber-600 dark:text-amber-400 mb-1"></i>
                    <span class="text-xs font-semibold text-gray-900 dark:text-white">Bank Sampah</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Pickup Tracking Banner (if any) -->
    @if($activePickup)
        <div class="bg-primary-50 dark:bg-primary-950/60 border border-primary-200 dark:border-primary-800 p-4 rounded-xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-600 text-white flex items-center justify-center shrink-0">
                    <i data-lucide="truck" class="w-5 h-5 animate-pulse"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-primary-700 dark:text-primary-300">Penjemputan Aktif</span>
                        <span class="text-xs font-mono font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">{{ $activePickup->pickup_code }}</span>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">
                        Jadwal: <strong>{{ \Carbon\Carbon::parse($activePickup->scheduled_date)->translatedFormat('d M Y') }}</strong> (Slot: {{ ucfirst($activePickup->scheduled_slot) }}) • Kurir: {{ $activePickup->courier_name ?? 'Kurir Mitra OLARA' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('pickup.show', $activePickup->pickup_code) }}" class="inline-flex items-center justify-center px-3.5 py-2 text-xs font-bold rounded-lg bg-primary-600 hover:bg-primary-700 text-white shadow-sm transition shrink-0">
                Lacak Penjemputan →
            </a>
        </div>
    @endif

    <!-- 2-Column Split: Recent Analyses & Point Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Left Panel: Recent AI Scans -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="scan-line" class="w-5 h-5 text-primary-600 dark:text-primary-400"></i>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white">Hasil Pindai Kamera AI Terbaru</h3>
                </div>
                <a href="{{ route('scanner.index') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                    Scan Baru →
                </a>
            </div>

            @if($recentAnalysis->isEmpty())
                <div class="text-center py-8 text-gray-400 dark:text-gray-500 text-xs">
                    <i data-lucide="scan" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                    Belum ada riwayat pemindaian sampah.<br />
                    Arahkan kamera smartphone ke botol atau material daur ulang Anda!
                </div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($recentAnalysis as $analysis)
                        <div class="py-3 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center justify-center shrink-0">
                                    <i data-lucide="box" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white leading-snug">{{ $analysis->material_name }}</p>
                                    <div class="flex items-center gap-2 text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                        <span>{{ $analysis->category }}</span>
                                        <span>•</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Akurasi {{ $analysis->confidence_rate }}%</span>
                                        <span>•</span>
                                        <span>{{ number_format($analysis->weight_kg, 1) }} kg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">
                                    Rp {{ number_format($analysis->total_estimated_value) }}
                                </span>
                                <span class="block text-[10px] text-gray-400">{{ $analysis->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right Panel: Recent Point Transactions -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white">Riwayat Transaksi Eco-Points</h3>
                </div>
                <a href="{{ route('rewards.index') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                    Lihat Dompet →
                </a>
            </div>

            @if($recentTransactions->isEmpty())
                <div class="text-center py-8 text-gray-400 dark:text-gray-500 text-xs">
                    <i data-lucide="gift" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                    Belum ada transaksi poin terbaru.
                </div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($recentTransactions as $tx)
                        <div class="py-3 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg {{ $tx->amount >= 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-950 dark:text-red-400' }} flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $tx->amount >= 0 ? 'arrow-up-right' : 'arrow-down-left' }}" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-900 dark:text-white leading-snug line-clamp-1">{{ $tx->description }}</p>
                                    <span class="text-[10px] text-gray-400">{{ $tx->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-extrabold tabular-nums {{ $tx->amount >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $tx->amount >= 0 ? '+' : '' }}{{ number_format($tx->amount) }} Pts
                                </span>
                                <span class="block text-[10px] text-gray-400">Saldo: {{ number_format($tx->balance_after) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
