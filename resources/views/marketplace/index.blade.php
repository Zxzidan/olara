@extends('layouts.app')

@section('title', 'Marketplace Bahan Baku Daur Ulang — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

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

        <div class="flex items-center gap-2">
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
                        <span>🌱</span>
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

                    <button type="button" onclick="openOrderModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->grade }}', {{ $product->price_per_kg }}, {{ $product->min_order_kg }})" class="w-full py-2.5 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-1.5">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i> Beli Material Ini
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Marketplace Orders -->
    @if($userOrders->isNotEmpty())
        <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-4 mt-8">
            <h3 class="text-base font-bold text-[#1B211E]">Riwayat Pesanan Material Anda</h3>
            
            <div class="divide-y divide-gray-100">
                @foreach($userOrders as $order)
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 first:pt-0 last:pb-0">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono font-bold text-[#168A5B]">{{ $order->order_number }}</span>
                                <span class="text-[10px] font-bold uppercase bg-emerald-100 text-[#168A5B] px-2 py-0.5 rounded">Lunas</span>
                            </div>
                            <h5 class="text-sm font-bold text-[#1B211E] mt-1">{{ $order->items[0]['name'] ?? 'Bahan Daur Ulang' }} ({{ $order->items[0]['qty_kg'] ?? 50 }} kg)</h5>
                            <p class="text-xs text-gray-400 font-medium">Metode Pembayaran: {{ $order->payment_method }} • {{ $order->created_at->format('d M Y') }}</p>
                        </div>

                        <div class="text-left sm:text-right flex sm:flex-col items-center sm:items-end justify-between gap-2">
                            <span class="text-sm font-extrabold text-[#0B4F38] tabular-nums">Rp {{ number_format($order->grand_total) }}</span>
                            <a href="{{ route('marketplace.orderDetail', $order->order_number) }}" class="text-xs font-bold text-[#168A5B] hover:underline">
                                Lihat Invoice & Sertifikat CO₂
                            </a>
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
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2 py-0.5 rounded">
                Formulir Pemesanan Material
            </span>
            <h3 id="modalProductName" class="text-lg font-extrabold text-[#1B211E] mt-1">PET Flakes Bening</h3>
            <p id="modalProductGrade" class="text-xs text-[#66716B]">Grade A Hot Washed</p>
        </div>

        <form action="{{ route('marketplace.checkout') }}" method="POST" class="space-y-4">
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

            <!-- Payment Method -->
            <div>
                <label class="block text-xs font-bold text-[#1B211E] mb-1.5">Metode Pembayaran Digital</label>
                <select name="payment_method" class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-xs font-semibold text-[#1B211E] focus:ring-2 focus:ring-[#168A5B] focus:outline-none">
                    <option value="QRIS Instant (GoPay, OVO, DANA)">QRIS Instant (Semua E-Wallet & M-Banking)</option>
                    <option value="BCA Virtual Account">BCA Virtual Account</option>
                    <option value="Mandiri Virtual Account">Mandiri Virtual Account</option>
                    <option value="BRI Virtual Account">BRI Virtual Account</option>
                    <option value="BNI Virtual Account">BNI Virtual Account</option>
                </select>
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

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-sm font-bold shadow-sm transition flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Bayar & Proses Pesanan
            </button>
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

        recalcOrderModal();
        document.getElementById('orderModal').classList.remove('hidden');
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
</script>
@endsection
