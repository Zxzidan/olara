@extends('layouts.app')

@section('title', 'Skema Keanggotaan (Membership) — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8 sm:space-y-12">

    <!-- Header & Pitch -->
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-3 py-1 rounded-full">
            Tingkatkan Dampak Anda
        </span>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-[#1B211E] tracking-tight">
            Pilihan Paket Keanggotaan OLARA
        </h1>
        <p class="text-xs sm:text-sm text-[#66716B] leading-relaxed">
            Nikmati fasilitas penjemputan armada prioritas, poin reward ekstra, dan pemindaian kamera AI tanpa batas dengan beralih ke paket Premium.
        </p>

        <!-- Monthly / Annual Toggle -->
        <div class="inline-flex max-w-full flex-wrap sm:flex-nowrap justify-center items-center gap-1.5 sm:gap-3 bg-white p-1.5 rounded-2xl border border-[#DDE3DF] shadow-xs mt-4">
            <button type="button" onclick="setBilling('monthly')" id="btnMonthly" class="py-1.5 px-3 sm:px-4 rounded-xl text-xs font-bold bg-[#168A5B] text-white transition">
                Tagihan Bulanan
            </button>
            <button type="button" onclick="setBilling('yearly')" id="btnYearly" class="py-1.5 px-3 sm:px-4 rounded-xl text-xs font-bold text-[#66716B] hover:text-[#1B211E] transition flex items-center gap-1.5">
                Tagihan Tahunan <span class="bg-amber-100 text-amber-800 text-[10px] px-1.5 py-0.5 rounded-md font-extrabold">Hemat 20%</span>
            </button>
        </div>
    </div>

    <!-- 2 Plan Cards Side-by-Side (PRD Section 9) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-4xl mx-auto items-stretch">
        
        <!-- Plan 1: Paket Lite (Gratis) -->
        <div class="bg-white border border-[#DDE3DF] rounded-3xl p-5 sm:p-8 shadow-sm flex flex-col justify-between space-y-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
                        Paket Dasar
                    </span>
                    @if($user && $user->membership_tier === 'lite')
                        <span class="text-xs font-bold text-[#168A5B] bg-[#EEF9F2] px-2.5 py-1 rounded-full border border-[#BFE7D0]">
                            ● Paket Aktif Anda
                        </span>
                    @endif
                </div>

                <div>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-[#1B211E]">Olara Lite</h3>
                    <p class="text-xs text-[#66716B] mt-1">Solusi awal untuk mulai memilah sampah rumah tangga secara praktis.</p>
                </div>

                <div class="flex items-baseline gap-1 pt-2">
                    <span class="text-3xl sm:text-4xl font-extrabold text-[#1B211E]">Rp 0</span>
                    <span class="text-xs text-gray-400 font-semibold">/ selamanya</span>
                </div>

                <ul class="space-y-3 text-xs text-[#66716B] pt-4 border-t border-gray-100">
                    <li class="flex items-center gap-2.5">
                        <i data-lucide="check" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span>Laporan dampak lingkungan dasar (Bulanan)</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i data-lucide="check" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span>1x Kuota penjemputan sampah per bulan</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i data-lucide="check" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span>Batas dompet Eco-Point hingga 1.000 Pts</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i data-lucide="check" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span>5x Kredit pemindaian kamera AI per bulan</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-gray-400 line-through">
                        <i data-lucide="x" class="w-4 h-4 text-gray-300 shrink-0"></i>
                        <span>Bebas biaya dasar penjemputan</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-gray-400 line-through">
                        <i data-lucide="x" class="w-4 h-4 text-gray-300 shrink-0"></i>
                        <span>Multiplier 1.2x poin setiap setoran</span>
                    </li>
                </ul>
            </div>

            <div class="pt-4">
                @if($user && $user->membership_tier === 'lite')
                    <button disabled class="w-full py-3 px-4 rounded-xl border border-[#DDE3DF] text-gray-400 text-xs font-bold bg-gray-50 cursor-default">
                        Paket yang Sedang Anda Gunakan
                    </button>
                @else
                    <form action="{{ route('membership.upgrade') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tier" value="lite" />
                        <button type="submit" class="w-full py-3 px-4 rounded-xl border border-gray-200 text-gray-700 text-xs font-bold hover:bg-gray-50 transition">
                            Kembali ke Paket Lite
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Plan 2: Paket Premium (Highlighted Card) -->
        <div class="bg-gradient-to-b from-[#EEF9F2] to-white border-2 border-[#168A5B] rounded-3xl p-5 sm:p-8 shadow-xl relative flex flex-col justify-between space-y-6">
            <!-- Highlight Ribbon -->
            <div class="absolute -top-3.5 right-4 sm:right-8">
                <span class="bg-[#168A5B] text-white text-[10px] font-extrabold uppercase tracking-wider px-3 py-1 rounded-full shadow-md flex items-center gap-1.5">
                    <i data-lucide="crown" class="w-3 h-3"></i> Paling Direkomendasikan
                </span>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-800 bg-amber-100 border border-amber-300 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-500"></i> Premium Eco-Tier
                    </span>
                    @if($user && $user->membership_tier === 'premium')
                        <span class="text-xs font-bold text-amber-800 bg-amber-100 px-2.5 py-1 rounded-full border border-amber-300">
                            ● Status Aktif Anda
                        </span>
                    @endif
                </div>

                <div>
                    <h3 class="text-2xl font-extrabold text-[#0B4F38]">Olara Premium</h3>
                    <p class="text-xs text-[#66716B] mt-1">Dukungan penuh untuk individu dan keluarga dengan aksi daur ulang maksimal.</p>
                </div>

                <div class="flex items-baseline gap-1 pt-2">
                    <span id="priceDisplay" class="text-4xl font-extrabold text-[#0B4F38] tabular-nums">Rp 39.000</span>
                    <span id="cycleDisplay" class="text-xs text-gray-500 font-semibold">/ bulan</span>
                </div>

                <ul class="space-y-3 text-xs text-[#1B211E] pt-4 border-t border-[#BFE7D0]">
                    <li class="flex items-center gap-2.5 font-medium">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span><strong>5x Penjemputan gratis</strong> per bulan (Bebas Biaya Dasar)</span>
                    </li>
                    <li class="flex items-center gap-2.5 font-medium">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span><strong>100 Token</strong> scan AI</span>
                    </li>
                    <li class="flex items-center gap-2.5 font-medium">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span><strong>Multiplier 1.2x poin ekstra</strong> di setiap transaksi</span>
                    </li>
                    <li class="flex items-center gap-2.5 font-medium">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span>Prioritas antrean penugasan armada kurir</span>
                    </li>
                    <li class="flex items-center gap-2.5 font-medium">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span>Batas saldo dompet diperluas s/d <strong>50.000 Pts</strong></span>
                    </li>
                    <li class="flex items-center gap-2.5 font-medium">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span>10% biaya langganan disalurkan otomatis untuk <strong>penanaman pohon</strong></span>
                    </li>
                </ul>
            </div>

            <div class="pt-4">
                @if($user && $user->membership_tier === 'premium')
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-center text-xs font-bold text-amber-900">
                        ✓ Anda sudah menikmati seluruh fasilitas Premium
                    </div>
                @else
                    <form action="{{ route('membership.upgrade') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tier" value="premium" />
                        <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-sm font-bold shadow-md transition flex items-center justify-center gap-2">
                            <i data-lucide="zap" class="w-4 h-4"></i> Upgrade ke Premium Sekarang (+100 Pts Bonus)
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>

    <!-- Feature Matrix Comparison Table (PRD Spec) -->
    <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 sm:p-8 shadow-sm space-y-4 max-w-4xl mx-auto">
        <h3 class="text-base font-bold text-[#1B211E]">Perbandingan Rinci Fitur</h3>
        
        <div class="border border-gray-100 rounded-2xl overflow-hidden">
            <table class="w-full text-xs text-left">
                <thead class="bg-[#F7F8F6] text-gray-500 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="p-3.5">Fasilitas / Fitur</th>
                        <th class="p-3.5 w-44">Paket Lite</th>
                        <th class="p-3.5 w-56 text-[#0B4F38] font-bold bg-[#EEF9F2]">Paket Premium</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($benefits as $b)
                        <tr>
                            <td class="p-3.5 font-medium text-gray-800">{{ $b['feature'] }}</td>
                            <td class="p-3.5 text-gray-500">{{ $b['lite'] }}</td>
                            <td class="p-3.5 font-bold text-[#168A5B] bg-[#EEF9F2]/50">{{ $b['premium'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function setBilling(cycle) {
        const btnMonthly = document.getElementById('btnMonthly');
        const btnYearly = document.getElementById('btnYearly');
        const priceDisplay = document.getElementById('priceDisplay');
        const cycleDisplay = document.getElementById('cycleDisplay');

        if (cycle === 'monthly') {
            btnMonthly.className = 'py-1.5 px-4 rounded-xl text-xs font-bold bg-[#168A5B] text-white transition';
            btnYearly.className = 'py-1.5 px-4 rounded-xl text-xs font-bold text-[#66716B] hover:text-[#1B211E] transition flex items-center gap-1.5';
            priceDisplay.textContent = 'Rp 39.000';
            cycleDisplay.textContent = '/ bulan';
        } else {
            btnYearly.className = 'py-1.5 px-4 rounded-xl text-xs font-bold bg-[#168A5B] text-white transition flex items-center gap-1.5';
            btnMonthly.className = 'py-1.5 px-4 rounded-xl text-xs font-bold text-[#66716B] hover:text-[#1B211E] transition';
            priceDisplay.textContent = 'Rp 390.000';
            cycleDisplay.textContent = '/ tahun (hemat 2 bulan)';
        }
    }
</script>
@endsection
