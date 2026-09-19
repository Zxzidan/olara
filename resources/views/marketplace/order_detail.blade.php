@extends('layouts.app')

@section('title', 'Lacak Pesanan #' . $order->order_number . ' — OLARA')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation & Breadcrumbs -->
    <div class="flex items-center justify-between">
        <a href="{{ route('marketplace.orders') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Pesanan
        </a>
        <div class="flex items-center gap-2">
            <span class="text-xs font-mono font-bold text-gray-500 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg border border-gray-200 dark:border-gray-700">
                #{{ $order->order_number }}
            </span>
            <!-- Simulation Dropdown -->
            <form action="{{ route('marketplace.updateStatus', $order->order_number) }}" method="POST" class="flex items-center gap-1">
                @csrf
                <select name="shipping_status" onchange="this.form.submit()" class="text-[11px] font-semibold py-1 px-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-1 focus:ring-primary-500">
                    <option value="diproses" {{ $order->shipping_status === 'diproses' ? 'selected' : '' }}>Simulasi: Diproses</option>
                    <option value="dikirim" {{ $order->shipping_status === 'dikirim' ? 'selected' : '' }}>Simulasi: Dikirim</option>
                    <option value="sampai" {{ $order->shipping_status === 'sampai' ? 'selected' : '' }}>Simulasi: Sampai</option>
                    <option value="selesai" {{ $order->shipping_status === 'selesai' ? 'selected' : '' }}>Simulasi: Selesai</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Shopee-style Tracking Stepper Card -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200 dark:border-gray-700 space-y-8">
        
        <!-- Order Status Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-6">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    @if($order->shipping_status === 'diproses')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            SEDANG DIKEMAS PENJUAL
                        </span>
                    @elseif($order->shipping_status === 'dikirim')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                            DALAM PENGIRIMAN KURIR
                        </span>
                    @elseif($order->shipping_status === 'sampai')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800 dark:bg-purple-950/80 dark:text-purple-300">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            SUDAH SAMPAI DI TUJUAN
                        </span>
                    @elseif($order->shipping_status === 'selesai')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                            PESANAN SELESAI
                        </span>
                    @endif
                    <span class="text-xs text-gray-400 font-mono">{{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white">
                    Status Pengiriman Material
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    No. Resi: <span class="font-mono font-bold text-gray-800 dark:text-gray-200">{{ $order->tracking_number ?? 'Belum terbit' }}</span>
                    &bull; Kurir: <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $order->courier_name ?? 'Logistik Partner' }}</span>
                </p>
            </div>

            <!-- Confirmation Action (Shopee Style) -->
            <div class="flex flex-col items-end gap-2">
                @if(in_array($order->shipping_status, ['dikirim', 'sampai']))
                    <form action="{{ route('marketplace.confirmDelivery', $order->order_number) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin barang pesanan #{{ $order->order_number }} sudah sampai dan diterima dengan lengkap?');">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl text-xs font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/25 hover:scale-[1.02] active:scale-[0.98] transition">
                            <i data-lucide="check-check" class="w-4 h-4"></i>
                            Konfirmasi Barang Sudah Sampai
                        </button>
                    </form>
                    <span class="text-[11px] text-gray-400 text-right">Klik jika barang sudah Anda terima dengan baik</span>
                @elseif($order->shipping_status === 'selesai')
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-bold">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        Barang Diterima ({{ $order->completed_at ? $order->completed_at->translatedFormat('d M Y') : 'Selesai' }})
                    </div>
                @else
                    <div class="text-xs text-amber-600 dark:text-amber-400 font-semibold bg-amber-50 dark:bg-amber-950/60 px-3 py-2 rounded-xl">
                        ⏳ Menunggu kurir melakukan pick-up
                    </div>
                @endif
            </div>
        </div>

        <!-- Visual Stepper Progress (Ala Shopee) -->
        <div class="relative py-4">
            @php
                $statusOrder = [
                    'diproses' => 1,
                    'dikirim' => 2,
                    'sampai' => 3,
                    'selesai' => 4,
                ];
                $currentStep = $statusOrder[$order->shipping_status] ?? 1;
            @endphp

            <!-- Step Progress Track -->
            <div class="hidden sm:block absolute top-10 left-12 right-12 h-1 bg-gray-200 dark:bg-gray-700 -z-0">
                <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ ($currentStep - 1) * 33.33 }}%;"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 sm:gap-2 relative z-10">
                <!-- Step 1: Pesanan Dibuat / Dikemas -->
                <div class="flex sm:flex-col items-center sm:text-center gap-3 sm:gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md {{ $currentStep >= 1 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 dark:ring-emerald-950' : 'bg-gray-200 text-gray-500 dark:bg-gray-700' }}">
                        @if($currentStep > 1)
                            <i data-lucide="check" class="w-5 h-5"></i>
                        @else
                            <i data-lucide="package" class="w-5 h-5"></i>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-900 dark:text-white">Pesanan Dikemas</div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">Penyiapan material</div>
                        <div class="text-[10px] text-gray-400 font-mono">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <!-- Step 2: Dikirim Kurir -->
                <div class="flex sm:flex-col items-center sm:text-center gap-3 sm:gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md {{ $currentStep >= 2 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 dark:ring-emerald-950' : 'bg-gray-200 text-gray-500 dark:bg-gray-700' }}">
                        @if($currentStep > 2)
                            <i data-lucide="check" class="w-5 h-5"></i>
                        @else
                            <i data-lucide="truck" class="w-5 h-5"></i>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-900 dark:text-white">Dalam Pengiriman</div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">{{ $order->courier_name }}</div>
                        <div class="text-[10px] text-gray-400 font-mono">No: {{ $order->tracking_number }}</div>
                    </div>
                </div>

                <!-- Step 3: Sampai di Lokasi Tujuan -->
                <div class="flex sm:flex-col items-center sm:text-center gap-3 sm:gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md {{ $currentStep >= 3 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 dark:ring-emerald-950' : 'bg-gray-200 text-gray-500 dark:bg-gray-700' }}">
                        @if($currentStep > 3)
                            <i data-lucide="check" class="w-5 h-5"></i>
                        @else
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-900 dark:text-white">Sampai di Lokasi</div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">Tiba di alamat penerima</div>
                        <div class="text-[10px] text-gray-400 font-mono">
                            {{ $order->delivered_at ? $order->delivered_at->format('d/m/Y H:i') : 'Estimasi ' . ($order->estimated_delivery_date ? $order->estimated_delivery_date->format('d/m/Y') : '1-2 hari') }}
                        </div>
                    </div>
                </div>

                <!-- Step 4: Selesai / Dikonfirmasi -->
                <div class="flex sm:flex-col items-center sm:text-center gap-3 sm:gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md {{ $currentStep >= 4 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 dark:ring-emerald-950' : 'bg-gray-200 text-gray-500 dark:bg-gray-700' }}">
                        <i data-lucide="award" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-900 dark:text-white">Pesanan Selesai</div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">+{{ number_format($order->eco_points_earned) }} Poin cair</div>
                        <div class="text-[10px] text-gray-400 font-mono">
                            {{ $order->completed_at ? $order->completed_at->format('d/m/Y H:i') : 'Menunggu Konfirmasi' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Courier Log / Tracking Timeline Box -->
        <div class="bg-gray-50 dark:bg-gray-750 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                <div class="flex items-center gap-2">
                    <i data-lucide="navigation" class="w-4 h-4 text-primary-600 dark:text-primary-400"></i>
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                        Rincian Perjalanan Kurir ({{ $order->courier_name }})
                    </h3>
                </div>
                <button type="button" onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}'); alert('Nomor resi telah disalin!');" class="text-xs font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 flex items-center gap-1">
                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin Resi
                </button>
            </div>

            <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200 dark:before:bg-gray-700">
                @if($order->shipping_status === 'selesai')
                    <div class="relative">
                        <span class="absolute -left-6 top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white dark:ring-gray-750"></span>
                        <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Pesanan Telah Selesai</div>
                        <p class="text-[11px] text-gray-600 dark:text-gray-300 mt-0.5">Barang telah diterima oleh {{ $order->recipient_name ?? 'Penerima' }}. Konfirmasi penyelesaian berhasil.</p>
                        <span class="text-[10px] text-gray-400">{{ $order->completed_at ? $order->completed_at->translatedFormat('d M Y, H:i') : 'Baru saja' }}</span>
                    </div>
                @endif

                @if(in_array($order->shipping_status, ['sampai', 'selesai']))
                    <div class="relative">
                        <span class="absolute -left-6 top-1 w-3 h-3 rounded-full {{ $order->shipping_status === 'sampai' ? 'bg-purple-500 ring-4 ring-purple-100 dark:ring-purple-950' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                        <div class="text-xs font-bold text-gray-900 dark:text-white">Tiba di Alamat Tujuan</div>
                        <p class="text-[11px] text-gray-600 dark:text-gray-300 mt-0.5">Kurir telah tiba di alamat: {{ $order->shipping_address }}. Silakan periksa isi paket.</p>
                        <span class="text-[10px] text-gray-400">{{ $order->delivered_at ? $order->delivered_at->translatedFormat('d M Y, H:i') : 'Hari ini' }}</span>
                    </div>
                @endif

                @if(in_array($order->shipping_status, ['dikirim', 'sampai', 'selesai']))
                    <div class="relative">
                        <span class="absolute -left-6 top-1 w-3 h-3 rounded-full {{ $order->shipping_status === 'dikirim' ? 'bg-blue-500 ring-4 ring-blue-100 dark:ring-blue-950' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                        <div class="text-xs font-bold text-gray-900 dark:text-white">Dalam Perjalanan Menuju Penerima</div>
                        <p class="text-[11px] text-gray-600 dark:text-gray-300 mt-0.5">Paket dibawa oleh armada truk ekspedisi logistik dari Sortir Hub Olara Surabaya menuju kota tujuan.</p>
                        <span class="text-[10px] text-gray-400">{{ $order->created_at->addHours(4)->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                @endif

                <div class="relative">
                    <span class="absolute -left-6 top-1 w-3 h-3 rounded-full {{ $order->shipping_status === 'diproses' ? 'bg-amber-500 ring-4 ring-amber-100 dark:ring-amber-950' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                    <div class="text-xs font-bold text-gray-900 dark:text-white">Pesanan Terverifikasi & Dikemas</div>
                    <p class="text-[11px] text-gray-600 dark:text-gray-300 mt-0.5">Penjual telah menyiapkan material daur ulang dan menempelkan barcode resi pengiriman.</p>
                    <span class="text-[10px] text-gray-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Environmental Impact Proof Banner -->
        <div class="p-5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white flex items-center justify-between shadow-md">
            <div class="space-y-1">
                <div class="flex items-center gap-1.5 text-xs text-emerald-200 font-bold uppercase tracking-wider">
                    <i data-lucide="award" class="w-4 h-4 text-emerald-300"></i> Sertifikat Penghematan Emisi Industri
                </div>
                <h4 class="text-base sm:text-lg font-bold">Menghemat {{ $order->co2_saved_kg }} kg Emisi CO₂e</h4>
                <p class="text-xs text-emerald-100/80">Dengan membeli bahan baku daur ulang, Anda mencegah ekstraksi material mentah dan memajukan sirkularitas.</p>
            </div>
            <div class="text-3xl sm:text-4xl pl-4">
                🌱
            </div>
        </div>

        <!-- Itemized Order Table -->
        <div class="space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Rincian Barang Pesanan</h4>
            
            <div class="border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 font-semibold border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="p-3">Nama Material</th>
                            <th class="p-3">Kategori & Grade</th>
                            <th class="p-3 text-right">Harga / kg</th>
                            <th class="p-3 text-right">Kuantitas</th>
                            <th class="p-3 text-right">Total Tagihan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr>
                            <td class="p-3 font-bold text-gray-900 dark:text-white">
                                {{ $order->product->name ?? 'Material Olahan Daur Ulang' }}
                            </td>
                            <td class="p-3 text-gray-500 dark:text-gray-400">
                                {{ $order->product->category ?? 'Material' }} ({{ $order->product->grade ?? 'Standard' }})
                            </td>
                            <td class="p-3 text-right tabular-nums">Rp {{ number_format($order->price_per_kg) }}</td>
                            <td class="p-3 text-right font-bold tabular-nums">{{ number_format($order->quantity_kg) }} kg</td>
                            <td class="p-3 text-right font-extrabold text-emerald-600 dark:text-emerald-400 tabular-nums">Rp {{ number_format($order->total_price) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Shipping Address & Order Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-gray-700">
            <div class="space-y-2 text-xs">
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Alamat Pengiriman</h4>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-xl border border-gray-100 dark:border-gray-700 space-y-1">
                    <p class="font-bold text-gray-900 dark:text-white">{{ $order->recipient_name ?? Auth::user()->name }}</p>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $order->shipping_address }}</p>
                    <p class="text-[11px] text-gray-400 pt-1">Metode Pembayaran: <strong>{{ $order->payment_method }}</strong></p>
                </div>
            </div>

            <div class="space-y-2 text-xs divide-y divide-gray-100 dark:divide-gray-700">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-gray-500">Subtotal Produk:</span>
                    <span class="font-bold text-gray-800 dark:text-gray-200 tabular-nums">Rp {{ number_format($order->total_price) }}</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500">Ongkos Kirim Logistik:</span>
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">Gratis (Subsidi Eco)</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500">Reward Eco-Points:</span>
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">+{{ number_format($order->eco_points_earned) }} Pts</span>
                </div>
                <div class="flex items-center justify-between pt-2.5 text-sm">
                    <span class="font-extrabold text-gray-900 dark:text-white">Total Tagihan Lunas:</span>
                    <span class="font-black text-emerald-600 dark:text-emerald-400 tabular-nums text-lg">Rp {{ number_format($order->total_price) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
            <button onclick="window.print()" class="py-2.5 px-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1.5 transition">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak Invoice & Resi
            </button>
            <div class="flex items-center gap-2">
                <a href="{{ route('marketplace.orders') }}" class="py-2.5 px-4 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold transition">
                    Lihat Semua Pesanan
                </a>
                @if(in_array($order->shipping_status, ['dikirim', 'sampai']))
                    <form action="{{ route('marketplace.confirmDelivery', $order->order_number) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa barang sudah diterima dengan baik?');">
                        @csrf
                        <button type="submit" class="py-2.5 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                            <i data-lucide="check" class="w-4 h-4"></i> Pesanan Diterima
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
