@extends('layouts.app')

@section('title', 'Pesanan Saya & Pelacakan Pengiriman — OLARA')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Page Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400">Beranda</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('marketplace.index') }}" class="hover:text-primary-600 dark:hover:text-primary-400">Marketplace</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900 dark:text-white font-medium">Pelacakan Pesanan</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-2.5">
                <i data-lucide="package-check" class="w-7 h-7 text-primary-600 dark:text-primary-400"></i>
                Pesanan & Pelacakan Pengiriman
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Pantau proses kurir pengiriman material daur ulang dan lakukan konfirmasi penerimaan barang seperti di Shopee.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition">
                <i data-lucide="store" class="w-4 h-4 text-emerald-600"></i>
                Katalog Bahan Baku
            </a>
        </div>
    </div>

    <!-- Shopee-style Status Tabs Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-2 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-1 sm:gap-2 overflow-x-auto scrollbar-none">
            <!-- Tab: Semua -->
            <a href="{{ route('marketplace.orders', ['status' => 'all']) }}"
               class="flex-1 min-w-[110px] text-center px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap {{ $status === 'all' ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Semua
                <span class="ml-1.5 px-1.5 py-0.5 text-[10px] rounded-full {{ $status === 'all' ? 'bg-primary-700 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                    {{ $counts['all'] }}
                </span>
            </a>

            <!-- Tab: Diproses / Dikemas -->
            <a href="{{ route('marketplace.orders', ['status' => 'diproses']) }}"
               class="flex-1 min-w-[120px] text-center px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap {{ $status === 'diproses' ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Dikemas
                @if($counts['diproses'] > 0)
                    <span class="ml-1.5 px-1.5 py-0.5 text-[10px] rounded-full {{ $status === 'diproses' ? 'bg-primary-700 text-white' : 'bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 font-bold' }}">
                        {{ $counts['diproses'] }}
                    </span>
                @endif
            </a>

            <!-- Tab: Dikirim (Dalam Perjalanan) -->
            <a href="{{ route('marketplace.orders', ['status' => 'dikirim']) }}"
               class="flex-1 min-w-[120px] text-center px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap {{ $status === 'dikirim' ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Dikirim
                @if($counts['dikirim'] > 0)
                    <span class="ml-1.5 px-1.5 py-0.5 text-[10px] rounded-full {{ $status === 'dikirim' ? 'bg-primary-700 text-white' : 'bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-bold' }}">
                        {{ $counts['dikirim'] }}
                    </span>
                @endif
            </a>

            <!-- Tab: Sampai di Lokasi -->
            <a href="{{ route('marketplace.orders', ['status' => 'sampai']) }}"
               class="flex-1 min-w-[120px] text-center px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap {{ $status === 'sampai' ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Sampai
                @if($counts['sampai'] > 0)
                    <span class="ml-1.5 px-1.5 py-0.5 text-[10px] rounded-full {{ $status === 'sampai' ? 'bg-primary-700 text-white' : 'bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 font-bold' }}">
                        {{ $counts['sampai'] }}
                    </span>
                @endif
            </a>

            <!-- Tab: Selesai -->
            <a href="{{ route('marketplace.orders', ['status' => 'selesai']) }}"
               class="flex-1 min-w-[120px] text-center px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap {{ $status === 'selesai' ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Selesai
                @if($counts['selesai'] > 0)
                    <span class="ml-1.5 px-1.5 py-0.5 text-[10px] rounded-full {{ $status === 'selesai' ? 'bg-primary-700 text-white' : 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300' }}">
                        {{ $counts['selesai'] }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition hover:shadow-md">
                <!-- Card Header: Partner Store & Status -->
                <div class="px-5 py-3.5 bg-gray-50/80 dark:bg-gray-750 border-b border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-md bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center">
                            <i data-lucide="store" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-xs font-bold text-gray-900 dark:text-white">OLARA Circular Hub</span>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <span class="font-mono text-xs text-gray-500 dark:text-gray-400">#{{ $order->order_number }}</span>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <span class="text-[11px] text-gray-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>

                    <!-- Shipping & Payment Status Pills -->
                    <div class="flex items-center gap-2">
                        @if($order->isPending())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Menunggu Pembayaran
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Lunas
                            </span>
                        @endif

                        @if($order->shipping_status === 'diproses')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Dikemas
                            </span>
                        @elseif($order->shipping_status === 'dikirim')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                Dikirim
                            </span>
                        @elseif($order->shipping_status === 'sampai')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-950/80 dark:text-purple-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                Sampai
                            </span>
                        @elseif($order->shipping_status === 'selesai')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                                <i data-lucide="check" class="w-3 h-3"></i> Selesai
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Product Body -->
                @php
                    $firstItem = $order->items[0] ?? null;
                    $itemName = $firstItem['name'] ?? ($order->product->name ?? 'Material Daur Ulang');
                    $itemCategory = $order->product->category ?? 'Material Olahan';
                    $itemGrade = $firstItem['grade'] ?? ($order->product->grade ?? 'Standar Industri');
                    $itemPrice = $firstItem['price'] ?? ($order->price_per_kg ?? 0);
                    $itemQty = $firstItem['qty_kg'] ?? ($order->quantity_kg ?? 50);
                    $grandTotal = $order->grand_total ?? $order->total_price;
                @endphp
                <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            @if(str_contains(strtolower($itemCategory), 'plastik'))
                                <i data-lucide="recycle" class="w-8 h-8"></i>
                            @elseif(str_contains(strtolower($itemCategory), 'kertas'))
                                <i data-lucide="package" class="w-8 h-8"></i>
                            @elseif(str_contains(strtolower($itemCategory), 'logam'))
                                <i data-lucide="box" class="w-8 h-8"></i>
                            @else
                                <i data-lucide="leaf" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded">
                                    {{ $itemCategory }}
                                </span>
                                <span class="text-[10px] font-mono text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">
                                    {{ $itemGrade }}
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white hover:text-primary-600 transition">
                                <a href="{{ route('marketplace.orderDetail', $order->order_number) }}">
                                    {{ $itemName }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Kuantitas: <span class="font-bold text-gray-800 dark:text-gray-200">{{ number_format($itemQty) }} kg</span>
                                &times; Rp {{ number_format($itemPrice) }}/kg
                            </p>
                        </div>
                    </div>

                    <div class="text-left sm:text-right w-full sm:w-auto">
                        <span class="text-[11px] text-gray-400 block font-medium">Total Tagihan:</span>
                        <div class="text-lg sm:text-xl font-extrabold text-emerald-700 dark:text-emerald-400 tabular-nums">
                            Rp {{ number_format($grandTotal) }}
                        </div>
                        <div class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-full mt-1">
                            <span>+{{ number_format($order->points_earned ?? $order->eco_points_earned ?? 0) }} Pts</span>
                        </div>
                    </div>
                </div>

                <!-- Shopee-style Live Tracking Strip -->
                <div class="px-5 py-3.5 bg-gray-50/50 dark:bg-gray-750/50 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900 dark:text-white">{{ $order->courier_name ?? 'Kurir Logistik' }}</span>
                                <span class="text-gray-400 font-mono">No. Resi: <strong>{{ $order->tracking_number ?? '-' }}</strong></span>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}'); alert('Nomor Resi disalin ke papan klip!');" class="text-primary-600 hover:text-primary-700 text-[11px] font-semibold" title="Salin No. Resi">
                                    Salin
                                </button>
                            </div>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                @if($order->shipping_status === 'diproses')
                                    Penjual sedang menyiapkan material dan menjadwalkan pick-up kurir.
                                @elseif($order->shipping_status === 'dikirim')
                                    Paket sedang dalam perjalanan via {{ $order->courier_name }}. Estimasi tiba: <strong class="text-gray-800 dark:text-gray-200">{{ $order->estimated_delivery_date ? $order->estimated_delivery_date->translatedFormat('d M Y') : '1-2 hari kerja' }}</strong>.
                                @elseif($order->shipping_status === 'sampai')
                                    <span class="text-purple-700 dark:text-purple-300 font-semibold">Kurir telah tiba di alamat tujuan. Harap periksa barang Anda dan klik tombol konfirmasi di bawah.</span>
                                @elseif($order->shipping_status === 'selesai')
                                    <span class="text-emerald-700 dark:text-emerald-400 font-semibold">Pesanan telah diterima oleh {{ $order->recipient_name ?? 'pembeli' }} pada {{ $order->completed_at ? $order->completed_at->translatedFormat('d M Y, H:i') : '-' }}.</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Status Simulation Tools for Demo Testing -->
                    <div class="flex items-center gap-2 ml-auto md:ml-0">
                        <form action="{{ route('marketplace.updateStatus', $order->order_number) }}" method="POST" class="flex items-center gap-1.5">
                            @csrf
                            <label class="text-[10px] text-gray-400 hidden lg:inline">Simulasi Status:</label>
                            <select name="shipping_status" onchange="this.form.submit()" class="text-[11px] font-medium py-1 px-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-1 focus:ring-primary-500">
                                <option value="diproses" {{ $order->shipping_status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikirim" {{ $order->shipping_status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="sampai" {{ $order->shipping_status === 'sampai' ? 'selected' : '' }}>Sampai</option>
                                <option value="selesai" {{ $order->shipping_status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Shopee-style Card Footer Actions -->
                <div class="p-4 bg-white dark:bg-gray-800 flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        @if($order->shipping_status === 'selesai')
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-semibold">
                                <i data-lucide="award" class="w-3.5 h-3.5"></i> +{{ number_format($order->eco_points_earned) }} Poin telah masuk ke dompet Anda!
                            </span>
                        @elseif($order->shipping_status === 'sampai')
                            <span class="text-purple-600 dark:text-purple-400 font-medium">
                                Silakan klik <strong>Pesanan Diterima</strong> jika material sudah sampai dengan lengkap.
                            </span>
                        @else
                            <span>Hubungi customer care jika paket mengalami kendala logistik.</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 ml-auto">
                        @if($order->isPending())
                            <a href="{{ route('marketplace.orderDetail', $order->order_number) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md transition">
                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                Bayar Sekarang
                            </a>
                        @endif

                        <!-- Detail & Tracking Stepper Button -->
                        <a href="{{ route('marketplace.orderDetail', $order->order_number) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-200 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition">
                            <i data-lucide="map" class="w-3.5 h-3.5"></i>
                            Lacak Lengkap
                        </a>

                        <!-- SHOPEE STYLE: Konfirmasi Barang Sudah Sampai / Pesanan Diterima -->
                        @if(in_array($order->shipping_status, ['dikirim', 'sampai']))
                            <form action="{{ route('marketplace.confirmDelivery', $order->order_number) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa barang pesanan #{{ $order->order_number }} sudah Anda terima dalam kondisi baik? Saldo reward eco-points akan segera dikreditkan.');">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-500/20 hover:scale-[1.02] active:scale-[0.98] transition">
                                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                    Pesanan Diterima (Sudah Sampai)
                                </button>
                            </form>
                        @elseif($order->shipping_status === 'selesai')
                            <button type="button" disabled class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-gray-400 bg-gray-100 dark:bg-gray-700/50 cursor-not-allowed">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                Sudah Diterima
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center border border-gray-200 dark:border-gray-700 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 flex items-center justify-center mx-auto">
                    <i data-lucide="package-x" class="w-8 h-8"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Tidak ada pesanan di tab ini</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-sm mx-auto mt-1">
                        @if($status === 'all')
                            Anda belum pernah melakukan pemesanan bahan baku daur ulang.
                        @else
                            Tidak ada pesanan dengan status <span class="font-bold uppercase">"{{ $status }}"</span> saat ini.
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 text-white text-xs font-bold hover:bg-primary-700 shadow-sm transition">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                        Belanja Bahan Baku Sekarang
                    </a>
                </div>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="pt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
