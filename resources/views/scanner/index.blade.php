@extends('layouts.app')

@section('title', 'AI Waste Scanner — Deteksi & Valuasi Sampah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header & Intro -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full flex items-center gap-1">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Computer Vision AI v2.4
                </span>
                <span class="text-xs text-[#66716B] font-medium">• Akurasi Model 98.4%</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1B211E] tracking-tight mt-1">
                Kamera AI Pemindai Sampah
            </h1>
            <p class="text-sm text-[#66716B] mt-1">
                Identifikasi jenis material, analisis tingkat kontaminasi, dan dapatkan estimasi valuasi harga pasar secara instan.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-[#0B4F38] bg-[#EEF9F2] border border-[#BFE7D0] px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                <i data-lucide="award" class="w-4 h-4 text-[#168A5B]"></i> Unggah foto timbangan: +10 Poin
            </span>
        </div>
    </div>

    <!-- Main Scanner Workspace (2-Column Split View) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left 7 Cols: Camera / Image Viewfinder & Detection Simulation -->
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-black/90 rounded-3xl overflow-hidden border border-gray-800 shadow-xl relative aspect-[4/3] flex items-center justify-center">
                <!-- Preview Image -->
                <img id="scannerPreviewImg" src="https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=900&auto=format&fit=crop&q=80" alt="Scanner Preview" class="w-full h-full object-cover opacity-90 transition-all duration-300" />

                <!-- HUD Bounding Box Overlay -->
                <div class="absolute inset-8 sm:inset-12 border-2 border-[#10B981] rounded-2xl pointer-events-none flex flex-col justify-between p-3 shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                    <div class="flex items-center justify-between text-xs font-mono text-[#10B981]">
                        <span class="bg-black/60 px-2 py-0.5 rounded backdrop-blur-sm flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-[#10B981] animate-ping"></span> AI TRACKING ACTIVE
                        </span>
                        <span id="hudConfidence" class="bg-black/60 px-2 py-0.5 rounded backdrop-blur-sm">CONF: 98%</span>
                    </div>

                    <!-- Center Scan Line Animation -->
                    <div class="w-full h-0.5 bg-gradient-to-r from-transparent via-[#10B981] to-transparent animate-pulse"></div>

                    <div class="flex items-center justify-between text-[11px] font-mono text-[#10B981]">
                        <span id="hudMaterialTag" class="bg-black/70 px-2.5 py-1 rounded backdrop-blur-sm font-bold text-white">Plastik PET (Grade A)</span>
                        <span id="hudContamination" class="bg-black/60 px-2 py-0.5 rounded backdrop-blur-sm text-emerald-300">KONTAMINASI: 4%</span>
                    </div>
                </div>

                <!-- Bottom Floating Camera Controls -->
                <div class="absolute bottom-4 inset-x-0 flex items-center justify-center gap-4 z-20">
                    <label for="cameraFileInput" class="cursor-pointer bg-white/20 hover:bg-white/30 backdrop-blur-md text-white p-3 rounded-full border border-white/30 transition shadow-md">
                        <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                        <input type="file" id="cameraFileInput" accept="image/*" class="hidden" onchange="handleFileUpload(event)" />
                    </label>

                    <button type="button" onclick="simulateScanAction()" class="bg-[#168A5B] hover:bg-[#0F6B47] text-white px-6 py-3 rounded-full font-bold text-sm shadow-lg flex items-center gap-2 border border-emerald-400/40 transition hover:scale-105">
                        <i data-lucide="camera" class="w-5 h-5"></i> Pindai Objek
                    </button>

                    <button type="button" onclick="switchPresetQuick()" class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white p-3 rounded-full border border-white/30 transition shadow-md" title="Ganti Sample Preset">
                        <i data-lucide="shuffle" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Preset Material Chips for Quick Testing -->
            <div class="bg-white border border-[#DDE3DF] rounded-2xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-xs font-bold text-[#1B211E]">Uji Coba Material Lain (Sample Cepat):</span>
                    <span class="text-[11px] text-[#66716B]">Klik untuk simulasi instan</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($presetMaterials as $idx => $mat)
                        <button type="button" onclick="loadPreset({{ $idx }})" class="preset-chip text-xs px-3 py-1.5 rounded-xl border border-gray-200 hover:border-[#168A5B] hover:bg-[#EEF9F2] text-gray-700 hover:text-[#168A5B] transition font-medium">
                            {{ $mat['name'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right 5 Cols: AI Diagnostic Results & Action Form -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 shadow-sm space-y-6">
                
                <!-- Result Header -->
                <div class="flex items-start justify-between border-b border-gray-100 pb-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2 py-0.5 rounded">
                            Hasil Identifikasi AI
                        </span>
                        <h3 id="resultMaterialTitle" class="text-lg font-extrabold text-[#1B211E] mt-1">
                            Plastik Botol Minuman PET
                        </h3>
                        <p id="resultCategoryText" class="text-xs text-[#66716B]">Kategori: Plastik • Grade A Daur Ulang</p>
                    </div>
                    <div class="text-right">
                        <span id="resultConfidenceBadge" class="inline-flex items-center gap-1 font-bold text-xs text-[#168A5B] bg-[#EEF9F2] px-2.5 py-1 rounded-full border border-[#BFE7D0]">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> 98% Cocok
                        </span>
                    </div>
                </div>

                <!-- Cleanliness & Contamination Gauge Bar -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-[#1B211E]">Tingkat Kontaminasi & Kebersihan:</span>
                        <span id="resultContaminationText" class="font-extrabold text-[#168A5B]">4% (Sangat Bersih)</span>
                    </div>
                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden p-0.5">
                        <div id="contaminationBar" class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all duration-500" style="width: 15%"></div>
                    </div>
                    <p id="resultRecommendation" class="text-xs text-[#66716B] leading-relaxed bg-[#F7F8F6] p-3 rounded-xl border border-gray-100">
                        Kategori Sangat Bersih. Lepaskan tutup botol dan label plastik tipis untuk menaikkan harga jual di tingkat pengepul.
                    </p>
                </div>

                <!-- Live Market Valuation & Weight Form -->
                <form action="{{ route('scanner.save') }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- Hidden AI Fields -->
                    <input type="hidden" name="material_name" id="formMaterialName" value="Plastik Botol Minuman PET" />
                    <input type="hidden" name="category" id="formCategory" value="Plastik" />
                    <input type="hidden" name="confidence_rate" id="formConfidence" value="98" />
                    <input type="hidden" name="contamination_rate" id="formContamination" value="4" />
                    <input type="hidden" name="estimated_price_per_kg" id="formPricePerKg" value="4800" />
                    <input type="hidden" name="recommendation" id="formRecommendation" value="Kondisi sangat bersih." />
                    <input type="hidden" name="action_type" id="formActionType" value="save" />

                    <!-- Valuation Metric Card -->
                    <div class="p-4 rounded-2xl bg-[#EEF9F2] border border-[#BFE7D0] flex items-center justify-between">
                        <div>
                            <span class="text-xs text-[#66716B] font-medium">Estimasi Harga Pasar:</span>
                            <p id="resultPricePerKg" class="text-lg font-extrabold text-[#0B4F38] tabular-nums">
                                Rp 4.800 <span class="text-xs font-semibold text-[#168A5B]">/ kg</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-[#66716B] font-medium">Estimasi Total Nilai:</span>
                            <p id="resultTotalValue" class="text-xl font-extrabold text-[#168A5B] tabular-nums">
                                Rp 24.000
                            </p>
                        </div>
                    </div>

                    <!-- Weight Input Stepper -->
                    <div>
                        <label for="weightInput" class="block text-xs font-bold text-[#1B211E] mb-1.5">
                            Estimasi Berat Sampah Anda (kg):
                        </label>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="adjustWeight(-1)" class="w-10 h-10 rounded-xl border border-[#DDE3DF] bg-white font-bold text-gray-700 hover:bg-gray-100 flex items-center justify-center transition">
                                -
                            </button>
                            <input type="number" step="0.5" min="0.5" name="weight_kg" id="weightInput" value="5.0" oninput="calculateTotalValue()" class="flex-1 text-center py-2.5 rounded-xl border border-[#DDE3DF] text-base font-bold text-[#1B211E] focus:ring-2 focus:ring-[#168A5B] focus:outline-none tabular-nums" />
                            <button type="button" onclick="adjustWeight(1)" class="w-10 h-10 rounded-xl border border-[#DDE3DF] bg-white font-bold text-gray-700 hover:bg-gray-100 flex items-center justify-center transition">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Scale Verification Checkbox (PRD Bonus Rule) -->
                    <div class="p-3 rounded-xl border border-[#DDE3DF] bg-[#F7F8F6] space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="has_scale_photo" id="scalePhotoCheck" class="w-4 h-4 text-[#168A5B] rounded border-gray-300 focus:ring-[#168A5B]" onchange="toggleScaleBonus(this)" />
                            <span class="text-xs font-bold text-[#1B211E]">Unggah Bukti Timbangan Digital (+10 Poin)</span>
                        </label>
                        <p class="text-[11px] text-[#66716B] pl-6">
                            Verifikasi foto angka timbangan untuk meningkatkan akurasi data dan otomatis memperoleh bonus Eco-Point tambahan.
                        </p>
                    </div>

                    <!-- Action Buttons Cluster (PRD Hierarchy: Primary, Secondary, Save) -->
                    <div class="space-y-2 pt-2">
                        <button type="button" onclick="submitScanForm('pickup')" class="w-full py-3 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-sm font-bold shadow-sm transition flex items-center justify-center gap-2">
                            <i data-lucide="truck" class="w-4 h-4"></i> Lanjut Jadwalkan Penjemputan (Pickup)
                        </button>

                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="submitScanForm('dropoff')" class="py-2.5 px-3 rounded-xl border border-[#BFE7D0] bg-[#EEF9F2] hover:bg-[#DDF4E8] text-[#0B4F38] text-xs font-bold transition flex items-center justify-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#168A5B]"></i> Setor ke Mitra
                            </button>
                            <button type="button" onclick="submitScanForm('save')" class="py-2.5 px-3 rounded-xl border border-[#DDE3DF] bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold transition flex items-center justify-center gap-1.5">
                                <i data-lucide="bookmark" class="w-3.5 h-3.5"></i> Simpan Hasil
                            </button>
                        </div>
                    </div>

                    <!-- Human Disclaimer (PRD Requirement) -->
                    <p class="text-[10px] text-gray-400 text-center italic mt-2">
                        *Hasil analisis AI merupakan estimasi otomatis dan dapat disesuaikan kembali berdasarkan penimbangan aktual oleh kurir atau bank sampah mitra.
                    </p>
                </form>

            </div>
        </div>

    </div>

</div>

<script>
    const presetData = @json($presetMaterials);
    let currentPresetIdx = 0;

    function loadPreset(idx) {
        currentPresetIdx = idx;
        const item = presetData[idx];

        document.getElementById('scannerPreviewImg').src = item.image;
        document.getElementById('hudMaterialTag').textContent = item.name;
        document.getElementById('hudConfidence').textContent = `CONF: ${item.confidence}%`;
        document.getElementById('hudContamination').textContent = `KONTAMINASI: ${item.contamination}%`;

        document.getElementById('resultMaterialTitle').textContent = item.name;
        document.getElementById('resultCategoryText').textContent = `Kategori: ${item.category} • Grade A Daur Ulang`;
        document.getElementById('resultConfidenceBadge').innerHTML = `<i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> ${item.confidence}% Cocok`;
        document.getElementById('resultContaminationText').textContent = `${item.contamination}% (${item.contamination < 5 ? 'Sangat Bersih' : 'Cukup Bersih'})`;
        document.getElementById('resultRecommendation').textContent = item.recommendation;
        document.getElementById('resultPricePerKg').innerHTML = `Rp ${item.price_per_kg.toLocaleString('id-ID')} <span class="text-xs font-semibold text-[#168A5B]">/ kg</span>`;

        // Update hidden form
        document.getElementById('formMaterialName').value = item.name;
        document.getElementById('formCategory').value = item.category;
        document.getElementById('formConfidence').value = item.confidence;
        document.getElementById('formContamination').value = item.contamination;
        document.getElementById('formPricePerKg').value = item.price_per_kg;
        document.getElementById('formRecommendation').value = item.recommendation;

        // Gauge width
        document.getElementById('contaminationBar').style.width = `${Math.min(100, item.contamination * 4)}%`;

        calculateTotalValue();
        lucide.createIcons();
    }

    function switchPresetQuick() {
        let nextIdx = (currentPresetIdx + 1) % presetData.length;
        loadPreset(nextIdx);
    }

    function simulateScanAction() {
        const img = document.getElementById('scannerPreviewImg');
        img.classList.add('scale-105', 'brightness-125');
        setTimeout(() => {
            img.classList.remove('scale-105', 'brightness-125');
            switchPresetQuick();
        }, 300);
    }

    function handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('scannerPreviewImg').src = e.target.result;
                document.getElementById('hudMaterialTag').textContent = "Material Terunggah: Analisis Berhasil";
            };
            reader.readAsDataURL(file);
        }
    }

    function adjustWeight(delta) {
        const input = document.getElementById('weightInput');
        let current = parseFloat(input.value) || 1;
        current = Math.max(0.5, current + delta);
        input.value = current.toFixed(1);
        calculateTotalValue();
    }

    function calculateTotalValue() {
        const price = parseFloat(document.getElementById('formPricePerKg').value) || 0;
        const weight = parseFloat(document.getElementById('weightInput').value) || 0;
        const total = Math.round(price * weight);
        document.getElementById('resultTotalValue').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    function toggleScaleBonus(checkbox) {
        if (checkbox.checked) {
            alert('Bukti foto timbangan digital diaktifkan. Anda akan mendapatkan bonus +10 Eco-Points saat menyimpan!');
        }
    }

    function submitScanForm(action) {
        document.getElementById('formActionType').value = action;
        document.querySelector('form[action="{{ route('scanner.save') }}"]').submit();
    }
</script>
@endsection
