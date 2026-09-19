@extends('layouts.app')

@section('title', 'Dashboard Utama — OLARA')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-8">

    <!-- Top Greeting & Action Cluster -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 bg-white dark:bg-stone-800/95 p-6 sm:p-7 rounded-2xl border border-stone-200/80 dark:border-stone-700/80 shadow-soft">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="font-outfit text-xl sm:text-2xl font-extrabold text-stone-900 dark:text-white tracking-tight">
                    Halo, {{ $user ? explode(' ', $user->name)[0] : 'Pejuang Bumi' }}!
                </h1>
                <span class="inline-flex items-center gap-1.5 bg-[#FFE4E1] text-[#a7322b] dark:bg-stone-700 dark:text-[#ffb7b2] text-xs font-bold px-3 py-1 rounded-full">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i> {{ $tierName }}
                </span>
                @if($user && $user->membership_tier === 'premium')
                    <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-200 text-xs font-bold px-3 py-1 rounded-full">
                        <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-600"></i> Premium 1.2x
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400">
                Kelola sampah pilahan dan pantau dampak lingkungan Anda.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <button type="button" onclick="openOnboardingModal()" class="olara-btn-secondary cursor-pointer">
                <i data-lucide="compass" class="w-4 h-4 text-stone-500"></i>
                <span>Panduan</span>
            </button>
            <a href="{{ route('scanner.index') }}" class="olara-btn-primary">
                <i data-lucide="scan-line" class="w-4 h-4"></i>
                <span>Scan AI</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Stat Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
        <!-- Metric 1: Eco-Points Balance -->
        <div class="bg-white dark:bg-stone-800/95 p-6 rounded-2xl border border-stone-200/80 dark:border-stone-700/80 shadow-soft hover:shadow-md transition-all group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-semibold text-stone-500 dark:text-stone-400 uppercase tracking-wider">Saldo Poin</p>
                    <div class="w-10 h-10 rounded-full bg-[#FFE4E1]/80 text-[#cb3930] dark:bg-stone-700 dark:text-[#ffb7b2] flex items-center justify-center transition-transform group-hover:scale-105 shrink-0">
                        <i data-lucide="coins" class="w-5 h-5"></i>
                    </div>
                </div>
                <h3 class="font-outfit text-2xl sm:text-3xl font-extrabold text-stone-900 dark:text-white mt-2 tabular-nums">
                    {{ number_format($user?->eco_points ?? 0) }}
                </h3>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs pt-3 border-t border-stone-100 dark:border-stone-700/60">
                <span class="text-stone-500 dark:text-stone-400">≈ Rp {{ number_format(($user?->eco_points ?? 0) * 100) }}</span>
                <a href="{{ route('rewards.index') }}" class="text-[#cb3930] dark:text-[#ffb7b2] font-bold hover:underline inline-flex items-center gap-0.5">
                    Tukar <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        <!-- Metric 2: Waste Managed -->
        <div class="bg-white dark:bg-stone-800/95 p-6 rounded-2xl border border-stone-200/80 dark:border-stone-700/80 shadow-soft hover:shadow-md transition-all group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-semibold text-stone-500 dark:text-stone-400 uppercase tracking-wider">Sampah Dikelola</p>
                    <div class="w-10 h-10 rounded-full bg-stone-100 text-stone-700 dark:bg-stone-700 dark:text-stone-200 flex items-center justify-center transition-transform group-hover:scale-105 shrink-0">
                        <i data-lucide="recycle" class="w-5 h-5"></i>
                    </div>
                </div>
                <h3 class="font-outfit text-2xl sm:text-3xl font-extrabold text-stone-900 dark:text-white mt-2 tabular-nums">
                    {{ number_format($totalWasteManaged, 1) }} <span class="text-sm font-medium text-stone-500 dark:text-stone-400">kg</span>
                </h3>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs pt-3 border-t border-stone-100 dark:border-stone-700/60">
                <span class="text-[#168A5B] dark:text-emerald-400 font-medium">Bebas TPA</span>
                <a href="{{ route('pickup.index') }}" class="text-stone-900 dark:text-stone-200 font-bold hover:underline inline-flex items-center gap-0.5">
                    Jemput <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        <!-- Metric 3: CO2 Avoided -->
        <div class="bg-white dark:bg-stone-800/95 p-6 rounded-2xl border border-stone-200/80 dark:border-stone-700/80 shadow-soft hover:shadow-md transition-all group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-semibold text-stone-500 dark:text-stone-400 uppercase tracking-wider">CO₂ Dicegah</p>
                    <div class="w-10 h-10 rounded-full bg-[#E8EFE8] text-[#168A5B] dark:bg-stone-700 dark:text-emerald-300 flex items-center justify-center transition-transform group-hover:scale-105 shrink-0">
                        <i data-lucide="cloud-off" class="w-5 h-5"></i>
                    </div>
                </div>
                <h3 class="font-outfit text-2xl sm:text-3xl font-extrabold text-stone-900 dark:text-white mt-2 tabular-nums">
                    {{ number_format($totalCo2Avoided, 1) }} <span class="text-sm font-medium text-stone-500 dark:text-stone-400">kg</span>
                </h3>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs pt-3 border-t border-stone-100 dark:border-stone-700/60">
                <span class="text-stone-500 dark:text-stone-400">Reduksi Emisi</span>
                <a href="{{ route('analytics.index') }}" class="text-[#168A5B] dark:text-emerald-400 font-bold hover:underline inline-flex items-center gap-0.5">
                    Analisis <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        <!-- Metric 4: Trees Equivalent -->
        <div class="bg-white dark:bg-stone-800/95 p-6 rounded-2xl border border-stone-200/80 dark:border-stone-700/80 shadow-soft hover:shadow-md transition-all group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-semibold text-stone-500 dark:text-stone-400 uppercase tracking-wider">Pohon Diselamatkan</p>
                    <div class="w-10 h-10 rounded-full bg-[#E8EFE8] text-[#168A5B] dark:bg-stone-700 dark:text-emerald-300 flex items-center justify-center transition-transform group-hover:scale-105 shrink-0">
                        <i data-lucide="trees" class="w-5 h-5"></i>
                    </div>
                </div>
                <h3 class="font-outfit text-2xl sm:text-3xl font-extrabold text-stone-900 dark:text-white mt-2 tabular-nums">
                    {{ number_format($treesEquivalent, 1) }} <span class="text-sm font-medium text-stone-500 dark:text-stone-400">Pohon</span>
                </h3>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs pt-3 border-t border-stone-100 dark:border-stone-700/60">
                <span class="text-[#168A5B] dark:text-emerald-400 font-medium">Reboisasi Mitra</span>
                <a href="{{ route('membership.index') }}" class="text-[#168A5B] dark:text-emerald-400 font-bold hover:underline inline-flex items-center gap-0.5">
                    Tanam <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recycler Level Progression & Quick Shortcuts -->
    <div class="bg-white dark:bg-stone-800/95 p-6 sm:p-7 rounded-2xl border border-stone-200/80 dark:border-stone-700/80 shadow-soft">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">
            <!-- Left: Level Progress -->
            <div class="lg:col-span-7 space-y-3">
                <div class="flex items-center justify-between text-xs font-semibold">
                    <span class="text-stone-700 dark:text-stone-300">
                        Level: <strong class="text-[#cb3930] dark:text-[#ffb7b2] font-bold">{{ $tierName }}</strong>
                    </span>
                    <span class="text-stone-500 dark:text-stone-400">
                        {{ number_format($pointsRemaining) }} poin lagi ke <strong class="text-stone-900 dark:text-white">{{ $nextTierName }}</strong>
                    </span>
                </div>
                <div class="w-full bg-stone-100 rounded-full h-3 dark:bg-stone-700 overflow-hidden p-0.5">
                    <div class="bg-[#cb3930] h-full rounded-full transition-all duration-500" style="width: {{ $tierProgressPercent }}%"></div>
                </div>
                <p class="text-[11px] text-stone-400 dark:text-stone-500">
                    Setor sampah pilahan atau scan AI untuk naik ke level berikutnya.
                </p>
            </div>

            <!-- Right: Fast Shortcut Pills -->
            <div class="lg:col-span-5 grid grid-cols-3 gap-3">
                <a href="{{ route('scanner.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-stone-200/80 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-700/70 text-center transition group shadow-2xs active:scale-[0.98]">
                    <i data-lucide="scan-line" class="w-5 h-5 text-stone-800 dark:text-stone-200 mb-1 group-hover:scale-105 transition-transform"></i>
                    <span class="text-xs font-semibold text-stone-800 dark:text-stone-200">Scan AI</span>
                </a>
                <a href="{{ route('pickup.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-stone-200/80 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-700/70 text-center transition group shadow-2xs active:scale-[0.98]">
                    <i data-lucide="truck" class="w-5 h-5 text-[#cb3930] mb-1 group-hover:scale-105 transition-transform"></i>
                    <span class="text-xs font-semibold text-stone-800 dark:text-stone-200">Jemput</span>
                </a>
                <a href="{{ route('dropoff.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-stone-200/80 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-700/70 text-center transition group shadow-2xs active:scale-[0.98]">
                    <i data-lucide="map-pin" class="w-5 h-5 text-stone-800 dark:text-stone-200 mb-1 group-hover:scale-105 transition-transform"></i>
                    <span class="text-xs font-semibold text-stone-800 dark:text-stone-200">Bank Sampah</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Pickup Tracking Banner (if any) -->
    @if($activePickup)
        <div class="bg-[#FFE4E1]/30 dark:bg-stone-800/95 border border-[#FFB7B2]/50 dark:border-stone-700 p-5 sm:p-6 rounded-2xl shadow-soft flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-full bg-[#FFB7B2] text-[#292524] flex items-center justify-center shrink-0 shadow-xs">
                    <i data-lucide="truck" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#cb3930] bg-[#FFE4E1] px-2.5 py-0.5 rounded-full">Penjemputan Aktif</span>
                        <span class="text-xs font-mono font-bold text-stone-800 dark:text-stone-200 bg-white dark:bg-stone-900 px-2 py-0.5 rounded-full border border-stone-200 dark:border-stone-700">{{ $activePickup->pickup_code }}</span>
                    </div>
                    <p class="text-xs text-stone-600 dark:text-stone-300 mt-1">
                        Jadwal: <strong>{{ \Carbon\Carbon::parse($activePickup->scheduled_date)->translatedFormat('d M Y') }}</strong> (Slot: {{ ucfirst($activePickup->scheduled_slot) }}) • Kurir: {{ $activePickup->courier_name ?? 'Mitra OLARA' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('pickup.show', $activePickup->pickup_code) }}" class="olara-btn-primary shrink-0">
                <span>Lacak Armada</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    @endif

    <!-- 2-Column Split: Recent Analyses & Point Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Panel: Recent AI Scans -->
        <div class="bg-white dark:bg-stone-800/95 p-6 sm:p-7 rounded-2xl border border-stone-200/80 dark:border-stone-700/80 shadow-soft">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-stone-100 dark:border-stone-700/60">
                <div class="flex items-center gap-2">
                    <i data-lucide="scan-line" class="w-4 h-4 text-[#cb3930]"></i>
                    <h3 class="font-outfit text-sm sm:text-base font-bold text-stone-900 dark:text-white">Riwayat Scan AI</h3>
                </div>
                <a href="{{ route('scanner.index') }}" class="text-xs font-bold text-[#cb3930] dark:text-[#ffb7b2] hover:underline">
                    Scan Baru →
                </a>
            </div>

            @if($recentAnalysis->isEmpty())
                <div class="text-center py-10 text-stone-400 dark:text-stone-500 text-xs">
                    <i data-lucide="scan" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                    Belum ada riwayat scan sampah.
                </div>
            @else
                <div class="divide-y divide-stone-100 dark:divide-stone-700/60">
                    @foreach($recentAnalysis as $analysis)
                        <div class="py-3.5 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-stone-100 dark:bg-stone-700 text-stone-700 dark:text-stone-300 flex items-center justify-center shrink-0">
                                    <i data-lucide="box" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-stone-900 dark:text-white leading-snug">{{ $analysis->material_name }}</p>
                                    <div class="flex items-center gap-2 text-[11px] text-stone-500 dark:text-stone-400 mt-0.5">
                                        <span>{{ $analysis->category }}</span>
                                        <span>•</span>
                                        <span class="text-[#168A5B] dark:text-emerald-400 font-semibold">{{ $analysis->confidence_rate }}%</span>
                                        <span>•</span>
                                        <span>{{ number_format($analysis->weight_kg, 1) }} kg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold text-[#168A5B] dark:text-emerald-400 tabular-nums">
                                    Rp {{ number_format($analysis->total_estimated_value) }}
                                </span>
                                <span class="block text-[10px] text-stone-400">{{ $analysis->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right Panel: Recent Point Transactions -->
        <div class="bg-white dark:bg-stone-800/95 p-6 sm:p-7 rounded-2xl border border-stone-200/80 dark:border-stone-700/80 shadow-soft">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-stone-100 dark:border-stone-700/60">
                <div class="flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-[#cb3930]"></i>
                    <h3 class="font-outfit text-sm sm:text-base font-bold text-stone-900 dark:text-white">Transaksi Poin</h3>
                </div>
                <a href="{{ route('rewards.index') }}" class="text-xs font-bold text-[#cb3930] dark:text-[#ffb7b2] hover:underline">
                    Lihat Dompet →
                </a>
            </div>

            @if($recentTransactions->isEmpty())
                <div class="text-center py-10 text-stone-400 dark:text-stone-500 text-xs">
                    <i data-lucide="gift" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                    Belum ada transaksi poin terbaru.
                </div>
            @else
                <div class="divide-y divide-stone-100 dark:divide-stone-700/60">
                    @foreach($recentTransactions as $tx)
                        <div class="py-3.5 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full {{ $tx->amount >= 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-stone-100 text-stone-600 dark:bg-stone-700 dark:text-stone-300' }} flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $tx->amount >= 0 ? 'arrow-up-right' : 'arrow-down-left' }}" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-stone-900 dark:text-white leading-snug line-clamp-1">{{ $tx->description }}</p>
                                    <span class="text-[10px] text-stone-400">{{ $tx->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-extrabold tabular-nums {{ $tx->amount >= 0 ? 'text-[#168A5B] dark:text-emerald-400' : 'text-stone-500 dark:text-stone-400' }}">
                                    {{ $tx->amount >= 0 ? '+' : '' }}{{ number_format($tx->amount) }} Pts
                                </span>
                                <span class="block text-[10px] text-stone-400">Saldo: {{ number_format($tx->balance_after) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
