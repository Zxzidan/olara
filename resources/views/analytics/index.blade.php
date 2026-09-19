@extends('layouts.app')

@section('title', 'Laporan Dampak Ekologis & Jejak Karbon — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 space-y-6 sm:space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full">
                Analisis Lingkungan Terukur
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1B211E] tracking-tight mt-1">
                Laporan Dampak Ekologis & Jejak Karbon
            </h1>
            <p class="text-sm text-[#66716B] mt-1">
                Pantau akumulasi kontribusi nyata Anda terhadap penurunan emisi gas rumah kaca dan konservasi sumber daya bumi.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-500 bg-white border border-[#DDE3DF] px-3 py-1.5 rounded-xl shadow-xs">
                Periode: Bulan Berjalan (September 2026)
            </span>
        </div>
    </div>

    <!-- 4 Key Environmental KPI Cards (PRD Section G) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Metric 1: Total Sampah -->
        <div class="bg-white border border-[#DDE3DF] rounded-3xl p-5 sm:p-6 shadow-sm space-y-2">
            <div class="w-10 h-10 rounded-xl bg-[#EEF9F2] text-[#168A5B] flex items-center justify-center font-bold">
                <i data-lucide="package" class="w-5 h-5"></i>
            </div>
            <span class="text-xs text-[#66716B] font-medium block">Total Sampah Terkelola</span>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-[#0B4F38] tabular-nums">{{ $totalWeightKg }}</h3>
                <span class="text-sm font-bold text-gray-500">kg</span>
            </div>
            <span class="text-[11px] text-[#168A5B] font-bold flex items-center gap-1">
                ↑ +14.2% vs bulan sebelumnya
            </span>
        </div>

        <!-- Metric 2: Emisi Karbon Dicegah -->
        <div class="bg-white border border-[#DDE3DF] rounded-3xl p-5 sm:p-6 shadow-sm space-y-2">
            <div class="w-10 h-10 rounded-xl bg-[#EEF9F2] text-[#168A5B] flex items-center justify-center font-bold">
                <i data-lucide="leaf" class="w-5 h-5"></i>
            </div>
            <span class="text-xs text-[#66716B] font-medium block">Emisi Karbon Dicegah</span>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-[#0B4F38] tabular-nums">{{ $totalCo2AvoidedKg }}</h3>
                <span class="text-sm font-bold text-gray-500">kg CO₂e</span>
            </div>
            <span class="text-[11px] text-gray-500 font-medium">
                Setara mematikan 18 lampu selama 1 tahun
            </span>
        </div>

        <!-- Metric 3: Pohon Diselamatkan -->
        <div class="bg-white border border-[#DDE3DF] rounded-3xl p-5 sm:p-6 shadow-sm space-y-2">
            <div class="w-10 h-10 rounded-xl bg-[#EEF9F2] text-[#168A5B] flex items-center justify-center font-bold">
                <i data-lucide="trees" class="w-5 h-5"></i>
            </div>
            <span class="text-xs text-[#66716B] font-medium block">Setara Pohon Dewasa</span>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-[#0B4F38] tabular-nums">{{ $treesSaved }}</h3>
                <span class="text-sm font-bold text-gray-500">Pohon</span>
            </div>
            <span class="text-[11px] text-emerald-600 font-medium">
                Hasil dari daur ulang material selulosa & kayu
            </span>
        </div>

        <!-- Metric 4: Air & Energi -->
        <div class="bg-white border border-[#DDE3DF] rounded-3xl p-5 sm:p-6 shadow-sm space-y-2">
            <div class="w-10 h-10 rounded-xl bg-[#EEF9F2] text-[#168A5B] flex items-center justify-center font-bold">
                <i data-lucide="droplet" class="w-5 h-5"></i>
            </div>
            <span class="text-xs text-[#66716B] font-medium block">Konservasi Air Bersih</span>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-[#0B4F38] tabular-nums">{{ $waterSavedLiters }}</h3>
                <span class="text-sm font-bold text-gray-500">Liter</span>
            </div>
            <span class="text-[11px] text-gray-500 font-medium">
                Efisiensi dari pengurangan proses virgin material
            </span>
        </div>

    </div>

    <!-- Charts Section (2 Columns: Weekly Trend Bar Chart & Material Composition Donut) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
        
        <!-- Left 7 Cols: Weekly Trend Bar Chart -->
        <div class="lg:col-span-7 bg-white border border-[#DDE3DF] rounded-3xl p-4 sm:p-6 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-[#1B211E]">Tren Penyetoran Sampah Mingguan</h3>
                    <p class="text-xs text-[#66716B]">Grafik berat material terpilah harian (Senin - Minggu)</p>
                </div>
                <span class="text-xs font-bold text-[#168A5B] bg-[#EEF9F2] px-2.5 py-1 rounded-lg self-start sm:self-auto">
                    Rata-rata: 21.2 kg / hari
                </span>
            </div>

            <div class="relative h-64 w-full">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        <!-- Right 5 Cols: Material Composition Donut Chart & Purity Meter -->
        <div class="lg:col-span-5 bg-white border border-[#DDE3DF] rounded-3xl p-4 sm:p-6 shadow-sm space-y-6">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-[#1B211E]">Komposisi Material Terpilah</h3>
                <p class="text-xs text-[#66716B]">Distribusi kategori sampah yang Anda kelola</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-6">
                <div class="w-36 h-36 relative shrink-0">
                    <canvas id="compositionChart"></canvas>
                </div>

                <div class="space-y-2 w-full sm:flex-1 text-xs">
                    @foreach($composition as $item)
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-1.5 text-gray-700">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $item['color'] }}"></span>
                                {{ $item['name'] }}
                            </span>
                            <span class="font-extrabold text-[#1B211E] tabular-nums">{{ $item['percent'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Average Contamination Quality Meter -->
            <div class="pt-4 border-t border-gray-100 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-[#1B211E]">Tingkat Kebersihan Rata-Rata:</span>
                    <span class="font-extrabold text-[#168A5B]">{{ $purityScore }}% (Sangat Bersih)</span>
                </div>
                <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full" style="width: {{ $purityScore }}%"></div>
                </div>
                <p class="text-[11px] text-gray-500">
                    Tingkat kontaminasi Anda hanya {{ $avgContamination }}%. Kebersihan yang tinggi membuat harga jual daur ulang Anda berada di tingkatan tertinggi.
                </p>
            </div>
        </div>

    </div>

    <!-- Personalized Educative Recommendations (PRD Section 7) -->
    <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full">
                Rekomendasi Berbasis Data
            </span>
            <h3 class="text-lg font-extrabold text-[#1B211E] mt-2">Saran Praktis untuk Memaksimalkan Nilai Daur Ulang</h3>
            <p class="text-xs text-[#66716B]">AI OLARA menganalisis pola penyetoran Anda untuk memberikan panduan yang langsung berdampak pada insentif poin.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            @foreach($recommendations as $rec)
                <div class="p-5 rounded-2xl border border-[#DDE3DF] bg-[#F7F8F6] space-y-2.5 hover:border-[#168A5B] transition">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#168A5B] bg-white border border-[#BFE7D0] px-2 py-0.5 rounded">
                        {{ $rec['tag'] }}
                    </span>
                    <h4 class="text-sm font-bold text-[#1B211E]">{{ $rec['title'] }}</h4>
                    <p class="text-xs text-[#66716B] leading-relaxed">{{ $rec['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Weekly Trend Bar Chart
        const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
        const weeklyData = @json($weeklyTrend);

        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: weeklyData.map(d => d.day),
                datasets: [{
                    label: 'Berat Sampah (kg)',
                    data: weeklyData.map(d => d.weight),
                    backgroundColor: '#168A5B',
                    borderRadius: 8,
                    hoverBackgroundColor: '#0F6B47',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F3' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Composition Donut Chart
        const compCtx = document.getElementById('compositionChart').getContext('2d');
        const compData = @json($composition);

        new Chart(compCtx, {
            type: 'doughnut',
            data: {
                labels: compData.map(d => d.name),
                datasets: [{
                    data: compData.map(d => d.percent),
                    backgroundColor: compData.map(d => d.color),
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush
@endsection
