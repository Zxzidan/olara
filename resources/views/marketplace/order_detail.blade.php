@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number . ' — OLARA')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex items-center justify-between">
        <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#168A5B] hover:text-[#0F6B47]">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Katalog Bahan
        </a>
        <span class="text-xs font-mono font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">
            {{ $order->order_number }}
        </span>
    </div>

    <!-- Order Receipt & Certificate Card -->
    <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        
        <!-- Header & Status -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-6">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-0.5 rounded-full">
                    Pembayaran Berhasil
                </span>
                <h1 class="text-2xl font-extrabold text-[#1B211E] mt-1.5">Bukti Transaksi & Sertifikat Daur Ulang</h1>
                <p class="text-xs text-[#66716B]">Waktu Transaksi: {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
            </div>

            <div class="p-3.5 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0] text-center sm:text-right">
                <span class="text-[11px] text-[#0B4F38] block font-medium">Eco-Points Diperoleh:</span>
                <span class="text-lg font-extrabold text-[#168A5B]">+{{ number_format($order->points_earned) }} Pts</span>
            </div>
        </div>

        <!-- Environmental Impact Certificate Proof (PRD Spec) -->
        <div class="p-5 rounded-2xl gradient-card text-white flex items-center justify-between shadow-md">
            <div class="space-y-1">
                <div class="flex items-center gap-1.5 text-xs text-emerald-200 font-bold uppercase tracking-wider">
                    <i data-lucide="award" class="w-4 h-4 text-emerald-300"></i> Sertifikat Penghematan Emisi Industri
                </div>
                <h4 class="text-lg font-bold">Menghemat {{ $order->co2_saved_kg }} kg Emisi CO₂e</h4>
                <p class="text-xs text-emerald-100/80">Dengan membeli bahan baku olahan daur ulang, Anda mencegah emisi karbon proses ekstraksi virgin material.</p>
            </div>
            <div class="text-4xl hidden sm:block">
                🌱
            </div>
        </div>

        <!-- Itemized Table -->
        <div class="space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Rincian Barang yang Dipesan</h4>
            
            <div class="border border-gray-100 rounded-2xl overflow-hidden">
                <table class="w-full text-xs text-left">
                    <thead class="bg-[#F7F8F6] text-gray-500 font-semibold border-b border-gray-100">
                        <tr>
                            <th class="p-3">Nama Material</th>
                            <th class="p-3">Spesifikasi</th>
                            <th class="p-3 text-right">Harga / kg</th>
                            <th class="p-3 text-right">Kuantitas</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="p-3 font-bold text-[#1B211E]">{{ $item['name'] }}</td>
                                <td class="p-3 text-gray-500">{{ $item['grade'] }}</td>
                                <td class="p-3 text-right tabular-nums">Rp {{ number_format($item['price']) }}</td>
                                <td class="p-3 text-right font-bold tabular-nums">{{ $item['qty_kg'] }} kg</td>
                                <td class="p-3 text-right font-extrabold text-[#0B4F38] tabular-nums">Rp {{ number_format($item['total']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary & Logistics Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
            <div class="space-y-2 text-xs">
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Alamat Pengiriman Armada</h4>
                <p class="text-gray-700 leading-relaxed font-medium bg-[#F7F8F6] p-3 rounded-xl border border-gray-100">
                    {{ $order->shipping_address }}
                </p>
                <p class="text-[11px] text-gray-500">Metode Pembayaran: <strong>{{ $order->payment_method }}</strong></p>
            </div>

            <div class="space-y-2 text-xs divide-y divide-gray-100">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-gray-500">Subtotal:</span>
                    <span class="font-bold text-gray-800 tabular-nums">Rp {{ number_format($order->subtotal) }}</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500">PPN 10%:</span>
                    <span class="font-bold text-gray-800 tabular-nums">Rp {{ number_format($order->ppn_amount) }}</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500">Ongkos Truk Logistik:</span>
                    <span class="font-bold text-gray-800 tabular-nums">Rp {{ number_format($order->shipping_fee) }}</span>
                </div>
                <div class="flex items-center justify-between pt-2.5 text-sm">
                    <span class="font-extrabold text-[#0B4F38]">Total Pembayaran Lunas:</span>
                    <span class="font-extrabold text-[#168A5B] tabular-nums">Rp {{ number_format($order->grand_total) }}</span>
                </div>
            </div>
        </div>

        <!-- Bottom Action -->
        <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
            <button onclick="window.print()" class="py-2.5 px-4 rounded-xl border border-[#DDE3DF] hover:bg-gray-50 text-xs font-bold text-gray-700 flex items-center gap-1.5 transition">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak Dokumen Invoice
            </button>
            <a href="{{ route('marketplace.index') }}" class="py-2.5 px-5 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-xs font-bold transition shadow-sm">
                Kembali ke Marketplace
            </a>
        </div>

    </div>

</div>
@endsection
