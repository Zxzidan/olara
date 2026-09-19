@extends('layouts.app')

@section('title', 'Tukar Eco-Points & Hadiah — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Points Wallet Summary Banner (PRD Section F) -->
    <div class="rounded-3xl bg-stone-900 text-white dark:bg-stone-950 border border-stone-800 p-6 sm:p-8 shadow-soft-lg relative overflow-hidden">
        <!-- Decorative warm ambient glow -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-[#FFB7B2]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-8 space-y-3">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#FFB7B2] bg-white/10 border border-white/10 px-3.5 py-1 rounded-full backdrop-blur-sm">
                    <i data-lucide="wallet" class="w-3.5 h-3.5 text-[#FFB7B2]"></i> Dompet Eco-Points Terintegrasi
                </span>
                <div class="flex items-baseline gap-3">
                    <h1 class="font-outfit text-4xl sm:text-5xl font-extrabold tracking-tight tabular-nums text-white">
                        {{ number_format($user?->eco_points ?? 0) }}
                    </h1>
                    <span class="text-xl font-bold text-[#FFB7B2]">Points Tersedia</span>
                </div>
                <p class="text-xs sm:text-sm text-stone-300 leading-relaxed max-w-2xl">
                    Setiap 1 Eco-Point bernilai setara <strong class="text-white">Rp 100</strong>. Anda dapat mencairkan poin ke dompet digital, menukarkan voucher ritel, atau mendonasikannya untuk aksi reboisasi hutan mangrove.
                </p>
            </div>

            <div class="lg:col-span-4 flex flex-col gap-3">
                <div class="bg-stone-800/90 backdrop-blur-sm p-4 sm:p-5 rounded-2xl border border-stone-700/80 text-center shadow-inner">
                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider block">Estimasi Total Nilai Konversi</span>
                    <p class="font-outfit text-2xl sm:text-3xl font-extrabold text-white mt-1 tabular-nums">
                        Rp {{ number_format(($user?->eco_points ?? 0) * 100) }}
                    </p>
                </div>
                <a href="{{ route('scanner.index') }}" class="w-full py-3 px-4 rounded-xl bg-[#FFB7B2] hover:bg-[#ffa49e] text-stone-950 text-xs sm:text-sm font-bold text-center transition shadow-sm flex items-center justify-center gap-2">
                    <i data-lucide="scan-line" class="w-4 h-4 text-stone-950"></i>
                    <span>+ Kumpulkan Poin Lebih Banyak (Scan AI)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Tree Reforestation Community Impact Banner (PRD Donasi Aksi Hijau) -->
    <div class="bg-white dark:bg-stone-800/95 border border-stone-200/80 dark:border-stone-700/80 rounded-3xl p-6 sm:p-7 shadow-soft flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#E8EFE8] text-[#168A5B] dark:bg-stone-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
                <i data-lucide="sprout" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#E8EFE8] dark:bg-stone-700 dark:text-emerald-300 px-2.5 py-0.5 rounded-full">
                    Aksi Hijau Berkelanjutan
                </span>
                <h3 class="text-base font-bold text-stone-900 dark:text-white mt-1">Program Reboisasi Hutan Mangrove Pesisir</h3>
                <p class="text-xs text-stone-500 dark:text-stone-400 mt-0.5">Tukar 200 Eco-Points untuk 1 bibit pohon mangrove bersertifikat. Mari bersama hijaukan kembali bumi.</p>
            </div>
        </div>

        <div class="flex items-center gap-4 shrink-0">
            <div class="text-right">
                <span class="text-xs text-stone-400">Total Pohon Tertanam:</span>
                <p class="text-lg font-extrabold text-[#168A5B] dark:text-emerald-400 tabular-nums">{{ number_format($totalTreesDonated) }} Pohon</p>
            </div>
            <button type="button" onclick="openRedeemModal(9, 'Donasi 1 Bibit Pohon Mangrove Pesisir Jawa', 200, 'Yayasan LindungiHutan')" class="olara-btn-primary py-2.5 px-5 text-xs">
                Donasikan Sekarang
            </button>
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex items-center justify-between border-b border-stone-200/80 dark:border-stone-700/80 pb-4 flex-wrap gap-4">
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('rewards.index', ['category' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'all' ? 'bg-[#FFB7B2] text-stone-950 shadow-sm' : 'bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-300 border border-stone-200/80 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-700/50' }}">
                Semua Katalog
            </a>
            <a href="{{ route('rewards.index', ['category' => 'e_wallet']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'e_wallet' ? 'bg-[#FFB7B2] text-stone-950 shadow-sm' : 'bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-300 border border-stone-200/80 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-700/50' }}">
                Saldo E-Wallet
            </a>
            <a href="{{ route('rewards.index', ['category' => 'voucher']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'voucher' ? 'bg-[#FFB7B2] text-stone-950 shadow-sm' : 'bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-300 border border-stone-200/80 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-700/50' }}">
                Voucher Belanja & Hiburan
            </a>
            <a href="{{ route('rewards.index', ['category' => 'donasi']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'donasi' ? 'bg-[#FFB7B2] text-stone-950 shadow-sm' : 'bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-300 border border-stone-200/80 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-700/50' }}">
                Donasi Aksi Hijau
            </a>
        </div>

        <span class="text-xs text-stone-400 font-medium">{{ $rewards->count() }} Pilihan Hadiah</span>
    </div>

    <!-- Rewards Grid (3 Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rewards as $reward)
            <div class="bg-white dark:bg-stone-800/95 border border-stone-200/80 dark:border-stone-700/80 hover:border-stone-300 dark:hover:border-stone-600 rounded-3xl p-6 shadow-soft flex flex-col justify-between transition group hover:shadow-md">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#a7322b] bg-[#FFE4E1] dark:bg-stone-700 dark:text-[#ffb7b2] px-2.5 py-0.5 rounded-full">
                            {{ $reward->badge_text ?? ucfirst($reward->category) }}
                        </span>
                        <span class="text-xs font-bold text-stone-500 dark:text-stone-400">{{ $reward->provider }}</span>
                    </div>

                    <div>
                        <h4 class="font-outfit text-base font-extrabold text-stone-900 dark:text-white group-hover:text-[#cb3930] dark:group-hover:text-[#ffb7b2] transition leading-snug">
                            {{ $reward->title }}
                        </h4>
                        <p class="text-xs text-stone-500 dark:text-stone-400 mt-1.5 leading-relaxed">
                            {{ $reward->description }}
                        </p>
                    </div>
                </div>

                <div class="pt-5 border-t border-stone-100 dark:border-stone-700/60 mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-stone-400 block font-medium">Dibutuhkan:</span>
                            <span class="font-outfit text-base font-extrabold text-stone-900 dark:text-white tabular-nums">
                                {{ number_format($reward->points_required) }} <span class="text-xs text-[#cb3930] dark:text-[#ffb7b2] font-bold">Pts</span>
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-stone-400 block font-medium">Nilai Hadiah:</span>
                            <span class="text-xs font-bold text-stone-700 dark:text-stone-300 tabular-nums">
                                Rp {{ number_format($reward->value_idr) }}
                            </span>
                        </div>
                    </div>

                    @if(($user?->eco_points ?? 0) >= $reward->points_required)
                        <button type="button" onclick="openRedeemModal({{ $reward->id }}, '{{ addslashes($reward->title) }}', {{ $reward->points_required }}, '{{ $reward->provider }}')" class="olara-btn-primary w-full py-2.5 px-4 text-xs font-bold justify-center">
                            Tukar Sekarang
                        </button>
                    @else
                        <button disabled class="w-full py-2.5 px-4 rounded-xl bg-stone-100 dark:bg-stone-700/50 text-stone-400 dark:text-stone-500 text-xs font-bold cursor-not-allowed">
                            Poin Kurang ({{ number_format($reward->points_required - ($user?->eco_points ?? 0)) }} Pts lagi)
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Past Redemptions & Vouchers Ledger -->
    @if($myRedemptions->isNotEmpty())
        <div class="bg-white dark:bg-stone-800/95 border border-stone-200/80 dark:border-stone-700/80 rounded-3xl p-6 shadow-soft space-y-4 mt-8">
            <h3 class="font-outfit text-base font-bold text-stone-900 dark:text-white">Voucher & Penukaran Aktif Anda</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($myRedemptions as $redemption)
                    <div class="p-4 rounded-2xl border border-dashed border-[#FFB7B2] bg-[#FFE4E1]/20 dark:bg-stone-700/30 flex items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-bold text-[#cb3930] dark:text-[#ffb7b2] uppercase tracking-wider">{{ $redemption->reward->provider }}</span>
                            <h5 class="text-sm font-bold text-stone-900 dark:text-white">{{ $redemption->reward->title }}</h5>
                            <p class="text-xs text-stone-400 font-mono mt-0.5">Ditukar: {{ $redemption->created_at->format('d M Y') }}</p>
                        </div>

                        <div class="text-right">
                            <span class="text-[10px] text-stone-500 dark:text-stone-400 block font-medium">KODE VOUCHER:</span>
                            <span class="font-mono font-extrabold text-sm text-stone-900 dark:text-white bg-white dark:bg-stone-800 px-2.5 py-1 rounded border border-stone-200 dark:border-stone-700 inline-block mt-0.5 select-all">
                                {{ $redemption->redemption_code }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<!-- Instant Redeem Confirmation Modal -->
<div id="redeemModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-stone-200 dark:border-stone-700">
        <button onclick="closeRedeemModal()" class="absolute top-4 right-4 text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 p-1.5 rounded-full hover:bg-stone-100 dark:hover:bg-stone-700 transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="text-center space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-[#FFE4E1] text-[#cb3930] dark:bg-stone-700 dark:text-[#ffb7b2] mx-auto flex items-center justify-center">
                <i data-lucide="gift" class="w-7 h-7"></i>
            </div>
            <h3 class="font-outfit text-lg font-extrabold text-stone-900 dark:text-white">Konfirmasi Penukaran Poin</h3>
            <p id="modalRewardDesc" class="text-xs text-stone-500 dark:text-stone-400 leading-relaxed">
                Apakah Anda yakin ingin menukarkan <strong>250 Poin</strong> untuk reward ini?
            </p>
        </div>

        <form id="redeemForm" action="" method="POST" class="mt-6 space-y-3">
            @csrf
            <button type="submit" class="olara-btn-primary w-full py-3 text-sm justify-center">
                Ya, Tukar Poin Sekarang
            </button>
            <button type="button" onclick="closeRedeemModal()" class="olara-btn-secondary w-full py-2.5 text-xs justify-center">
                Batal
            </button>
        </form>
    </div>
</div>

<script>
    function openRedeemModal(id, title, points, provider) {
        document.getElementById('modalRewardDesc').innerHTML = `Anda akan menukarkan <strong>${points.toLocaleString('id-ID')} Eco-Points</strong> untuk mendapatkan <strong>${title}</strong> dari ${provider}.`;
        document.getElementById('redeemForm').action = `/rewards/${id}/redeem`;
        document.getElementById('redeemModal').classList.remove('hidden');
    }

    function closeRedeemModal() {
        document.getElementById('redeemModal').classList.add('hidden');
    }
</script>
@endsection
