<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLARA — Olah Kembali, Jaga Bumi | Digital Eco-Wellness Platform</title>
    <meta name="description" content="Platform sirkular pengelolaan sampah cerdas dengan AI scanner, armada penjemputan on-demand, marketplace bahan daur ulang, dan reward saldo e-wallet instan.">

    <!-- Google Fonts: Plus Jakarta Sans, Outfit, Reenie Beanie -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Reenie+Beanie&display=swap" rel="stylesheet">

    <!-- Tailwind & Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Reveal on scroll styling */
        .reveal-item {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-item.revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-[#FDFCF8] text-[#292524] font-sans antialiased selection:bg-[#FFB7B2] selection:text-[#292524] relative overflow-x-hidden">

    <!-- Global Analog Grain Texture Overlay -->
    <div class="grain-overlay" aria-hidden="true"></div>

    <!-- Background Ambient Blurred Floating Blobs -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
        <div class="absolute top-[-10%] left-[-5%] w-[480px] h-[480px] md:w-[650px] md:h-[650px] rounded-full bg-[#FFE4E1] opacity-60 blur-[100px] animate-float-slow"></div>
        <div class="absolute top-[25%] right-[-10%] w-[420px] h-[420px] md:w-[600px] md:h-[600px] rounded-full bg-[#E6E6FA] opacity-60 blur-[110px] animate-float-reverse"></div>
        <div class="absolute top-[60%] left-[5%] w-[450px] h-[450px] md:w-[580px] md:h-[580px] rounded-full bg-[#E8EFE8] opacity-60 blur-[120px] animate-float-slow"></div>
        <div class="absolute bottom-[-5%] right-[10%] w-[500px] h-[500px] rounded-full bg-[#FFE4E1] opacity-50 blur-[110px] animate-float-reverse"></div>
    </div>

    <!-- Floating Pill Navigation Bar -->
    <header class="fixed top-4 md:top-6 left-0 right-0 z-40 flex justify-center px-4">
        <nav class="w-full max-w-5xl bg-white/70 backdrop-blur-[20px] border border-stone-200/60 rounded-full px-4 sm:px-6 py-3 shadow-soft flex items-center justify-between transition-all">
            <!-- Brand & Official Logo -->
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
                <img src="{{ asset('assets/img/logo-emblem.png') }}" class="h-8 sm:h-9 w-auto object-contain group-hover:scale-105 transition-transform" alt="Logo OLARA">
                <div class="flex flex-col">
                    <span class="font-outfit font-extrabold text-base md:text-lg tracking-tight text-[#292524] leading-none">OLARA</span>
                    <span class="text-[9px] font-semibold text-stone-500 tracking-widest uppercase mt-0.5">Sirkular Bumi</span>
                </div>
            </a>

            <!-- Desktop Links -->
            <div class="hidden lg:flex items-center gap-7 text-[13.5px] font-medium text-[#78716C]">
                <a href="#fitur" class="hover:text-[#292524] transition-colors">Fitur Utama</a>
                <a href="#skenario" class="hover:text-[#292524] transition-colors">Cara Kerja</a>
                <a href="#preview" class="hover:text-[#292524] transition-colors">Aplikasi</a>
                <a href="#marketplace" class="hover:text-[#292524] transition-colors">Marketplace B2B</a>
                <a href="#testimoni" class="hover:text-[#292524] transition-colors">Testimoni</a>
                <a href="#faq" class="hover:text-[#292524] transition-colors">Tanya Jawab</a>
            </div>

            <!-- CTA Cluster -->
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-[#292524] text-white px-4 py-2 rounded-full text-xs font-semibold hover:bg-stone-800 transition-all shadow-sm">
                        <span>Buka Dashboard</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block text-xs font-semibold text-[#292524] hover:text-stone-900 px-3 py-2 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('demo.login') }}" class="inline-flex items-center gap-1.5 bg-[#FFB7B2] hover:bg-[#FF9E98] text-[#292524] px-4 py-2 rounded-full text-xs font-bold transition-all shadow-soft group">
                        <span>Coba Akun Demo</span>
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 group-hover:rotate-12 transition-transform"></i>
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="relative z-10">

        <!-- ===================================================================
             1. HERO SECTION (100% Desktop Viewport Fit: Title, Subtitle, & Dual CTA)
        =================================================================== -->
        <section class="h-[100dvh] min-h-[480px] flex flex-col justify-center items-center text-center px-4 sm:px-6 pt-16 sm:pt-20 pb-8 sm:pb-10 max-w-5xl mx-auto reveal-item">
            <!-- Main Headline with Character Animation & Hand-Drawn Underline on Target Word -->
            <h1 id="hero-heading" data-animate-heading class="heading-animated font-outfit text-3xl sm:text-4xl md:text-5xl lg:text-[54px] font-bold tracking-tight text-[#292524] leading-[1.2] max-w-3xl mx-auto select-none">
                Ubah sampah harian jadi berkah yang 
                <span class="relative inline-block keyword-target font-cursive text-4xl sm:text-6xl md:text-7xl text-[#cb3930] font-normal lowercase px-1 rotate-[-2deg]">
                    bermakna
                    <!-- Hand-drawn SVG doodle underline animation -->
                    <svg class="hand-drawn-underline" viewBox="0 0 160 16" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                        <path d="M 4 10 C 35 3, 90 14, 156 5" stroke="#cb3930" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" class="underline-path" />
                    </svg>
                </span> 
                bagi bumi
            </h1>

            <!-- Sub-headline -->
            <p class="mt-4 sm:mt-5 text-sm sm:text-base text-[#78716C] max-w-[480px] mx-auto leading-relaxed font-normal">
                Pilah dengan AI, armada jemput ke rumah, dan tukar poin jadi saldo e-wallet.
            </p>

            <!-- Dual CTA Buttons -->
            <div class="mt-6 sm:mt-7 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('demo.login') }}" class="w-full sm:w-auto px-7 py-2.5 sm:py-3 rounded-full bg-[#FFB7B2] hover:bg-[#FF9E98] text-[#292524] font-bold text-sm shadow-soft transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                    <span>Mulai Gratis</span>
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                </a>
                <a href="#cara-kerja" class="w-full sm:w-auto px-6 py-2.5 sm:py-3 rounded-full bg-white hover:bg-stone-50 text-[#292524] font-semibold text-sm border border-stone-200/80 shadow-soft transition-all flex items-center justify-center gap-2">
                    <span>Lihat Fitur</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-[#78716C]"></i>
                </a>
            </div>
        </section>


        <!-- ===================================================================
             SHOWCASE & REALTIME IMPACT STATS
        =================================================================== -->
        <section class="py-12 sm:py-16 max-w-5xl mx-auto px-4 sm:px-6 reveal-item text-center">
            <!-- Hero Mascot (IKON.png) Showcase -->
            <div class="relative flex justify-center mb-10">
                <div class="relative z-10 max-w-[260px] sm:max-w-[320px]">
                    <img
                        src="{{ asset('assets/img/IKON.png') }}"
                        alt="Maskot Ramah OLARA"
                        class="w-full h-auto drop-shadow-xl animate-float-slow select-none pointer-events-none"
                    />
                </div>
                <!-- Soft Glow Base Ring -->
                <div class="absolute bottom-4 w-56 h-12 bg-[#FFB7B2]/30 rounded-full blur-xl -z-0"></div>
            </div>

            <!-- Micro Stats Ticker with Dynamic Count-up Animation -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl mx-auto pt-6 border-t border-stone-200/70 text-left">
                <div class="p-4 bg-white rounded-2xl border border-stone-200/70 card-lift shadow-xs">
                    <span class="text-xs text-[#78716C]">Sampah Terkelola</span>
                    <p class="font-outfit text-xl font-bold text-[#292524] stat-counter mt-0.5" data-target="{{ (float)$stats['total_waste_kg'] }}" data-suffix=" kg">{{ number_format($stats['total_waste_kg']) }} kg</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-stone-200/70 card-lift shadow-xs">
                    <span class="text-xs text-[#78716C]">Karbon Dicegah</span>
                    <p class="font-outfit text-xl font-bold text-[#292524] stat-counter mt-0.5" data-target="{{ (float)$stats['co2_avoided_kg'] }}" data-suffix=" kg CO₂e">{{ number_format($stats['co2_avoided_kg']) }} kg CO₂e</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-stone-200/70 card-lift shadow-xs">
                    <span class="text-xs text-[#78716C]">Mitra Bank Sampah</span>
                    <p class="font-outfit text-xl font-bold text-[#292524] stat-counter mt-0.5" data-target="{{ (int)$stats['active_partners'] }}" data-suffix=" Lokasi">{{ $stats['active_partners'] }} Lokasi</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-stone-200/70 card-lift shadow-xs">
                    <span class="text-xs text-[#78716C]">Komunitas Terdaftar</span>
                    <p class="font-outfit text-xl font-bold text-[#292524] stat-counter mt-0.5" data-target="{{ (int)$stats['community_members'] }}" data-suffix=" Jiwa">{{ number_format($stats['community_members']) }} Jiwa</p>
                </div>
            </div>
        </section>


        <!-- ===================================================================
             2. CARA KERJA OLARA (Tiga Langkah Sederhana - No AI Slop)
        =================================================================== -->
        <section id="cara-kerja" class="py-14 sm:py-20 max-w-5xl mx-auto px-4 sm:px-6 reveal-item">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-[#cb3930] bg-[#FFB7B2]/20 border border-[#FFB7B2]/40 px-3 py-1 rounded-full">
                    Cara Kerja
                </span>
                <h2 class="font-outfit text-3xl sm:text-4xl font-bold text-[#292524] mt-3">
                    Tiga Langkah 
                    <span class="relative inline-block keyword-target">
                        Sederhana
                        <svg class="hand-drawn-underline" viewBox="0 0 140 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                            <path d="M 3 9 C 30 3, 90 12, 137 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                        </svg>
                    </span>
                </h2>
                <p class="text-sm text-[#78716C] mt-2 leading-relaxed">
                    Dari sampah rumah tangga hingga menjadi saldo dompet dan manfaat nyata bagi bumi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Step 1 -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-stone-200/80 shadow-soft card-lift flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-10 h-10 rounded-2xl bg-stone-100 flex items-center justify-center text-[#292524]">
                                <i data-lucide="scan-line" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-[#cb3930] bg-[#FFB7B2]/20 px-2.5 py-0.5 rounded-full">01</span>
                        </div>
                        <h3 class="font-outfit text-lg font-bold text-[#292524] mb-2">Pindai Material Sampah</h3>
                        <p class="text-xs sm:text-sm text-[#78716C] leading-relaxed">
                            Arahkan kamera ke botol plastik, kardus, atau kaleng bekas. Sistem mengenali jenis material dan estimasi nilai jualnya seketika.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-stone-200/80 shadow-soft card-lift flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-10 h-10 rounded-2xl bg-stone-100 flex items-center justify-center text-[#292524]">
                                <i data-lucide="truck" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-[#cb3930] bg-[#FFB7B2]/20 px-2.5 py-0.5 rounded-full">02</span>
                        </div>
                        <h3 class="font-outfit text-lg font-bold text-[#292524] mb-2">Penjemputan ke Lokasi</h3>
                        <p class="text-xs sm:text-sm text-[#78716C] leading-relaxed">
                            Pesan kurir armada jemput ke depan pintu rumah atau kantor. Sampah ditimbang langsung di tempat dengan timbangan digital transparan.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-stone-200/80 shadow-soft card-lift flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-10 h-10 rounded-2xl bg-stone-100 flex items-center justify-center text-[#292524]">
                                <i data-lucide="wallet" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-[#cb3930] bg-[#FFB7B2]/20 px-2.5 py-0.5 rounded-full">03</span>
                        </div>
                        <h3 class="font-outfit text-lg font-bold text-[#292524] mb-2">Cairkan Poin & Manfaat</h3>
                        <p class="text-xs sm:text-sm text-[#78716C] leading-relaxed">
                            Poin hasil setoran langsung masuk ke dompet aplikasi. Tarik ke GoPay atau OVO, tukar voucer belanja, atau salurkan bibit pohon.
                        </p>
                    </div>
                </div>
            </div>
        </section>


        <!-- ===================================================================
             3. APP EXPERIENCE PREVIEW
        =================================================================== -->
        <section id="preview" class="py-16 md:py-24 max-w-6xl mx-auto px-4 sm:px-6 reveal-item">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-[#cb3930] bg-[#FFB7B2]/20 border border-[#FFB7B2]/40 px-3 py-1 rounded-full">
                    Preview Aplikasi
                </span>
                <h2 class="font-outfit text-3xl sm:text-4xl font-bold text-[#292524] mt-3">
                    Seringan 
                    <span class="relative inline-block keyword-target">
                        Hembusan Nafas
                        <svg class="hand-drawn-underline" viewBox="0 0 160 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                            <path d="M 3 9 C 35 3, 110 12, 157 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                        </svg>
                    </span>
                </h2>
                <p class="text-sm text-[#78716C] mt-2">
                    Interaksi taktil, intuitif, dan bebas distraksi untuk kelestarian bumi.
                </p>
            </div>

            <!-- Mockups Grid Container -->
            <div class="relative flex flex-col md:flex-row items-center justify-center gap-6 md:gap-8 pt-4 pb-12">
                
                <!-- Left Phone: Pickup Tracking (280x580px, +48px translateY, Clean Stone) -->
                <div class="w-[280px] h-[540px] md:h-[580px] bg-stone-50 rounded-[2.5rem] p-4 border-[6px] border-white shadow-soft-lg md:translate-y-12 opacity-90 transition-transform duration-500 hover:opacity-100 hover:scale-[1.02] flex flex-col justify-between">
                    <!-- Phone Notch & Status -->
                    <div class="flex justify-between items-center px-3 pt-1 text-[11px] font-semibold text-stone-600">
                        <span>09:41</span>
                        <div class="w-16 h-3.5 bg-stone-300 rounded-full mx-auto"></div>
                        <i data-lucide="wifi" class="w-3.5 h-3.5"></i>
                    </div>

                    <!-- Screen Content -->
                    <div class="bg-white rounded-3xl p-4 shadow-sm my-auto space-y-3 border border-stone-100">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-[#cb3930] bg-[#FFB7B2]/20 px-2 py-0.5 rounded-full">On The Way</span>
                            <span class="text-[10px] text-stone-400">PKP-202609-089</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-stone-100 flex items-center justify-center text-[#292524] font-bold">
                                <i data-lucide="truck" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-stone-800">Kurir Budi Santoso</h4>
                                <p class="text-[10px] text-stone-500">Motor Listrik EV — B 4219 SZR</p>
                            </div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-stone-50 border border-stone-100 text-[11px] space-y-1">
                            <div class="flex justify-between text-stone-600">
                                <span>Estimasi Muatan:</span>
                                <span class="font-bold text-stone-800">15.0 kg</span>
                            </div>
                            <div class="flex justify-between text-stone-600">
                                <span>Total Ongkir:</span>
                                <span class="font-bold text-stone-800">Rp 23.000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="text-center py-1">
                        <span class="text-[10px] font-medium text-stone-500">Pelacakan Armada Realtime</span>
                    </div>
                </div>

                <!-- Center Phone: AI Scanner (300x620px, fully opaque, pulsing Coral button) -->
                <div class="w-[300px] h-[580px] md:h-[620px] bg-white rounded-[2.8rem] p-4 border-[7px] border-stone-800 shadow-2xl relative z-20 flex flex-col justify-between">
                    <!-- Phone Speaker Notch -->
                    <div class="flex justify-between items-center px-3 pt-1 text-[11px] font-semibold text-stone-600">
                        <span>09:41</span>
                        <div class="w-20 h-4 bg-stone-900 rounded-full mx-auto"></div>
                        <i data-lucide="battery-charging" class="w-4 h-4 text-stone-700"></i>
                    </div>

                    <!-- Screen Viewfinder -->
                    <div class="relative bg-stone-900 rounded-3xl overflow-hidden h-[400px] flex flex-col justify-between p-4 my-auto text-white">
                        <!-- Top Camera Header -->
                        <div class="flex justify-between items-center text-xs">
                            <span class="bg-black/40 backdrop-blur px-2.5 py-1 rounded-full text-[10px] text-[#FFB7B2] flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#FFB7B2] animate-pulse"></span> AI Scanner Aktif
                            </span>
                            <span class="text-[10px] text-stone-300">Timbangan: 12.4 kg</span>
                        </div>

                        <!-- Scanner Crosshairs -->
                        <div class="my-auto border-2 border-dashed border-[#FFB7B2] rounded-2xl h-48 flex flex-col items-center justify-center p-3 text-center bg-white/5">
                            <span class="text-[10px] uppercase font-bold text-[#FFB7B2] tracking-wider">Akurasi AI: 98%</span>
                            <p class="text-sm font-bold text-white mt-1">Plastik PET Bening</p>
                            <span class="text-[11px] text-stone-300">Est. Rp 4.800 / kg</span>
                        </div>

                        <!-- Pulsing 'Breathe / Scan' Button (per spec) -->
                        <div class="text-center">
                            <button type="button" class="w-full py-3 rounded-full bg-[#FFB7B2] text-[#292524] font-bold text-xs shadow-soft animate-pulse-breathe flex items-center justify-center gap-2">
                                <i data-lucide="sparkles" class="w-4 h-4"></i>
                                <span>Scan Sampah Sekarang</span>
                            </button>
                        </div>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="w-24 h-1 bg-stone-300 rounded-full mx-auto my-1"></div>
                </div>

                <!-- Right Phone: Rewards & Marketplace (280x580px, +96px translateY, Clean Stone) -->
                <div class="w-[280px] h-[540px] md:h-[580px] bg-stone-50 rounded-[2.5rem] p-4 border-[6px] border-white shadow-soft-lg md:translate-y-24 opacity-90 transition-transform duration-500 hover:opacity-100 hover:scale-[1.02] flex flex-col justify-between">
                    <!-- Notch -->
                    <div class="flex justify-between items-center px-3 pt-1 text-[11px] font-semibold text-stone-600">
                        <span>09:41</span>
                        <div class="w-16 h-3.5 bg-stone-300 rounded-full mx-auto"></div>
                        <i data-lucide="bell" class="w-3.5 h-3.5"></i>
                    </div>

                    <!-- Screen Content -->
                    <div class="bg-white rounded-3xl p-4 shadow-sm my-auto space-y-3 border border-stone-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-stone-400">Saldo Dompet</span>
                                <h4 class="text-base font-extrabold text-[#292524]">1.450 Pts</h4>
                            </div>
                            <span class="text-[10px] font-bold text-[#cb3930] bg-[#FFB7B2]/20 px-2.5 py-1 rounded-full">Eco Champion</span>
                        </div>

                        <div class="p-2.5 rounded-2xl bg-stone-50 border border-stone-100 flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-xs text-[#292524]">
                                <i data-lucide="wallet" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h5 class="text-[11px] font-bold text-stone-800">GoPay Rp 50.000</h5>
                                <p class="text-[9px] text-stone-500">500 Pts • Cair Instan</p>
                            </div>
                        </div>

                        <div class="p-2.5 rounded-2xl bg-stone-50 border border-stone-100 flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-xs text-[#292524]">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h5 class="text-[11px] font-bold text-stone-800">Tokopedia Rp 25.000</h5>
                                <p class="text-[9px] text-stone-500">250 Pts • Belanja Ritel</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="text-center py-1">
                        <span class="text-[10px] font-medium text-stone-500">Katalog Rewards & Marketplace</span>
                    </div>
                </div>

            </div>
        </section>


        <!-- ===================================================================
             4. COMPREHENSIVE FEATURES (All Dashboard Features Per Section)
             With IKON1.png, IKON2.png, IKON3.png Mascot Integration & Journey Line
        =================================================================== -->
        <section id="fitur" class="relative py-20 md:py-28 max-w-5xl mx-auto px-4 sm:px-6 space-y-24">

            <!-- Journey Line Connector (Scroll Percentage Animated SVG) -->
            <div class="absolute left-1/2 -translate-x-1/2 top-32 bottom-32 w-16 pointer-events-none hidden lg:block -z-0 opacity-70" aria-hidden="true">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 40 1800" fill="none">
                    <!-- Subtle dotted guide -->
                    <path d="M 20 0 C 35 300, 5 600, 20 900 C 35 1200, 5 1500, 20 1800" stroke="#E4E4E7" stroke-width="2" stroke-dasharray="6 6" stroke-linecap="round" />
                    <!-- Dynamic journey progress path -->
                    <path id="journey-line-path" class="journey-line-track" d="M 20 0 C 35 300, 5 600, 20 900 C 35 1200, 5 1500, 20 1800" stroke="#cb3930" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <!-- Feature 1: AI Waste Scanner (Featuring IKON2.png) -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 items-center reveal-item relative z-10">
                <div class="md:col-span-6 space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#cb3930] bg-[#FFB7B2]/20 border border-[#FFB7B2]/40 px-3 py-1 rounded-full">
                        Fitur 01
                    </span>
                    <h3 class="font-outfit text-3xl sm:text-4xl font-bold text-[#292524] leading-snug">
                        <span class="relative inline-block keyword-target">
                            Kamera AI Pintar
                            <svg class="hand-drawn-underline" viewBox="0 0 210 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M 3 9 C 45 3, 140 12, 205 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                            </svg>
                        </span>
                    </h3>
                    <p class="text-[#78716C] text-sm sm:text-base leading-relaxed">
                        Arahkan kamera ke sampah. AI mengenali jenis dan harga jual seketika.
                    </p>
                    <ul class="space-y-2.5 pt-2 text-sm text-stone-700">
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-stone-100 text-[#292524] font-bold flex items-center justify-center text-xs">✓</span>
                            <span>Deteksi: PET, HDPE, kardus, & logam</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-stone-100 text-[#292524] font-bold flex items-center justify-center text-xs">✓</span>
                            <span>Estimasi harga jual per kilogram</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-stone-100 text-[#292524] font-bold flex items-center justify-center text-xs">✓</span>
                            <span>Bonus +10 Pts foto timbangan digital</span>
                        </li>
                    </ul>
                    <div class="pt-4">
                        <a href="{{ route('scanner.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#292524] hover:text-[#cb3930] underline underline-offset-4">
                            <span>Buka Kamera AI</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
                <div class="md:col-span-6 flex justify-center">
                    <div class="w-full max-w-[340px] bg-white rounded-[2.5rem] p-6 border border-stone-200/70 shadow-soft-lg text-center relative group">
                        <img
                            src="{{ asset('assets/img/IKON2.png') }}"
                            alt="Maskot Mengolah Botol Daur Ulang"
                            class="w-full h-auto drop-shadow-md group-hover:scale-105 transition-transform duration-500 select-none pointer-events-none"
                        />
                        <div class="mt-4 p-3 rounded-2xl bg-stone-50 border border-stone-100">
                            <p class="text-xs font-bold text-stone-800">Biji Pelet Daur Ulang</p>
                            <span class="text-[11px] text-[#78716C]">Kurangi mikroplastik industri</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 2: On-Demand Pickup & Dropoff Partners (Featuring IKON1.png) -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 items-center reveal-item">
                <div class="md:col-span-6 order-2 md:order-1 flex justify-center">
                    <div class="w-full max-w-[340px] bg-stone-50 rounded-[2.5rem] p-6 border border-stone-200/80 shadow-soft-lg text-center relative group">
                        <img
                            src="{{ asset('assets/img/IKON1.png') }}"
                            alt="Maskot Menyapu Sampah Pilahan"
                            class="w-full h-auto drop-shadow-md group-hover:scale-105 transition-transform duration-500 select-none pointer-events-none"
                        />
                        <div class="mt-4 p-3 rounded-2xl bg-white border border-stone-100">
                            <p class="text-xs font-bold text-stone-800">Armada Ramah Lingkungan</p>
                            <span class="text-[11px] text-[#78716C]">Jemput tepat waktu di rumah</span>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-6 order-1 md:order-2 space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#cb3930] bg-[#FFB7B2]/20 border border-[#FFB7B2]/40 px-3 py-1 rounded-full">
                        Fitur 02
                    </span>
                    <h3 class="font-outfit text-3xl sm:text-4xl font-bold text-[#292524] leading-snug">
                        Jemput & 
                        <span class="relative inline-block keyword-target">
                            Peta Mitra
                            <svg class="hand-drawn-underline" viewBox="0 0 140 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M 3 9 C 30 3, 90 12, 137 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                            </svg>
                        </span>
                    </h3>
                    <p class="text-[#78716C] text-sm sm:text-base leading-relaxed">
                        Armada jemput ke pintu rumah, atau setor langsung ke Bank Sampah mitra.
                    </p>
                    <ul class="space-y-2.5 pt-2 text-sm text-stone-700">
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-stone-100 text-[#292524] font-bold flex items-center justify-center text-xs">✓</span>
                            <span>Tarif transparan per km & volume</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-stone-100 text-[#292524] font-bold flex items-center justify-center text-xs">✓</span>
                            <span>Lacak kurir realtime via kode unik</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-stone-100 text-[#292524] font-bold flex items-center justify-center text-xs">✓</span>
                            <span>Bonus +15 hingga +25 Pts per setoran</span>
                        </li>
                    </ul>
                    <div class="pt-4 flex gap-4 text-xs font-bold">
                        <a href="{{ route('pickup.index') }}" class="text-[#292524] hover:text-[#cb3930] underline underline-offset-4">Jemput Sampah →</a>
                        <a href="{{ route('dropoff.index') }}" class="text-[#292524] hover:text-[#cb3930] underline underline-offset-4">Peta Mitra →</a>
                    </div>
                </div>
            </div>

            <!-- Feature 3: Rewards Wallet & Points Economy -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 items-center reveal-item">
                <div class="md:col-span-6 space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#cb3930] bg-[#FFB7B2]/20 border border-[#FFB7B2]/40 px-3 py-1 rounded-full">
                        Fitur 03
                    </span>
                    <h3 class="font-outfit text-3xl sm:text-4xl font-bold text-[#292524] leading-snug">
                        Eco-Points & 
                        <span class="relative inline-block keyword-target">
                            Hadiah Nyata
                            <svg class="hand-drawn-underline" viewBox="0 0 160 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M 3 9 C 35 3, 110 12, 157 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                            </svg>
                        </span>
                    </h3>
                    <p class="text-[#78716C] text-sm sm:text-base leading-relaxed">
                        Tukar poin jadi saldo e-wallet, voucher belanja, atau bibit pohon.
                    </p>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="p-3 bg-white rounded-2xl border border-stone-200/70 shadow-xs">
                            <span class="text-xs font-bold text-stone-800 block">GoPay & OVO</span>
                            <span class="text-[11px] text-[#78716C]">Mulai 500 Pts (Rp 50.000)</span>
                        </div>
                        <div class="p-3 bg-white rounded-2xl border border-stone-200/70 shadow-xs">
                            <span class="text-xs font-bold text-stone-800 block">Voucher Tokopedia</span>
                            <span class="text-[11px] text-[#78716C]">Mulai 250 Pts (Rp 25.000)</span>
                        </div>
                        <div class="p-3 bg-white rounded-2xl border border-stone-200/70 shadow-xs">
                            <span class="text-xs font-bold text-stone-800 block">Tiket Bioskop XXI</span>
                            <span class="text-[11px] text-[#78716C]">400 Pts Studio Regular</span>
                        </div>
                        <div class="p-3 bg-white rounded-2xl border border-stone-200/70 shadow-xs">
                            <span class="text-xs font-bold text-stone-800 block">Bibit Mangrove</span>
                            <span class="text-[11px] text-[#78716C]">200 Pts Bersertifikat</span>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-6 flex justify-center">
                    <div class="w-full max-w-[340px] bg-white rounded-[2.5rem] p-6 border border-stone-200/70 shadow-soft-lg space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                            <span class="text-xs font-bold text-stone-800">Katalog Hadiah Populer</span>
                            <span class="text-[10px] text-[#cb3930] bg-[#FFB7B2]/20 px-2 py-0.5 rounded-full font-bold">Stok Tersedia</span>
                        </div>
                        <div class="space-y-2.5">
                            <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-xl bg-stone-100 text-[#292524] flex items-center justify-center text-xs font-bold">Rp</span>
                                    <div>
                                        <p class="text-xs font-bold text-stone-800">Saldo GoPay Rp 50.000</p>
                                        <span class="text-[10px] text-stone-500">GoPay Official</span>
                                    </div>
                                </div>
                                <span class="text-xs font-extrabold text-[#292524]">500 Pts</span>
                            </div>
                            <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-xl bg-stone-100 text-[#292524] flex items-center justify-center text-xs font-bold">OVO</span>
                                    <div>
                                        <p class="text-xs font-bold text-stone-800">OVO Cash Rp 100.000</p>
                                        <span class="text-[10px] text-stone-500">OVO Official</span>
                                    </div>
                                </div>
                                <span class="text-xs font-extrabold text-[#292524]">1.000 Pts</span>
                            </div>
                            <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-xl bg-stone-100 text-[#292524] flex items-center justify-center text-xs font-bold">
                                        <i data-lucide="sprout" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-stone-800">Bibit Pohon Mangrove</p>
                                        <span class="text-[10px] text-stone-500">LindungiHutan</span>
                                    </div>
                                </div>
                                <span class="text-xs font-extrabold text-[#292524]">200 Pts</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 4: Marketplace B2B & Tracking Resi (Section) -->
            <div id="marketplace" class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 items-center reveal-item">
                <div class="md:col-span-6 order-2 md:order-1 flex justify-center">
                    <div class="w-full max-w-[360px] bg-white rounded-[2.5rem] p-6 border border-stone-200/70 shadow-soft-lg space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-stone-800">Bahan Siap Kirim</span>
                            <span class="text-[10px] text-[#cb3930] bg-[#FFB7B2]/20 px-2 py-0.5 rounded-full font-bold">B2B Verified</span>
                        </div>
                        <div class="space-y-2">
                            @foreach($sampleProducts->take(3) as $product)
                                <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-bold text-stone-800 leading-snug">{{ $product->name }}</p>
                                        <span class="text-[10px] text-stone-500">Min. {{ $product->min_order_kg }} kg • Stok: {{ number_format($product->stock_kg) }} kg</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-bold text-[#cb3930]">Rp {{ number_format($product->price_per_kg) }}</span>
                                        <span class="block text-[9px] text-stone-400">/ kg</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-2.5 rounded-xl bg-stone-50 border border-stone-200/80 text-[10px] text-stone-700 flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4 text-[#cb3930] shrink-0"></i>
                            <span>Faktur resmi & pelacakan resi kargo</span>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-6 order-1 md:order-2 space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#cb3930] bg-[#FFB7B2]/20 border border-[#FFB7B2]/40 px-3 py-1 rounded-full">
                        Fitur 04
                    </span>
                    <h3 class="font-outfit text-3xl sm:text-4xl font-bold text-[#292524] leading-snug">
                        <span class="relative inline-block keyword-target">
                            Marketplace B2B
                            <svg class="hand-drawn-underline" viewBox="0 0 210 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M 3 9 C 45 3, 140 12, 205 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                            </svg>
                        </span>
                        Sirkular
                    </h3>
                    <p class="text-[#78716C] text-sm sm:text-base leading-relaxed">
                        Jual beli material daur ulang langsung ke pabrik pengolah.
                    </p>
                    <ul class="space-y-2.5 pt-2 text-sm text-stone-700">
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-stone-100 text-[#292524] font-bold flex items-center justify-center text-xs">✓</span>
                            <span>Bahan teruji: PET, HDPE, & kardus</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-stone-100 text-[#292524] font-bold flex items-center justify-center text-xs">✓</span>
                            <span>Ongkir kargo transparan & proteksi transaksi</span>
                        </li>
                    </ul>
                    <div class="pt-4">
                        <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#292524] hover:text-[#cb3930] underline underline-offset-4">
                            <span>Buka Marketplace</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feature 5: Carbon Analytics & Olara Membership (Featuring IKON3.png) -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 items-center reveal-item">
                <div class="md:col-span-6 space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#cb3930] bg-[#FFB7B2]/20 border border-[#FFB7B2]/40 px-3 py-1 rounded-full">
                        Fitur 05
                    </span>
                    <h3 class="font-outfit text-3xl sm:text-4xl font-bold text-[#292524] leading-snug">
                        Dampak Karbon & 
                        <span class="relative inline-block keyword-target">
                            Membership
                            <svg class="hand-drawn-underline" viewBox="0 0 160 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M 3 9 C 35 3, 110 12, 157 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                            </svg>
                        </span>
                    </h3>
                    <p class="text-[#78716C] text-sm sm:text-base leading-relaxed">
                        Pantau emisi CO₂e yang dicegah dan nikmati kuota jemput gratis.
                    </p>
                    <div class="p-4 rounded-3xl bg-white border border-stone-200/70 shadow-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-stone-800 flex items-center gap-1.5">
                                <i data-lucide="crown" class="w-4 h-4 text-[#cb3930]"></i> Benefit Olara Premium
                            </span>
                            <span class="text-xs font-bold text-[#cb3930]">Rp 39.000 / bln</span>
                        </div>
                        <p class="text-xs text-[#78716C] leading-relaxed">
                            5x jemput gratis per bulan, scan AI tanpa batas, dan poin 1.2x tiap setoran.
                        </p>
                    </div>
                </div>
                <div class="md:col-span-6 flex justify-center">
                    <div class="w-full max-w-[340px] bg-stone-50 rounded-[2.5rem] p-6 border border-stone-200/80 shadow-soft-lg text-center relative group">
                        <img
                            src="{{ asset('assets/img/IKON3.png') }}"
                            alt="Maskot Sahabat Daur Ulang dan Tetes Air"
                            class="w-full h-auto drop-shadow-md group-hover:scale-105 transition-transform duration-500 select-none pointer-events-none"
                        />
                        <div class="mt-4 p-3 rounded-2xl bg-white border border-stone-100">
                            <p class="text-xs font-bold text-stone-800">Jaga Ekosistem Bumi</p>
                            <span class="text-[11px] text-[#78716C]">Lindungi habitat alam dan pesisir</span>
                        </div>
                    </div>
                </div>
            </div>

        </section>


        <!-- ===================================================================
             5. 3D ELEVATED AUTO-MOVING TESTIMONIALS (Per User Specification)
             Center card elevated, 3D shadow lift, 5 stars, quote mark, auto-moving,
             dot pattern canvas background, pause on hover.
        =================================================================== -->
        <section id="testimoni" class="py-20 md:py-28 bg-[#FDFCF8] border-y border-stone-200/70 overflow-hidden reveal-item">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 mb-12 text-center">
                <span class="text-xs font-bold uppercase tracking-widest text-[#78716C] bg-[#FFB7B2]/20 border border-[#FFB7B2]/40 px-3 py-1 rounded-full">
                    Cerita Pengguna
                </span>
                <h2 class="font-outfit text-2xl sm:text-4xl font-bold text-[#292524] mt-3">
                    tumbuh bersama 
                    <span class="relative inline-block keyword-target">
                        OLARA
                        <svg class="hand-drawn-underline" viewBox="0 0 100 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                            <path d="M 2 9 C 25 3, 70 12, 98 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                        </svg>
                    </span>
                </h2>
                <p class="text-xs sm:text-sm text-[#78716C] max-w-md mx-auto mt-2">
                    Kisah nyata pelaku usaha dan komunitas yang terbantu oleh ekosistem sirkular Olara.
                </p>
            </div>

            <!-- 3D Carousel Stage Container -->
            <div class="max-w-4xl mx-auto px-4 relative">
                <div id="carousel-3d" class="carousel-3d-stage cursor-grab">
                    
                    <!-- Card 1: Mayla Fazza (Kopi Senja Space) -->
                    <div class="testimonial-3d-card card-active bg-white border border-stone-100 rounded-[1.75rem] p-6 sm:p-8 flex flex-col justify-between" data-index="0">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex text-[#cb3930] gap-1 text-sm">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                                <span class="font-serif text-3xl sm:text-4xl text-[#FFB7B2] leading-none select-none">”</span>
                            </div>
                            <p class="text-stone-800 text-sm sm:text-base font-bold leading-snug">
                                “Paling terbantu dengan fitur pesanan jemput & struk digital timbangan. Sampah ampas kopi dan kardus susu rapi, poin terkonversi akurat!”
                            </p>
                        </div>
                        <div class="mt-6 flex items-center gap-3.5 pt-4 border-t border-stone-100">
                            <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 text-[#cb3930] font-bold text-xs flex items-center justify-center shrink-0">
                                MF
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#292524]">Mayla Fazza</h4>
                                <p class="text-[11px] text-[#cb3930] font-medium">Kopi Senja Space</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Meydi (Warkorame) -->
                    <div class="testimonial-3d-card card-next bg-white border border-stone-100 rounded-[1.75rem] p-6 sm:p-8 flex flex-col justify-between" data-index="1">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex text-[#cb3930] gap-1 text-sm">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                                <span class="font-serif text-3xl sm:text-4xl text-[#FFB7B2] leading-none select-none">”</span>
                            </div>
                            <p class="text-stone-800 text-sm sm:text-base font-bold leading-snug">
                                “Sekarang saya lebih tenang karena tahu kondisi pemilahan sampah kafe setiap hari, bukan cuma lihat omzet tapi dampaknya tercatat!”
                            </p>
                        </div>
                        <div class="mt-6 flex items-center gap-3.5 pt-4 border-t border-stone-100">
                            <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 text-[#cb3930] font-bold text-xs flex items-center justify-center shrink-0">
                                M
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#292524]">Meydi</h4>
                                <p class="text-[11px] text-[#cb3930] font-medium">Warkorame</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Rendy Pratama (Resto Dapur Melayu) -->
                    <div class="testimonial-3d-card card-hidden bg-white border border-stone-100 rounded-[1.75rem] p-6 sm:p-8 flex flex-col justify-between" data-index="2">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex text-[#cb3930] gap-1 text-sm">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                                <span class="font-serif text-3xl sm:text-4xl text-[#FFB7B2] leading-none select-none">”</span>
                            </div>
                            <p class="text-stone-800 text-sm sm:text-base font-bold leading-snug">
                                “Manajemen armada jemput real-time nya luar biasa. Tumpukan botol dan jeriken minyak bekas berkurang otomatis tanpa repot.”
                            </p>
                        </div>
                        <div class="mt-6 flex items-center gap-3.5 pt-4 border-t border-stone-100">
                            <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 text-[#cb3930] font-bold text-xs flex items-center justify-center shrink-0">
                                RP
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#292524]">Rendy Pratama</h4>
                                <p class="text-[11px] text-[#cb3930] font-medium">Resto Dapur Melayu</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Dian Sastrowijoyo (Komunitas Bintaro) -->
                    <div class="testimonial-3d-card card-hidden bg-white border border-stone-100 rounded-[1.75rem] p-6 sm:p-8 flex flex-col justify-between" data-index="3">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex text-[#cb3930] gap-1 text-sm">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                                <span class="font-serif text-3xl sm:text-4xl text-[#FFB7B2] leading-none select-none">”</span>
                            </div>
                            <p class="text-stone-800 text-sm sm:text-base font-bold leading-snug">
                                “Kardus dan botol tak lagi menumpuk di garasi. Cukup booking jemput, kurir datang tepat waktu dan poin langsung cair jadi GoPay.”
                            </p>
                        </div>
                        <div class="mt-6 flex items-center gap-3.5 pt-4 border-t border-stone-100">
                            <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 text-[#cb3930] font-bold text-xs flex items-center justify-center shrink-0">
                                DS
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#292524]">Dian Sastrowijoyo</h4>
                                <p class="text-[11px] text-[#cb3930] font-medium">Warga Bintaro</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5: Triyono Saputra (Sentra Daur Ulang) -->
                    <div class="testimonial-3d-card card-prev bg-white border border-stone-100 rounded-[1.75rem] p-6 sm:p-8 flex flex-col justify-between" data-index="4">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex text-[#cb3930] gap-1 text-sm">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                                <span class="font-serif text-3xl sm:text-4xl text-[#FFB7B2] leading-none select-none">”</span>
                            </div>
                            <p class="text-stone-800 text-sm sm:text-base font-bold leading-snug">
                                “Kamera AI mengenali jenis plastik secara cepat. Timbangan digital transparan membuat penyetor makin percaya diri bermitra.”
                            </p>
                        </div>
                        <div class="mt-6 flex items-center gap-3.5 pt-4 border-t border-stone-100">
                            <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 text-[#cb3930] font-bold text-xs flex items-center justify-center shrink-0">
                                TS
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#292524]">Triyono Saputra</h4>
                                <p class="text-[11px] text-[#cb3930] font-medium">Sentra Daur Ulang</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Carousel Navigation Dots -->
                <div class="flex justify-center items-center gap-2 mt-8">
                    <button type="button" class="w-2.5 h-2.5 rounded-full bg-[#cb3930] transition-all duration-300 carousel-dot" data-to="0" aria-label="Lihat testimoni 1"></button>
                    <button type="button" class="w-2.5 h-2.5 rounded-full bg-stone-300 transition-all duration-300 carousel-dot" data-to="1" aria-label="Lihat testimoni 2"></button>
                    <button type="button" class="w-2.5 h-2.5 rounded-full bg-stone-300 transition-all duration-300 carousel-dot" data-to="2" aria-label="Lihat testimoni 3"></button>
                    <button type="button" class="w-2.5 h-2.5 rounded-full bg-stone-300 transition-all duration-300 carousel-dot" data-to="3" aria-label="Lihat testimoni 4"></button>
                    <button type="button" class="w-2.5 h-2.5 rounded-full bg-stone-300 transition-all duration-300 carousel-dot" data-to="4" aria-label="Lihat testimoni 5"></button>
                </div>
            </div>
        </section>


        <!-- ===================================================================
             6. INTERACTIVE FAQ ACCORDION
        =================================================================== -->
        <section id="faq" class="py-16 md:py-24 max-w-3xl mx-auto px-4 sm:px-6 reveal-item">
            <div class="text-center mb-10">
                <span class="text-xs font-bold uppercase tracking-widest text-[#78716C] bg-[#FFB7B2]/20 border border-[#FFB7B2]/40 px-3 py-1 rounded-full">
                    Pertanyaan Umum
                </span>
                <h2 class="font-outfit text-2xl sm:text-3xl font-bold text-[#292524] mt-3">
                    <span class="relative inline-block keyword-target">
                        Tanya Jawab Populer
                        <svg class="hand-drawn-underline" viewBox="0 0 210 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                            <path d="M 3 9 C 45 3, 140 12, 205 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                        </svg>
                    </span>
                </h2>
            </div>

            <div class="space-y-3" id="faq-accordion">
                <!-- FAQ 1 -->
                <div class="faq-item bg-white rounded-2xl border border-stone-200/70 overflow-hidden shadow-soft transition-all">
                    <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-5 flex justify-between items-center gap-4 text-stone-800 font-medium text-sm sm:text-base hover:text-[#cb3930] transition-colors">
                        <span>Bagaimana cara kerja penjemputan sampah?</span>
                        <i data-lucide="plus" class="faq-icon w-4 h-4 text-stone-400 transition-transform duration-500 ease-in-out shrink-0"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out px-5">
                        <p class="text-xs sm:text-sm text-[#78716C] pb-5 leading-relaxed">
                            Pilih material, atur jadwal, dan masukkan alamat. Kurir armada kami datang menimbang di lokasi.
                        </p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="faq-item bg-white rounded-2xl border border-stone-200/70 overflow-hidden shadow-soft transition-all">
                    <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-5 flex justify-between items-center gap-4 text-stone-800 font-medium text-sm sm:text-base hover:text-[#cb3930] transition-colors">
                        <span>Berapa nilai 1 Eco-Point jika dicairkan?</span>
                        <i data-lucide="plus" class="faq-icon w-4 h-4 text-stone-400 transition-transform duration-500 ease-in-out shrink-0"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out px-5">
                        <p class="text-xs sm:text-sm text-[#78716C] pb-5 leading-relaxed">
                            10 Eco-Points = Rp 1.000. 500 Poin bisa ditukar langsung jadi saldo GoPay Rp 50.000.
                        </p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="faq-item bg-white rounded-2xl border border-stone-200/70 overflow-hidden shadow-soft transition-all">
                    <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-5 flex justify-between items-center gap-4 text-stone-800 font-medium text-sm sm:text-base hover:text-[#cb3930] transition-colors">
                        <span>Apakah kamera AI bisa mendeteksi botol kotor?</span>
                        <i data-lucide="plus" class="faq-icon w-4 h-4 text-stone-400 transition-transform duration-500 ease-in-out shrink-0"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out px-5">
                        <p class="text-xs sm:text-sm text-[#78716C] pb-5 leading-relaxed">
                            Bisa. Bilas wadah dan lepas tutupnya untuk mendapatkan estimasi nilai jual maksimal.
                        </p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="faq-item bg-white rounded-2xl border border-stone-200/70 overflow-hidden shadow-soft transition-all">
                    <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-5 flex justify-between items-center gap-4 text-stone-800 font-medium text-sm sm:text-base hover:text-[#cb3930] transition-colors">
                        <span>Berapa minimal order di Marketplace B2B?</span>
                        <i data-lucide="plus" class="faq-icon w-4 h-4 text-stone-400 transition-transform duration-500 ease-in-out shrink-0"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out px-5">
                        <p class="text-xs sm:text-sm text-[#78716C] pb-5 leading-relaxed">
                            Minimal order mulai 25 kg, didukung armada kargo resmi dan pelacakan resi realtime.
                        </p>
                    </div>
                </div>
            </div>
        </section>


        <!-- ===================================================================
             7. WAITLIST & CONVERSION SECTION
        =================================================================== -->
        <section class="py-16 md:py-24 relative overflow-hidden bg-stone-100/50 reveal-item">
            <!-- Background floating gradients (Warm coral tone) -->
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-[#FFE4E1] opacity-70 rounded-full blur-[90px]"></div>
                <div class="absolute top-1/2 right-1/4 -translate-y-1/2 w-[400px] h-[300px] bg-[#FFB7B2]/20 opacity-50 rounded-full blur-[80px]"></div>
            </div>

            <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 text-center">
                <!-- Dark stone rounded-square icon with coral dot (per spec) -->
                <div class="w-11 h-11 rounded-2xl bg-[#292524] flex items-center justify-center mx-auto mb-5 shadow-soft">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FFB7B2]"></span>
                </div>

                <h2 class="font-outfit text-2xl sm:text-4xl font-bold text-[#292524] tracking-tight">
                    Mulai Pilah 
                    <span class="relative inline-block keyword-target">
                        Hari Ini
                        <svg class="hand-drawn-underline" viewBox="0 0 120 14" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" aria-hidden="true">
                            <path d="M 3 9 C 30 3, 80 12, 117 5" stroke="#cb3930" stroke-width="3" stroke-linecap="round" class="underline-path" />
                        </svg>
                    </span>
                </h2>
                <p class="mt-3 text-sm text-[#78716C] max-w-sm mx-auto">
                    Daftar gratis sekarang dan dapatkan bonus <strong>+50 Eco-Points</strong> otomatis.
                </p>

                <!-- Waitlist / Quick Signup Form -->
                <form action="{{ route('register.submit') }}" method="POST" class="mt-7 max-w-md mx-auto flex flex-col sm:flex-row gap-2.5">
                    @csrf
                    <input type="hidden" name="name" value="Sahabat Bumi Baru">
                    <input type="hidden" name="password" value="password123">
                    <input
                        type="email"
                        name="email"
                        required
                        placeholder="Ketik alamat email..."
                        class="flex-1 px-5 py-3 rounded-full bg-stone-50 border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFB7B2] shadow-inner text-stone-800"
                    />
                    <button
                        type="submit"
                        class="px-7 py-3 rounded-full bg-[#292524] text-white font-bold text-sm hover:scale-105 transition-transform duration-300 shadow-soft flex items-center justify-center gap-2"
                    >
                        <span>Daftar Cepat</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-[#FFB7B2]"></i>
                    </button>
                </form>

                <div class="mt-5 flex items-center justify-center gap-5 text-xs text-[#78716C]">
                    <a href="{{ route('demo.login') }}" class="font-bold text-[#292524] hover:underline flex items-center gap-1">
                        <i data-lucide="play-circle" class="w-3.5 h-3.5 text-[#cb3930]"></i>
                        <span>Coba Akun Demo (Zidan)</span>
                    </a>
                    <span>•</span>
                    <a href="{{ route('login') }}" class="hover:underline">Sudah punya akun? Masuk</a>
                </div>
            </div>
        </section>

    </main>

    <!-- Clean, Softly Minimalist Footer -->
    <footer class="bg-white border-t border-stone-200/60 py-12 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row justify-between items-center gap-6 text-xs text-[#78716C]">
            <div class="flex items-center gap-3">
                <img
                    src="{{ asset('assets/img/LOGO ORALA.png') }}"
                    alt="Logo Olara"
                    class="h-8 w-auto object-contain"
                />
                <span class="text-[11px]">© 2026 PT Olara Ekosistem Digital. Seluruh hak cipta dilindungi.</span>
            </div>

            <div class="flex items-center gap-5 font-medium text-stone-700">
                <a href="#fitur" class="hover:text-stone-900 transition-colors">Fitur</a>
                <a href="{{ route('home') }}" class="hover:text-stone-900 transition-colors">Dashboard</a>
                <a href="{{ route('login') }}" class="hover:text-stone-900 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="hover:text-stone-900 transition-colors">Daftar</a>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts -->
    <script>
        // 1. Initialize Lucide Icons
        lucide.createIcons();

        // 2. Character-by-character Heading Animation without splitting words mid-line
        function initHeadingAnimation() {
            const heading = document.getElementById('hero-heading');
            if (!heading) return;

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                heading.classList.add('in-view');
                return;
            }

            let charCount = 0;
            function processNode(node) {
                if (node.nodeType === Node.TEXT_NODE) {
                    const text = node.textContent;
                    const tokens = text.split(/(\s+)/);
                    const frag = document.createDocumentFragment();

                    tokens.forEach(token => {
                        if (!token) return;
                        if (/^\s+$/.test(token)) {
                            const space = document.createElement('span');
                            space.className = 'char-space';
                            space.innerHTML = ' ';
                            frag.appendChild(space);
                        } else {
                            const wordSpan = document.createElement('span');
                            wordSpan.className = 'word-wrapper inline-block whitespace-nowrap';
                            for (let i = 0; i < token.length; i++) {
                                const ch = token[i];
                                const charSpan = document.createElement('span');
                                charSpan.className = 'char-fade';
                                charSpan.style.setProperty('--char-index', charCount++);
                                charSpan.textContent = ch;
                                wordSpan.appendChild(charSpan);
                            }
                            frag.appendChild(wordSpan);
                        }
                    });
                    return frag;
                } else if (node.nodeType === Node.ELEMENT_NODE) {
                    if (node.classList.contains('keyword-target')) {
                        const clone = node.cloneNode(true);
                        const textNode = Array.from(clone.childNodes).find(n => n.nodeType === Node.TEXT_NODE && n.textContent.trim().length > 0);
                        if (textNode) {
                            const frag = processNode(textNode);
                            clone.replaceChild(frag, textNode);
                        }
                        return clone;
                    }
                    const clone = node.cloneNode(false);
                    Array.from(node.childNodes).forEach(c => clone.appendChild(processNode(c)));
                    return clone;
                }
                return node.cloneNode(true);
            }

            const newFrag = document.createDocumentFragment();
            Array.from(heading.childNodes).forEach(c => newFrag.appendChild(processNode(c)));
            heading.innerHTML = '';
            heading.appendChild(newFrag);

            requestAnimationFrame(() => {
                heading.classList.add('in-view');
            });
        }
        initHeadingAnimation();

        // 3. Hand-drawn Underline Trigger for Target Keywords
        const keywordObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                }
            });
        }, { threshold: 0.25 });
        document.querySelectorAll('.keyword-target').forEach(el => keywordObserver.observe(el));

        // 4. Large Numbers Count-Up Animation
        const counterObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseFloat(el.getAttribute('data-target')) || 0;
                    const suffix = el.getAttribute('data-suffix') || '';
                    const duration = 1600;
                    const startTime = performance.now();

                    function updateCounter(now) {
                        const elapsed = now - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                        const current = Math.floor(ease * target);
                        el.textContent = current.toLocaleString('id-ID') + suffix;
                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        } else {
                            el.textContent = target.toLocaleString('id-ID') + suffix;
                        }
                    }
                    requestAnimationFrame(updateCounter);
                    obs.unobserve(el);
                }
            });
        }, { threshold: 0.25 });
        document.querySelectorAll('.stat-counter').forEach(el => counterObserver.observe(el));

        // 5. SVG Journey Line Connector (Progress based on scroll percentage)
        const journeyPath = document.getElementById('journey-line-path');
        if (journeyPath) {
            const length = journeyPath.getTotalLength();
            journeyPath.style.strokeDasharray = length;
            journeyPath.style.strokeDashoffset = length;

            function updateJourneyLine() {
                const section = document.getElementById('fitur');
                if (!section) return;
                const rect = section.getBoundingClientRect();
                const vh = window.innerHeight;
                const totalHeight = rect.height - vh * 0.3;
                const current = Math.max(0, vh * 0.7 - rect.top);
                const progress = Math.min(Math.max(current / totalHeight, 0), 1);
                journeyPath.style.strokeDashoffset = length * (1 - progress);
            }
            window.addEventListener('scroll', updateJourneyLine, { passive: true });
            updateJourneyLine();
        }

        // 6. 3D Testimonial Carousel Controller
        function init3DCarousel() {
            const stage = document.getElementById('carousel-3d');
            if (!stage) return;

            const cards = Array.from(stage.querySelectorAll('.testimonial-3d-card'));
            const dots = Array.from(document.querySelectorAll('.carousel-dot'));
            const total = cards.length;
            let current = 0;
            let autoTimer = null;

            function render() {
                cards.forEach((card, idx) => {
                    card.classList.remove('card-active', 'card-prev', 'card-next', 'card-hidden');
                    if (idx === current) {
                        card.classList.add('card-active');
                    } else if (idx === (current - 1 + total) % total) {
                        card.classList.add('card-prev');
                    } else if (idx === (current + 1) % total) {
                        card.classList.add('card-next');
                    } else {
                        card.classList.add('card-hidden');
                    }
                });

                dots.forEach((dot, idx) => {
                    if (idx === current) {
                        dot.classList.remove('bg-stone-300');
                        dot.classList.add('bg-[#cb3930]', 'scale-125');
                    } else {
                        dot.classList.remove('bg-[#cb3930]', 'scale-125');
                        dot.classList.add('bg-stone-300');
                    }
                });
            }

            function next() {
                current = (current + 1) % total;
                render();
            }

            function prev() {
                current = (current - 1 + total) % total;
                render();
            }

            function startTimer() {
                stopTimer();
                autoTimer = setInterval(next, 3800);
            }

            function stopTimer() {
                if (autoTimer) {
                    clearInterval(autoTimer);
                    autoTimer = null;
                }
            }

            cards.forEach((card, idx) => {
                card.addEventListener('click', () => {
                    if (idx === (current - 1 + total) % total) {
                        prev();
                        startTimer();
                    } else if (idx === (current + 1) % total) {
                        next();
                        startTimer();
                    }
                });
            });

            dots.forEach((dot) => {
                dot.addEventListener('click', () => {
                    current = parseInt(dot.getAttribute('data-to'), 10);
                    render();
                    startTimer();
                });
            });

            stage.addEventListener('mouseenter', stopTimer);
            stage.addEventListener('mouseleave', startTimer);

            render();
            startTimer();
        }
        init3DCarousel();

        // 7. FAQ Accordion Toggle
        function toggleFaq(btn) {
            const item = btn.closest('.faq-item');
            const content = item.querySelector('.faq-content');
            const icon = btn.querySelector('.faq-icon');
            const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

            document.querySelectorAll('.faq-item').forEach(other => {
                const otherContent = other.querySelector('.faq-content');
                const otherIcon = other.querySelector('.faq-icon');
                if (otherContent) otherContent.style.maxHeight = '0px';
                if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
            });

            if (!isOpen) {
                content.style.maxHeight = content.scrollHeight + 'px';
                if (icon) icon.style.transform = 'rotate(45deg)';
            } else {
                content.style.maxHeight = '0px';
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        }

        // 8. Reveal on scroll via IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px'
        });

        document.querySelectorAll('.reveal-item').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
