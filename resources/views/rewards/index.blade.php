@extends('layouts.app')

@section('title', 'Tukar Eco-Points & Hadiah — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Points Wallet Summary Banner (PRD Section F) -->
    <div class="rounded-3xl gradient-card text-white p-6 sm:p-8 shadow-xl shadow-emerald-950/10 relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-8 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-200 bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                    Dompet Eco-Points Terintegrasi
                </span>
                <div class="flex items-baseline gap-3">
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight tabular-nums">
                        {{ number_format($user?->eco_points ?? 0) }}
                    </h1>
                    <span class="text-xl font-bold text-emerald-200">Points Tersedia</span>
                </div>
                <p class="text-xs text-emerald-100/90 leading-relaxed">
                    Setiap 1 Eco-Point bernilai setara <strong>Rp 100</strong>. Anda dapat mencairkan poin ke dompet digital, menukarkan voucher ritel, atau mendonasikannya untuk aksi reboisasi hutan mangrove.
                </p>
            </div>

            <div class="lg:col-span-4 flex flex-col gap-2.5">
                <div class="bg-black/20 backdrop-blur-sm p-4 rounded-2xl border border-white/10 text-center">
                    <span class="text-xs text-emerald-200">Estimasi Total Nilai Konversi</span>
                    <p class="text-2xl font-extrabold text-white mt-0.5 tabular-nums">
                        Rp {{ number_format(($user?->eco_points ?? 0) * 100) }}
                    </p>
                </div>
                <a href="{{ route('scanner.index') }}" class="w-full py-2.5 px-4 rounded-xl bg-white text-[#0B4F38] hover:bg-emerald-50 text-xs font-bold text-center transition shadow-sm">
                    + Kumpulkan Poin Lebih Banyak (Scan AI)
                </a>
            </div>
        </div>
    </div>

    <!-- Tree Reforestation Community Impact Banner (PRD Donasi Aksi Hijau) -->
    <div class="bg-white border border-[#BFE7D0] rounded-3xl p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#EEF9F2] text-[#168A5B] flex items-center justify-center shrink-0">
                <i data-lucide="sprout" class="w-6 h-6 text-[#168A5B]"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2 py-0.5 rounded">
                    Aksi Hijau Berkelanjutan
                </span>
                <h3 class="text-base font-bold text-[#1B211E] mt-1">Program Reboisasi Hutan Mangrove Pesisir</h3>
                <p class="text-xs text-[#66716B] mt-0.5">Tukar 200 Eco-Points untuk 1 bibit pohon mangrove bersertifikat. Mari bersama hijaukan kembali bumi.</p>
            </div>
        </div>

        <div class="flex items-center gap-4 shrink-0">
            <div class="text-right">
                <span class="text-xs text-gray-400">Total Pohon Tertanam:</span>
                <p class="text-lg font-extrabold text-[#0B4F38] tabular-nums">{{ number_format($totalTreesDonated) }} Pohon</p>
            </div>
            <button type="button" onclick="openRedeemModal(9, 'Donasi 1 Bibit Pohon Mangrove Pesisir Jawa', 200, 'Yayasan LindungiHutan')" class="py-2.5 px-5 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-xs font-bold transition shadow-sm">
                Donasikan Sekarang
            </button>
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex items-center justify-between border-b border-[#DDE3DF] pb-4 flex-wrap gap-4">
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1">
            <a href="{{ route('rewards.index', ['category' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'all' ? 'bg-[#168A5B] text-white shadow-sm' : 'bg-white text-[#66716B] border border-[#DDE3DF] hover:bg-gray-50' }}">
                Semua Katalog
            </a>
            <a href="{{ route('rewards.index', ['category' => 'e_wallet']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'e_wallet' ? 'bg-[#168A5B] text-white shadow-sm' : 'bg-white text-[#66716B] border border-[#DDE3DF] hover:bg-gray-50' }}">
                Saldo E-Wallet
            </a>
            <a href="{{ route('rewards.index', ['category' => 'voucher']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'voucher' ? 'bg-[#168A5B] text-white shadow-sm' : 'bg-white text-[#66716B] border border-[#DDE3DF] hover:bg-gray-50' }}">
                Voucher Belanja & Hiburan
            </a>
            <a href="{{ route('rewards.index', ['category' => 'donasi']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'donasi' ? 'bg-[#168A5B] text-white shadow-sm' : 'bg-white text-[#66716B] border border-[#DDE3DF] hover:bg-gray-50' }}">
                Donasi Aksi Hijau
            </a>
        </div>

        <span class="text-xs text-gray-400 font-medium">{{ $rewards->count() }} Pilihan Reward</span>
    </div>

    <!-- Rewards Grid (3 Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rewards as $reward)
            <div class="bg-white border border-[#DDE3DF] hover:border-[#168A5B] rounded-3xl p-6 shadow-sm flex flex-col justify-between transition group hover:shadow-md">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#168A5B] bg-[#EEF9F2] border border-[#BFE7D0] px-2.5 py-0.5 rounded-full">
                            {{ $reward->badge_text ?? ucfirst($reward->category) }}
                        </span>
                        <span class="text-xs font-bold text-gray-500">{{ $reward->provider }}</span>
                    </div>

                    <div>
                        <h4 class="text-base font-extrabold text-[#1B211E] group-hover:text-[#168A5B] transition line-clamp-1">
                            {{ $reward->title }}
                        </h4>
                        <p class="text-xs text-[#66716B] mt-1.5 leading-relaxed line-clamp-2">
                            {{ $reward->description }}
                        </p>
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-100 mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-gray-400 block font-medium">Dibutuhkan:</span>
                            <span class="text-base font-extrabold text-[#0B4F38] tabular-nums">
                                {{ number_format($reward->points_required) }} <span class="text-xs text-[#168A5B]">Pts</span>
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-gray-400 block font-medium">Nilai Hadiah:</span>
                            <span class="text-xs font-bold text-gray-700 tabular-nums">
                                Rp {{ number_format($reward->value_idr) }}
                            </span>
                        </div>
                    </div>

                    @if(($user?->eco_points ?? 0) >= $reward->points_required)
                        <button type="button" onclick="openRedeemModal({{ $reward->id }}, '{{ addslashes($reward->title) }}', {{ $reward->points_required }}, '{{ $reward->provider }}')" class="w-full py-2.5 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-xs font-bold shadow-sm transition">
                            Tukar Sekarang
                        </button>
                    @else
                        <button disabled class="w-full py-2.5 px-4 rounded-xl bg-gray-100 text-gray-400 text-xs font-bold cursor-not-allowed">
                            Poin Kurang ({{ number_format($reward->points_required - ($user?->eco_points ?? 0)) }} Pts lagi)
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Past Redemptions & Vouchers Ledger -->
    @if($myRedemptions->isNotEmpty())
        <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4 mt-12">
            <h3 class="text-base font-bold text-[#1B211E]">Voucher & Penukaran Aktif Anda</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($myRedemptions as $redemption)
                    <div class="p-4 rounded-2xl border border-dashed border-[#168A5B] bg-[#EEF9F2]/50 flex items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-bold text-[#168A5B] uppercase tracking-wider">{{ $redemption->reward->provider }}</span>
                            <h5 class="text-sm font-bold text-[#1B211E]">{{ $redemption->reward->title }}</h5>
                            <p class="text-xs text-gray-400 font-mono mt-0.5">Ditukar: {{ $redemption->created_at->format('d M Y') }}</p>
                        </div>

                        <div class="text-right">
                            <span class="text-[10px] text-gray-500 block font-medium">KODE VOUCHER:</span>
                            <span class="font-mono font-extrabold text-sm text-[#0B4F38] bg-white px-2.5 py-1 rounded border border-[#BFE7D0] inline-block mt-0.5 select-all">
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
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-[#DDE3DF]">
        <button onclick="closeRedeemModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1.5 rounded-full hover:bg-gray-100">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="text-center space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-[#EEF9F2] text-[#168A5B] mx-auto flex items-center justify-center">
                <i data-lucide="gift" class="w-7 h-7 text-[#168A5B]"></i>
            </div>
            <h3 class="text-lg font-extrabold text-[#1B211E]">Konfirmasi Penukaran Poin</h3>
            <p id="modalRewardDesc" class="text-xs text-[#66716B] leading-relaxed">
                Apakah Anda yakin ingin menukarkan <strong>250 Poin</strong> untuk reward ini?
            </p>
        </div>

        <form id="redeemForm" action="" method="POST" class="mt-6 space-y-3">
            @csrf
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-sm font-bold shadow-sm transition">
                Ya, Tukar Poin Sekarang
            </button>
            <button type="button" onclick="closeRedeemModal()" class="w-full py-2.5 px-4 rounded-xl border border-gray-200 text-gray-600 text-xs font-semibold hover:bg-gray-50 transition">
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
