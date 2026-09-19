@extends('layouts.app')

@section('title', 'Marketplace Bahan Baku Daur Ulang — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 space-y-6 sm:space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full">
                Katalog Bahan Baku Daur Ulang Industri
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1B211E] tracking-tight mt-1">
                Marketplace Bahan Baku Daur Ulang
            </h1>
            <p class="text-sm text-[#66716B] mt-1">
                Penyedia material olahan daur ulang berkualitas (PET Flakes, Bubur Kertas, Ingot Aluminium) untuk UMKM & industri manufaktur.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Order Tracking Dropdown Button -->
            <div class="relative">
                <button
                    id="orderTrackingDropdownBtn"
                    data-dropdown-toggle="order-tracking-dropdown"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-600/20 transition group"
                >
                    <i data-lucide="truck" class="w-4 h-4 text-emerald-200 group-hover:scale-110 transition-transform"></i>
                    <span>Pelacakan Pesanan</span>
                    @if($userOrders->whereIn('shipping_status', ['dikirim', 'sampai'])->count() > 0)
                        <span class="w-2 h-2 rounded-full bg-amber-300 animate-ping"></span>
                    @endif
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-emerald-200"></i>
                </button>

                <!-- Dropdown Menu -->
                <div
                    id="order-tracking-dropdown"
                    class="hidden z-50 my-2 w-[calc(100vw-2rem)] sm:w-96 max-w-sm right-0 sm:right-auto text-base list-none bg-white rounded-2xl divide-y divide-gray-100 shadow-2xl border border-gray-100 dark:bg-gray-800 dark:divide-gray-700 dark:border-gray-700"
                >
                    <div class="py-3 px-4 flex items-center justify-between bg-gray-50 dark:bg-gray-750 rounded-t-2xl">
                        <span class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                            <i data-lucide="package-check" class="w-4 h-4 text-emerald-600"></i>
                            Pelacakan Pengiriman Pesanan
                        </span>
                        <a href="{{ route('marketplace.orders') }}" class="text-[11px] font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400">
                            Semua →
                        </a>
                    </div>

                    <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($userOrders as $order)
                            <a href="{{ route('marketplace.orderDetail', $order->order_number) }}" class="block p-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/60 transition group">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="font-mono text-[11px] font-bold text-gray-800 dark:text-gray-200 group-hover:text-primary-600">
                                        #{{ $order->order_number }}
                                    </span>
                                    @if($order->shipping_status === 'diproses')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                            Dikemas
                                        </span>
                                    @elseif($order->shipping_status === 'dikirim')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 animate-pulse">
                                            Dikirim
                                        </span>
                                    @elseif($order->shipping_status === 'sampai')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300">
                                            Sampai
                                        </span>
                                    @elseif($order->shipping_status === 'selesai')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs font-semibold text-gray-900 dark:text-white line-clamp-1">
                                    {{ $order->product->name ?? 'Bahan Baku Daur Ulang' }}
                                </p>
                                <div class="flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                    <span>{{ $order->courier_name ?? 'Kurir' }}: <strong class="font-mono text-gray-700 dark:text-gray-300">{{ $order->tracking_number ?? '-' }}</strong></span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($order->total_price) }}</span>
                                </div>
                                @if(in_array($order->shipping_status, ['dikirim', 'sampai']))
                                    <div class="mt-2 text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/60 px-2 py-1 rounded-lg flex items-center gap-1.5">
                                        <i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>
                                        <span>Barang sudah sampai? Klik untuk konfirmasi penerimaan.</span>
                                    </div>
                                @endif
                            </a>
                        @empty
                            <div class="py-6 text-center text-xs text-gray-400">
                                Belum ada riwayat pesanan bahan baku.
                            </div>
                        @endforelse
                    </div>

                    <div class="p-2.5 bg-gray-50 dark:bg-gray-750 text-center rounded-b-2xl">
                        <a href="{{ route('marketplace.orders') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline">
                            <i data-lucide="package" class="w-3.5 h-3.5"></i>
                            Buka Halaman Pelacakan Pesanan & Status Lengkap
                        </a>
                    </div>
                </div>
            </div>

            <span class="text-xs font-bold text-[#0B4F38] bg-[#EEF9F2] border border-[#BFE7D0] px-3.5 py-2 rounded-2xl flex items-center gap-2 shadow-xs">
                <i data-lucide="shield-check" class="w-4 h-4 text-[#168A5B]"></i> Standar Kualitas Industri Terverifikasi
            </span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#DDE3DF] pb-4">
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1">
            <a href="{{ route('marketplace.index', ['category' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'all' ? 'bg-[#168A5B] text-white shadow-sm' : 'bg-white text-[#66716B] border border-[#DDE3DF] hover:bg-gray-50' }}">
                Semua Kategori
            </a>
            <a href="{{ route('marketplace.index', ['category' => 'Plastik']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'Plastik' ? 'bg-[#168A5B] text-white shadow-sm' : 'bg-white text-[#66716B] border border-[#DDE3DF] hover:bg-gray-50' }}">
                Plastik Olahan
            </a>
            <a href="{{ route('marketplace.index', ['category' => 'Kertas']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'Kertas' ? 'bg-[#168A5B] text-white shadow-sm' : 'bg-white text-[#66716B] border border-[#DDE3DF] hover:bg-gray-50' }}">
                Kertas & Pulp
            </a>
            <a href="{{ route('marketplace.index', ['category' => 'Logam']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'Logam' ? 'bg-[#168A5B] text-white shadow-sm' : 'bg-white text-[#66716B] border border-[#DDE3DF] hover:bg-gray-50' }}">
                Aluminium & Logam
            </a>
            <a href="{{ route('marketplace.index', ['category' => 'Tekstil']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategory === 'Tekstil' ? 'bg-[#168A5B] text-white shadow-sm' : 'bg-white text-[#66716B] border border-[#DDE3DF] hover:bg-gray-50' }}">
                Tekstil Perca
            </a>
        </div>

        <form action="{{ route('marketplace.index') }}" method="GET" class="relative">
            <input type="hidden" name="category" value="{{ $selectedCategory }}" />
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama bahan baku..." class="pl-9 pr-4 py-1.5 rounded-xl border border-[#DDE3DF] text-xs focus:outline-none focus:ring-2 focus:ring-[#168A5B]" />
        </form>
    </div>

    <!-- Product Grid (2 or 3 Columns) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white border border-[#DDE3DF] hover:border-[#168A5B] rounded-3xl p-6 shadow-sm flex flex-col justify-between transition group hover:shadow-md space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-0.5 rounded-full">
                            {{ $product->category }}
                        </span>
                        <span class="text-xs font-mono font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                            {{ $product->grade }}
                        </span>
                    </div>

                    <div>
                        <h4 class="text-base font-extrabold text-[#1B211E] group-hover:text-[#168A5B] transition line-clamp-1">
                            {{ $product->name }}
                        </h4>
                        <p class="text-xs text-[#66716B] mt-1.5 leading-relaxed line-clamp-3">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Environmental Carbon Saving Badge -->
                    <div class="p-2.5 rounded-xl bg-[#EEF9F2] border border-[#BFE7D0] flex items-center gap-2 text-[11px] text-[#0B4F38] font-medium">
                        <i data-lucide="leaf" class="w-4 h-4 text-[#168A5B] shrink-0"></i>
                        <span>Hemat <strong>{{ $product->co2_savings_per_kg }} kg CO₂e</strong> per kg dibanding material virgin</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <div>
                            <span class="text-[10px] text-gray-400 block font-medium">Harga / kg:</span>
                            <span class="text-lg font-extrabold text-[#0B4F38] tabular-nums">
                                Rp {{ number_format($product->price_per_kg) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-gray-400 block font-medium">Min. Order:</span>
                            <span class="font-bold text-gray-700 tabular-nums">
                                {{ $product->min_order_kg }} kg
                            </span>
                        </div>
                    </div>

                    <button type="button" onclick="openOrderModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->grade }}', {{ $product->price_per_kg }}, {{ $product->min_order_kg }})" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-[#168A5B] to-[#0F6B47] hover:from-[#0F6B47] hover:to-[#0B4F38] text-white text-xs font-extrabold shadow-md shadow-emerald-600/20 hover:scale-[1.02] active:scale-[0.98] transition flex items-center justify-center gap-2">
                        <i data-lucide="zap" class="w-4 h-4 text-emerald-200"></i> Beli Sekarang
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Marketplace Orders -->
    @if($userOrders->isNotEmpty())
        <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4 mt-8">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-[#1B211E]">Riwayat Pesanan Material Anda</h3>
                <a href="{{ route('marketplace.orders') }}" class="text-xs font-bold text-[#168A5B] hover:underline flex items-center gap-1">
                    Lihat Semua Pesanan <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            
            <div class="divide-y divide-gray-100">
                @foreach($userOrders as $order)
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 first:pt-0 last:pb-0">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono font-bold text-[#168A5B]">#{{ $order->order_number }}</span>
                                @if($order->isPaid())
                                    <span class="text-[10px] font-bold uppercase bg-emerald-100 text-[#168A5B] px-2 py-0.5 rounded-full flex items-center gap-1">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i> Lunas (Midtrans)
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold uppercase bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full flex items-center gap-1 animate-pulse">
                                        <i data-lucide="clock" class="w-3 h-3"></i> Menunggu Pembayaran
                                    </span>
                                @endif
                            </div>
                            <h5 class="text-sm font-bold text-[#1B211E] mt-1">{{ $order->items[0]['name'] ?? 'Bahan Daur Ulang' }} ({{ $order->items[0]['qty_kg'] ?? 50 }} kg)</h5>
                            <p class="text-xs text-gray-400 font-medium">{{ $order->payment_method }} • {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>

                        <div class="text-left sm:text-right flex sm:flex-col items-center sm:items-end justify-between gap-2">
                            <span class="text-sm font-extrabold text-[#0B4F38] tabular-nums">Rp {{ number_format($order->grand_total) }}</span>
                            <div class="flex items-center gap-2">
                                @if(! $order->isPaid())
                                    <button type="button" onclick="payPendingOrder('{{ $order->order_number }}')" class="px-3 py-1 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-bold shadow-xs transition flex items-center gap-1">
                                        <i data-lucide="credit-card" class="w-3 h-3"></i> Bayar Sekarang
                                    </button>
                                @endif
                                <a href="{{ route('marketplace.orderDetail', $order->order_number) }}" class="text-xs font-bold text-[#168A5B] hover:underline">
                                    Detail Invoice →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<!-- Purchase & Checkout Drawer Modal -->
<div id="orderModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border border-[#DDE3DF]">
        <button onclick="closeOrderModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1.5 rounded-full hover:bg-gray-100">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="border-b border-gray-100 pb-3 mb-4">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-0.5 rounded-full">
                    Konfirmasi Checkout & Beli
                </span>
                <span class="text-[11px] font-semibold text-gray-500 flex items-center gap-1">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i> Midtrans Sandbox
                </span>
            </div>
            <h3 id="modalProductName" class="text-lg font-extrabold text-[#1B211E] mt-1">PET Flakes Bening</h3>
            <p id="modalProductGrade" class="text-xs text-[#66716B]">Grade A Hot Washed</p>
        </div>

        <form id="checkoutForm" onsubmit="handleCheckoutSubmit(event)" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId" value="" />

            <!-- Quantity in KG -->
            <div>
                <label for="modalQtyInput" class="block text-xs font-bold text-[#1B211E] mb-1.5">
                    Kuantitas Pesanan (kg):
                </label>
                <div class="flex items-center gap-2">
                    <input type="number" name="quantity_kg" id="modalQtyInput" value="50" min="1" required oninput="recalcOrderModal()" class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-sm font-bold text-[#1B211E] focus:ring-2 focus:ring-[#168A5B] focus:outline-none" />
                    <span class="text-xs font-bold text-gray-500 shrink-0">kg</span>
                </div>
                <span id="modalMinOrderNote" class="text-[11px] text-gray-400 block mt-1">Min. order: 50 kg</span>
            </div>

            <!-- Shipping Address -->
            <div>
                <label for="modalAddress" class="block text-xs font-bold text-[#1B211E] mb-1.5">Alamat Gudang / Pabrik Pengiriman</label>
                <textarea name="shipping_address" id="modalAddress" rows="2" required class="w-full px-4 py-2 rounded-xl border border-[#DDE3DF] text-xs focus:ring-2 focus:ring-[#168A5B] focus:outline-none" placeholder="Masukkan alamat pengiriman lengkap armada truk">{{ $user->address ?? 'Gudang Workshop Olara, Kawasan Industri Hijau, Cikarang' }}</textarea>
            </div>

            <!-- Payment Method Gateway -->
            <div>
                <label class="block text-xs font-bold text-[#1B211E] mb-1.5">Gerbang Pembayaran Resmi</label>
                <div class="p-3 rounded-xl border border-emerald-300 bg-emerald-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#168A5B] text-white flex items-center justify-center font-black text-xs">
                            M
                        </div>
                        <div>
                            <p class="text-xs font-extrabold text-[#0B4F38]">Midtrans Payment Gateway (Sandbox)</p>
                            <p class="text-[11px] text-[#66716B]">QRIS (GoPay/ShopeePay/OVO), VA BCA/Mandiri/BRI/BNI</p>
                        </div>
                    </div>
                    <input type="hidden" name="payment_method" value="Midtrans Digital Gateway" />
                    <span class="text-[10px] font-extrabold uppercase tracking-wide bg-emerald-200/70 text-emerald-900 px-2 py-0.5 rounded">Otomatis</span>
                </div>
            </div>

            <!-- Price Breakdown Calculation (PRD: Subtotal, PPN 10%, Shipping, Total) -->
            <div class="p-4 rounded-2xl bg-[#F7F8F6] border border-[#DDE3DF] space-y-2 text-xs divide-y divide-gray-200">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-gray-600">Subtotal Material:</span>
                    <span id="modalSubtotal" class="font-bold text-gray-800 tabular-nums">Rp 575.000</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-600">Pajak PPN 10%:</span>
                    <span id="modalPpn" class="font-bold text-gray-800 tabular-nums">Rp 57.500</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-600">Ongkos Angkut Truk Armada:</span>
                    <span class="font-bold text-gray-800 tabular-nums">Rp 45.000</span>
                </div>
                <div class="flex items-center justify-between pt-2.5 text-sm">
                    <span class="font-extrabold text-[#0B4F38]">Total Pembayaran:</span>
                    <span id="modalGrandTotal" class="font-extrabold text-[#168A5B] tabular-nums">Rp 677.500</span>
                </div>
            </div>

            <!-- Generated Order Number Preview Alert -->
            <div id="orderStatusBox" class="hidden p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 font-semibold flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 animate-spin"></i>
                <span id="orderStatusMessage">Men-generate nomor order dan menghubungkan ke Midtrans...</span>
            </div>

            <button type="submit" id="btnCheckoutSubmit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-[#168A5B] to-[#0F6B47] hover:from-[#0F6B47] hover:to-[#0B4F38] text-white text-sm font-extrabold shadow-lg shadow-emerald-600/25 hover:scale-[1.01] active:scale-[0.99] transition flex items-center justify-center gap-2">
                <i data-lucide="zap" class="w-4 h-4 text-amber-300"></i>
                <span id="btnCheckoutText">Beli Sekarang & Generate Order</span>
            </button>
            <p class="text-[11px] text-center text-gray-400">
                Mengklik tombol di atas akan langsung menerbitkan nomor pesanan resmi dan memunculkan pop-up Midtrans.
            </p>
        </form>
    </div>
</div>

<script>
    let activePricePerKg = 0;
    let activeMinOrder = 1;

    function openOrderModal(id, name, grade, price, minOrder) {
        activePricePerKg = price;
        activeMinOrder = minOrder;

        document.getElementById('modalProductId').value = id;
        document.getElementById('modalProductName').textContent = name;
        document.getElementById('modalProductGrade').textContent = `Spesifikasi: ${grade}`;
        document.getElementById('modalQtyInput').value = minOrder;
        document.getElementById('modalQtyInput').min = minOrder;
        document.getElementById('modalMinOrderNote').textContent = `Minimum pemesanan: ${minOrder} kg`;

        document.getElementById('orderStatusBox').classList.add('hidden');
        resetSubmitButton();

        recalcOrderModal();
        document.getElementById('orderModal').classList.remove('hidden');
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }

    function recalcOrderModal() {
        const qty = parseFloat(document.getElementById('modalQtyInput').value) || activeMinOrder;
        const subtotal = Math.round(qty * activePricePerKg);
        const ppn = Math.round(subtotal * 0.10);
        const shipping = 45000;
        const grandTotal = subtotal + ppn + shipping;

        document.getElementById('modalSubtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        document.getElementById('modalPpn').textContent = `Rp ${ppn.toLocaleString('id-ID')}`;
        document.getElementById('modalGrandTotal').textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    }

    function resetSubmitButton() {
        const btn = document.getElementById('btnCheckoutSubmit');
        const text = document.getElementById('btnCheckoutText');
        btn.disabled = false;
        btn.classList.remove('opacity-60', 'cursor-not-allowed');
        text.textContent = 'Beli Sekarang & Generate Order';
    }

    async function handleCheckoutSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('checkoutForm');
        const btn = document.getElementById('btnCheckoutSubmit');
        const text = document.getElementById('btnCheckoutText');
        const statusBox = document.getElementById('orderStatusBox');
        const statusMsg = document.getElementById('orderStatusMessage');

        btn.disabled = true;
        btn.classList.add('opacity-60', 'cursor-not-allowed');
        text.textContent = 'Menerbitkan Order Number...';

        statusBox.classList.remove('hidden');
        statusMsg.textContent = 'Sedang membuat pesanan resmi dan menyiapkan Midtrans...';

        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('marketplace.checkout') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                alert(data.message || 'Terjadi kendala saat membuat pesanan.');
                resetSubmitButton();
                statusBox.classList.add('hidden');
                return;
            }

            statusMsg.innerHTML = `Nomor Pesanan: <strong>#${data.order_number}</strong> berhasil dibuat! Membuka Midtrans...`;

            // If Snap Token is present and snap SDK is loaded
            if (data.snap_token && window.snap) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        statusMsg.textContent = 'Pembayaran berhasil! Mengalihkan ke invoice pesanan...';
                        fetch(`/marketplace/order/${data.order_number}/mark-paid`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                transaction_status: result.transaction_status || 'settlement',
                                transaction_id: result.transaction_id || '',
                                payment_type: result.payment_type || 'midtrans',
                            })
                        }).finally(() => {
                            window.location.href = data.redirect_url;
                        });
                    },
                    onPending: function(result) {
                        window.location.href = data.redirect_url + '?status=pending';
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal atau dibatalkan.');
                        window.location.href = data.redirect_url;
                    },
                    onClose: function() {
                        // When user closes the modal without completing
                        window.location.href = data.redirect_url;
                    }
                });
            } else {
                // Fallback redirect
                window.location.href = data.redirect_url;
            }

        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan jaringan saat memproses pesanan.');
            resetSubmitButton();
            statusBox.classList.add('hidden');
        }
    }

    async function payPendingOrder(orderNumber) {
        try {
            const res = await fetch(`/marketplace/order/${orderNumber}/snap-token`);
            const data = await res.json();

            if (data.success && data.snap_token && window.snap) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        fetch(`/marketplace/order/${orderNumber}/mark-paid`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                transaction_status: result.transaction_status || 'settlement',
                                transaction_id: result.transaction_id || '',
                                payment_type: result.payment_type || 'midtrans',
                            })
                        }).finally(() => {
                            window.location.reload();
                        });
                    },
                    onPending: function(result) {
                        window.location.reload();
                    },
                    onError: function(result) {
                        alert('Gagal menyelesaikan pembayaran.');
                    },
                    onClose: function() {
                        window.location.reload();
                    }
                });
            } else {
                window.location.href = `/marketplace/order/${orderNumber}`;
            }
        } catch (e) {
            window.location.href = `/marketplace/order/${orderNumber}`;
        }
    }
</script>
@endsection
