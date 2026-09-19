<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OLARA') — Olah Kembali, Jaga Bumi</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F7F8F6;
            color: #1B211E;
        }
        .bg-olara-primary { background-color: #168A5B; }
        .bg-olara-primary:hover { background-color: #0F6B47; }
        .text-olara-primary { color: #168A5B; }
        .border-olara-primary { border-color: #168A5B; }
        .bg-olara-soft { background-color: #DDF4E8; }
        .text-olara-dark { color: #0B4F38; }
        .border-olara-soft { border-color: #BFE7D0; }
        .gradient-olara {
            background: linear-gradient(135deg, #0B4F38 0%, #168A5B 60%, #2FAF76 100%);
        }
        .gradient-card {
            background: linear-gradient(145deg, #0D5C3A 0%, #168A5B 100%);
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#F7F8F6] text-[#1B211E] antialiased selection:bg-[#DDF4E8] selection:text-[#0B4F38]">

    <!-- Top Announcement Bar (Welcome Bonus 50 Poin) -->
    <div class="bg-[#0B4F38] text-white text-xs font-medium py-2 px-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center bg-[#2FAF76] text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Bonus Registrasi</span>
                <span>Daftar akun baru dan dapatkan otomatis <strong>50 Eco-Points</strong> langsung ke dompet Anda!</span>
            </div>
            <div class="hidden sm:flex items-center gap-4 text-emerald-200">
                <button onclick="openOnboardingModal()" class="hover:text-white transition flex items-center gap-1">
                    <i data-lucide="help-circle" class="w-3.5 h-3.5"></i> Panduan Edukasi
                </button>
                <span class="text-emerald-500">|</span>
                <a href="{{ route('membership.index') }}" class="hover:text-white transition flex items-center gap-1">
                    <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-300"></i> Olara Premium
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#DDE3DF] shadow-[0_1px_3px_rgba(0,0,0,0.03)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo & Tagline -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl gradient-card flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="leaf" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-extrabold text-xl tracking-tight text-[#0B4F38]">OLARA</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider bg-[#DDF4E8] text-[#168A5B] px-1.5 py-0.5 rounded">Eco Tech</span>
                            </div>
                            <p class="text-[10px] text-[#66716B] font-medium leading-none tracking-tight">Olah Kembali, Jaga Bumi</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-3 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('home') ? 'bg-[#EEF9F2] text-[#168A5B]' : 'text-[#66716B] hover:text-[#1B211E] hover:bg-gray-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('scanner.index') }}" class="px-3 py-2 text-sm font-semibold rounded-lg transition-colors flex items-center gap-1.5 {{ request()->routeIs('scanner.*') ? 'bg-[#EEF9F2] text-[#168A5B]' : 'text-[#66716B] hover:text-[#1B211E] hover:bg-gray-50' }}">
                        <i data-lucide="scan-line" class="w-4 h-4 text-[#168A5B]"></i> Scan AI
                    </a>
                    <a href="{{ route('pickup.index') }}" class="px-3 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('pickup.*') ? 'bg-[#EEF9F2] text-[#168A5B]' : 'text-[#66716B] hover:text-[#1B211E] hover:bg-gray-50' }}">
                        Jemput Sampah
                    </a>
                    <a href="{{ route('dropoff.index') }}" class="px-3 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('dropoff.*') ? 'bg-[#EEF9F2] text-[#168A5B]' : 'text-[#66716B] hover:text-[#1B211E] hover:bg-gray-50' }}">
                        Peta Mitra
                    </a>
                    <a href="{{ route('rewards.index') }}" class="px-3 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('rewards.*') ? 'bg-[#EEF9F2] text-[#168A5B]' : 'text-[#66716B] hover:text-[#1B211E] hover:bg-gray-50' }}">
                        Tukar Poin
                    </a>
                    <a href="{{ route('analytics.index') }}" class="px-3 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('analytics.*') ? 'bg-[#EEF9F2] text-[#168A5B]' : 'text-[#66716B] hover:text-[#1B211E] hover:bg-gray-50' }}">
                        Dampak Karbon
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="px-3 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('marketplace.*') ? 'bg-[#EEF9F2] text-[#168A5B]' : 'text-[#66716B] hover:text-[#1B211E] hover:bg-gray-50' }}">
                        Marketplace
                    </a>
                </nav>

                <!-- Right Action Cluster -->
                <div class="flex items-center gap-3">
                    @auth
                        <!-- Eco-Point Wallet Pill -->
                        <a href="{{ route('rewards.index') }}" class="flex items-center gap-2 bg-[#EEF9F2] hover:bg-[#DDF4E8] border border-[#BFE7D0] px-3 py-1.5 rounded-full transition group">
                            <div class="w-5 h-5 rounded-full bg-[#168A5B] flex items-center justify-center text-white text-[10px] font-bold">
                                🌿
                            </div>
                            <div class="text-left">
                                <span class="text-xs font-extrabold text-[#0B4F38] tabular-nums">{{ number_format(Auth::user()->eco_points) }}</span>
                                <span class="text-[10px] text-[#168A5B] font-semibold">Pts</span>
                            </div>
                        </a>

                        <!-- Membership Badge -->
                        <a href="{{ route('membership.index') }}" class="hidden sm:inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full border {{ Auth::user()->membership_tier === 'premium' ? 'bg-amber-50 text-amber-700 border-amber-300' : 'bg-gray-100 text-gray-700 border-gray-200' }}">
                            @if(Auth::user()->membership_tier === 'premium')
                                <i data-lucide="crown" class="w-3 h-3 text-amber-500"></i> Premium
                            @else
                                Lite
                            @endif
                        </a>

                        <!-- User Profile Dropdown -->
                        <div class="relative" id="userMenuContainer">
                            <button onclick="toggleUserDropdown()" class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition focus:outline-none">
                                <img src="{{ Auth::user()->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover border border-[#BFE7D0]" />
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-[#66716B]"></i>
                            </button>
                            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border border-[#DDE3DF] rounded-2xl shadow-xl py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs font-semibold text-gray-400">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-[#1B211E] truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-[#168A5B] font-medium">{{ Auth::user()->recycler_level }}</p>
                                </div>
                                <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                                </a>
                                <a href="{{ route('membership.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i> Status Membership
                                </a>
                                <a href="{{ route('demo.login') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">
                                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Reset Akun Demo
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-[#66716B] hover:text-[#1B211E] px-3 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-[#168A5B] hover:bg-[#0F6B47] text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-sm transition">Daftar (+50 Pts)</a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileNav()" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobileNav" class="hidden lg:hidden border-t border-[#DDE3DF] bg-white px-4 pt-2 pb-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">Beranda</a>
            <a href="{{ route('scanner.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">Scan Kamera AI</a>
            <a href="{{ route('pickup.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">Jemput Sampah (Pickup)</a>
            <a href="{{ route('dropoff.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">Peta Mitra Drop-off</a>
            <a href="{{ route('rewards.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">Tukar Eco-Points</a>
            <a href="{{ route('analytics.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">Laporan Jejak Karbon</a>
            <a href="{{ route('marketplace.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">Marketplace Daur Ulang</a>
            <a href="{{ route('membership.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-[#EEF9F2] hover:text-[#168A5B]">Paket Membership</a>
        </div>
    </header>

    <!-- Global Toast / Alert Notifications -->
    @if(session('success'))
        <div id="flashToast" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-[#EEF9F2] border border-[#BFE7D0] text-[#0B4F38] px-4 py-3 rounded-xl flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full bg-[#168A5B] text-white flex items-center justify-center font-bold text-sm">✓</span>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button onclick="document.getElementById('flashToast').remove()" class="text-[#168A5B] hover:text-[#0B4F38]">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="flashError" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-sm">!</span>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button onclick="document.getElementById('flashError').remove()" class="text-red-600 hover:text-red-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main View Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="bg-white border-t border-[#DDE3DF] mt-16 text-sm text-[#66716B]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Col 1: Brand & Mission -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg gradient-card flex items-center justify-center text-white">
                            <i data-lucide="leaf" class="w-4 h-4"></i>
                        </div>
                        <span class="font-extrabold text-lg text-[#0B4F38]">OLARA</span>
                    </div>
                    <p class="text-xs text-[#66716B] leading-relaxed">
                        Ekosistem teknologi pengelolaan sampah cerdas berbasis AI. Mengintegrasikan pemindaian material, penjemputan on-demand, direktori bank sampah, dan insentif ekonomi Eco-Points.
                    </p>
                    <p class="text-xs font-semibold text-[#168A5B]">“Olah Kembali, Jaga Bumi.”</p>
                </div>

                <!-- Col 2: Fitur Utama -->
                <div>
                    <h4 class="text-xs font-bold text-[#1B211E] uppercase tracking-wider mb-3">Layanan Inti</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('scanner.index') }}" class="hover:text-[#168A5B] transition">AI Waste Scanner</a></li>
                        <li><a href="{{ route('pickup.index') }}" class="hover:text-[#168A5B] transition">Jemput Sampah On-Demand</a></li>
                        <li><a href="{{ route('dropoff.index') }}" class="hover:text-[#168A5B] transition">Peta Bank Sampah Terdekat</a></li>
                        <li><a href="{{ route('rewards.index') }}" class="hover:text-[#168A5B] transition">Tukar Hadiah & Donasi</a></li>
                    </ul>
                </div>

                <!-- Col 3: Solusi Berkelanjutan -->
                <div>
                    <h4 class="text-xs font-bold text-[#1B211E] uppercase tracking-wider mb-3">Ekonomi Sirkular</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('marketplace.index') }}" class="hover:text-[#168A5B] transition">Marketplace Bahan Baku Daur Ulang</a></li>
                        <li><a href="{{ route('analytics.index') }}" class="hover:text-[#168A5B] transition">Kalkulator Jejak Karbon</a></li>
                        <li><a href="{{ route('membership.index') }}" class="hover:text-[#168A5B] transition">Olara Premium & Reboisasi</a></li>
                        <li><a href="{{ route('dropoff.index') }}?premium_only=1" class="hover:text-[#168A5B] transition">Kemitraan Bank Sampah</a></li>
                    </ul>
                </div>

                <!-- Col 4: Akun & Bantuan -->
                <div>
                    <h4 class="text-xs font-bold text-[#1B211E] uppercase tracking-wider mb-3">Pusat Bantuan</h4>
                    <ul class="space-y-2 text-xs">
                        <li><button onclick="openOnboardingModal()" class="hover:text-[#168A5B] transition text-left">Panduan Pengguna Baru</button></li>
                        <li><a href="{{ route('demo.login') }}" class="hover:text-[#168A5B] transition">Beralih Akun Demo</a></li>
                        <li><a href="{{ route('membership.index') }}" class="hover:text-[#168A5B] transition">Tanya Jawab (FAQ)</a></li>
                        <li class="pt-2 text-[11px] text-gray-400">Hubungi: support@olara.id</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400 gap-4">
                <p>© 2026 OLARA — Hak Cipta Dilindungi. Dibuat sesuai spesifikasi OLARA PRD.</p>
                <div class="flex items-center gap-4">
                    <span>Versi MVP 1.0 (Production Ready)</span>
                    <span>•</span>
                    <span class="flex items-center gap-1 text-[#168A5B] font-semibold"><span class="w-2 h-2 rounded-full bg-[#168A5B] animate-pulse"></span> Sistem Normal</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- INTERACTIVE ONBOARDING MODAL (PRD Section A - Onboarding Edukatif) -->
    <div id="onboardingModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border border-[#DDE3DF] animate-scale-in">
            <button onclick="closeOnboardingModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1.5 rounded-full hover:bg-gray-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <!-- Slide Content Container -->
            <div id="onboardingSlides">
                <!-- Slide 1 -->
                <div class="onboarding-slide text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-[#EEF9F2] text-[#168A5B] mx-auto flex items-center justify-center">
                        <i data-lucide="scan" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full">Langkah 1 dari 3</span>
                        <h3 class="text-xl font-bold text-[#0B4F38] mt-2">Pindai Sampah dengan Kamera AI</h3>
                        <p class="text-sm text-[#66716B] mt-1.5 leading-relaxed">
                            Cukup arahkan kamera ke material sampah Anda. AI cerdas OLARA akan mendeteksi jenis plastik, tingkat kebersihan/kontaminasi, dan estimasi nilai pasarnya.
                        </p>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="onboarding-slide hidden text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-[#EEF9F2] text-[#168A5B] mx-auto flex items-center justify-center">
                        <i data-lucide="truck" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full">Langkah 2 dari 3</span>
                        <h3 class="text-xl font-bold text-[#0B4F38] mt-2">Pilih Jemput atau Setor Mandiri</h3>
                        <p class="text-sm text-[#66716B] mt-1.5 leading-relaxed">
                            Pesan kurir armada untuk menjemput sampah ke rumah Anda dengan biaya transparan, atau antar sendiri ke mitra Bank Sampah terdekat untuk bonus poin ekstra.
                        </p>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="onboarding-slide hidden text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-[#EEF9F2] text-[#168A5B] mx-auto flex items-center justify-center">
                        <i data-lucide="gift" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B] bg-[#DDF4E8] px-2.5 py-1 rounded-full">Langkah 3 dari 3</span>
                        <h3 class="text-xl font-bold text-[#0B4F38] mt-2">Dapatkan Eco-Points & Tukar Hadiah</h3>
                        <p class="text-sm text-[#66716B] mt-1.5 leading-relaxed">
                            Setiap gram sampah yang terverifikasi menambah Eco-Points Anda. Tukarkan menjadi saldo GoPay, OVO, voucher belanja, atau donasikan untuk bibit pohon mangrove!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Slide Indicators -->
            <div class="flex items-center justify-center gap-2 my-6">
                <span class="slide-dot w-6 h-2 rounded-full bg-[#168A5B] transition-all"></span>
                <span class="slide-dot w-2 h-2 rounded-full bg-gray-200 transition-all"></span>
                <span class="slide-dot w-2 h-2 rounded-full bg-gray-200 transition-all"></span>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center gap-3">
                <button onclick="prevSlide()" id="btnPrevSlide" class="hidden flex-1 py-2.5 px-4 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Kembali
                </button>
                <button onclick="nextSlide()" id="btnNextSlide" class="flex-1 py-2.5 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-sm font-semibold shadow-sm transition">
                    Lanjut
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        lucide.createIcons();

        function toggleUserDropdown() {
            const el = document.getElementById('userDropdown');
            el.classList.toggle('hidden');
        }

        function toggleMobileNav() {
            const el = document.getElementById('mobileNav');
            el.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const container = document.getElementById('userMenuContainer');
            if (container && !container.contains(e.target)) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown) dropdown.classList.add('hidden');
            }
        });

        // Onboarding Carousel Logic
        let currentSlide = 0;
        const slides = document.querySelectorAll('.onboarding-slide');
        const dots = document.querySelectorAll('.slide-dot');

        function updateSlideUI() {
            slides.forEach((s, idx) => {
                s.classList.toggle('hidden', idx !== currentSlide);
            });
            dots.forEach((d, idx) => {
                if (idx === currentSlide) {
                    d.className = 'slide-dot w-6 h-2 rounded-full bg-[#168A5B] transition-all';
                } else {
                    d.className = 'slide-dot w-2 h-2 rounded-full bg-gray-200 transition-all';
                }
            });

            document.getElementById('btnPrevSlide').classList.toggle('hidden', currentSlide === 0);
            if (currentSlide === slides.length - 1) {
                document.getElementById('btnNextSlide').textContent = 'Mulai Sekarang';
            } else {
                document.getElementById('btnNextSlide').textContent = 'Lanjut';
            }
        }

        function nextSlide() {
            if (currentSlide < slides.length - 1) {
                currentSlide++;
                updateSlideUI();
            } else {
                closeOnboardingModal();
            }
        }

        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                updateSlideUI();
            }
        }

        function openOnboardingModal() {
            currentSlide = 0;
            updateSlideUI();
            document.getElementById('onboardingModal').classList.remove('hidden');
        }

        function closeOnboardingModal() {
            document.getElementById('onboardingModal').classList.add('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
