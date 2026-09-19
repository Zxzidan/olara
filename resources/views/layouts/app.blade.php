<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OLARA — Olah Kembali, Jaga Bumi')</title>

    <!-- Google Fonts: Plus Jakarta Sans, Outfit, Reenie Beanie -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Reenie+Beanie&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Dark Mode Init Script (prevent flicker) -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Tailwind & Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#FDFCF8] dark:bg-stone-900 text-stone-800 dark:text-stone-100 min-h-screen flex flex-col">
  <!-- Subtle Analog Grain Overlay -->
  <div class="grain-overlay" aria-hidden="true"></div>

  <div class="antialiased bg-[#FDFCF8] dark:bg-stone-900 min-h-screen">
    <!-- Top Navigation Bar -->
    <nav class="bg-[#FDFCF8]/90 border-b border-stone-200/80 px-4 py-2.5 backdrop-blur-md dark:bg-stone-900/90 dark:border-stone-800 fixed left-0 right-0 top-0 z-50 transition-colors">
      <div class="flex flex-wrap justify-between items-center">
        <!-- Brand & Mobile Toggle -->
        <div class="flex justify-start items-center">
          <button
            id="sidebar-toggle-btn"
            type="button"
            onclick="toggleSidebar(event)"
            aria-controls="drawer-navigation"
            class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white transition-colors"
            title="Buka / Tutup Sidebar"
            aria-label="Toggle sidebar"
          >
            <i data-lucide="menu" class="w-5 h-5"></i>
            <span class="sr-only">Toggle sidebar</span>
          </button>

          <a href="{{ route('home') }}" class="flex items-center gap-2.5 mr-3 sm:mr-4 group py-0.5" title="OLARA — Sirkular Bumi">
            <img
              src="{{ asset('assets/img/logo-emblem.png') }}"
              alt="OLARA — Sirkular Bumi"
              class="h-8 sm:h-9 w-auto object-contain transition-transform group-hover:scale-105"
            />
            <div class="flex flex-col">
              <span class="font-outfit font-extrabold text-base tracking-tight text-stone-900 dark:text-white leading-none">OLARA</span>
              <span class="text-[9px] font-semibold text-stone-500 dark:text-stone-400 tracking-widest uppercase mt-0.5">Sirkular Bumi</span>
            </div>
          </a>

          <!-- Topbar Search -->
          <form action="{{ route('dropoff.index') }}" method="GET" class="hidden md:block md:pl-2">
            <label for="topbar-search" class="sr-only">Search</label>
            <div class="relative md:w-64 lg:w-80">
              <div class="flex absolute inset-y-0 left-0 items-center pl-3.5 pointer-events-none text-stone-400">
                <i data-lucide="search" class="w-4 h-4"></i>
              </div>
              <input
                type="text"
                name="search"
                id="topbar-search"
                class="bg-stone-100/70 border border-stone-200/80 text-stone-800 text-xs rounded-full focus:ring-2 focus:ring-[#FFB7B2] focus:border-[#FFB7B2] block w-full pl-9.5 pr-4 py-2 dark:bg-stone-800 dark:border-stone-700 dark:placeholder-stone-400 dark:text-stone-100 transition-all placeholder:text-stone-400"
                placeholder="Cari bank sampah, material daur ulang..."
              />
            </div>
          </form>
        </div>

        <!-- Right Cluster -->
        <div class="flex items-center gap-1 sm:gap-2 lg:order-2">
          <!-- Eco-Points Pill -->
          <a href="{{ route('rewards.index') }}" class="flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 dark:bg-emerald-950/50 dark:border-emerald-800/80 dark:hover:bg-emerald-900/60 px-3 py-1.5 rounded-full transition group" title="Saldo Eco-Points Anda">
            <span class="text-xs sm:text-sm font-extrabold text-emerald-800 dark:text-emerald-200 tabular-nums">{{ number_format(Auth::user()?->eco_points ?? 0) }}</span>
            <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">Pts</span>
          </a>

          <!-- Dark Mode Toggle Button -->
          <button
            id="theme-toggle"
            type="button"
            class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            title="Ganti Tema Gelap / Terang"
          >
            <span id="theme-toggle-dark-icon" class="hidden">
              <i data-lucide="moon" class="w-5 h-5"></i>
            </span>
            <span id="theme-toggle-light-icon" class="hidden">
              <i data-lucide="sun" class="w-5 h-5"></i>
            </span>
          </button>

          <!-- Notifications Dropdown Button -->
          <button
            type="button"
            data-dropdown-toggle="notification-dropdown"
            class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 relative"
          >
            <span class="sr-only">Lihat notifikasi</span>
            <i data-lucide="bell" class="w-5 h-5"></i>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-emerald-500 rounded-full"></span>
          </button>

          <!-- Notification Dropdown Menu -->
          <div
            class="hidden overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded-xl divide-y divide-gray-100 shadow-xl dark:divide-gray-600 dark:bg-gray-700"
            id="notification-dropdown"
          >
            <div class="block py-2 px-4 text-sm font-semibold text-center text-gray-700 bg-gray-50 dark:bg-gray-600 dark:text-gray-300">
              Notifikasi Lingkungan
            </div>
            <div>
              <a href="{{ route('rewards.index') }}" class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-600 flex items-center justify-center">
                  <i data-lucide="gift" class="w-4 h-4"></i>
                </div>
                <div class="pl-3 w-full">
                  <div class="text-gray-600 dark:text-gray-300 text-xs mb-1">
                    Bonus selamat datang: <strong>+10 Eco-Points</strong> telah aktif di akun Anda!
                  </div>
                  <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">Baru saja</div>
                </div>
              </a>
              <a href="{{ route('pickup.index') }}" class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 flex items-center justify-center">
                  <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
                <div class="pl-3 w-full">
                  <div class="text-gray-600 dark:text-gray-300 text-xs mb-1">
                    Armada jemput sampah siap beroperasi di wilayah Anda hari ini.
                  </div>
                  <div class="text-[10px] text-gray-400">1 jam lalu</div>
                </div>
              </a>
            </div>
            <a href="{{ route('analytics.index') }}" class="block py-2 text-xs font-semibold text-center text-primary-600 dark:text-primary-400 bg-gray-50 hover:bg-gray-100 dark:bg-gray-600 dark:hover:bg-gray-500">
              Lihat Laporan Dampak Lengkap →
            </a>
          </div>

          <!-- Apps Grid Dropdown Button -->
          <button
            type="button"
            data-dropdown-toggle="apps-dropdown"
            class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            title="Menu Fitur Utama"
          >
            <span class="sr-only">Semua Fitur</span>
            <i data-lucide="grid" class="w-5 h-5"></i>
          </button>

          <!-- Apps Grid Dropdown Menu -->
          <div
            class="hidden overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded-xl divide-y divide-gray-100 shadow-xl dark:bg-gray-700 dark:divide-gray-600"
            id="apps-dropdown"
          >
            <div class="block py-2 px-4 text-xs font-semibold text-center text-gray-700 bg-gray-50 dark:bg-gray-600 dark:text-gray-300 uppercase tracking-wider">
              Ekosistem Fitur OLARA
            </div>
            <div class="grid grid-cols-3 gap-2 p-3">
              <a href="{{ route('scanner.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                <i data-lucide="scan-line" class="mx-auto mb-1 w-6 h-6 text-primary-600 dark:text-primary-400 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-semibold text-gray-900 dark:text-white">Scan AI</div>
              </a>
              <a href="{{ route('pickup.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                <i data-lucide="truck" class="mx-auto mb-1 w-6 h-6 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-semibold text-gray-900 dark:text-white">Jemput</div>
              </a>
              <a href="{{ route('dropoff.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                <i data-lucide="map-pin" class="mx-auto mb-1 w-6 h-6 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-semibold text-gray-900 dark:text-white">Peta Mitra</div>
              </a>
              <a href="{{ route('rewards.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                <i data-lucide="gift" class="mx-auto mb-1 w-6 h-6 text-pink-600 dark:text-pink-400 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-semibold text-gray-900 dark:text-white">Tukar Poin</div>
              </a>
              <a href="{{ route('marketplace.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                <i data-lucide="shopping-bag" class="mx-auto mb-1 w-6 h-6 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-semibold text-gray-900 dark:text-white">Marketplace</div>
              </a>
              <a href="{{ route('analytics.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                <i data-lucide="bar-chart-3" class="mx-auto mb-1 w-6 h-6 text-teal-600 dark:text-teal-400 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-semibold text-gray-900 dark:text-white">Karbon</div>
              </a>
              <a href="{{ route('membership.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                <i data-lucide="crown" class="mx-auto mb-1 w-6 h-6 text-yellow-500 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-semibold text-gray-900 dark:text-white">Membership</div>
              </a>
              <button type="button" onclick="openOnboardingModal()" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                <i data-lucide="help-circle" class="mx-auto mb-1 w-6 h-6 text-blue-500 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-semibold text-gray-900 dark:text-white">Panduan</div>
              </button>
              <a href="{{ route('demo.login') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group">
                <i data-lucide="refresh-cw" class="mx-auto mb-1 w-6 h-6 text-gray-500 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-semibold text-gray-900 dark:text-white">Akun Demo</div>
              </a>
            </div>
          </div>

          <!-- User Avatar & Dropdown -->
          <button
            type="button"
            class="flex mx-1 sm:mx-2 text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            id="user-menu-button"
            aria-expanded="false"
            data-dropdown-toggle="dropdown"
          >
            <span class="sr-only">Buka menu akun</span>
            <img
              class="w-8 h-8 rounded-full object-cover border border-emerald-400"
              src="{{ Auth::user()?->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=120&auto=format&fit=crop&q=80' }}"
              alt="{{ Auth::user()?->name ?? 'User' }}"
            />
          </button>

          <!-- User Dropdown Menu -->
          <div
            class="hidden z-50 my-4 w-60 text-base list-none bg-white rounded-xl divide-y divide-gray-100 shadow-xl dark:bg-gray-700 dark:divide-gray-600"
            id="dropdown"
          >
            <div class="py-3 px-4">
              <div class="flex items-center justify-between gap-2">
                <span class="block text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()?->name ?? 'Zidan Ramadhan' }}</span>
                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full {{ (Auth::user()?->membership_tier ?? 'lite') === 'premium' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-gray-200' }}">
                  {{ Auth::user()?->membership_tier ?? 'Lite' }}
                </span>
              </div>
              <span class="block text-xs text-gray-500 truncate dark:text-gray-400 mt-0.5">{{ Auth::user()?->email ?? 'zidan@olara.id' }}</span>
              <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-600 flex items-center justify-between text-xs">
                <span class="text-gray-500 dark:text-gray-400">Level Saat Ini:</span>
                <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ Auth::user()?->recycler_level ?? 'Starter' }}</span>
              </div>
            </div>
            <ul class="py-1 text-sm text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
              <li>
                <a href="{{ route('home') }}" class="flex items-center gap-2 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                  <i data-lucide="layout-dashboard" class="w-4 h-4 text-gray-400"></i> Dashboard Utama
                </a>
              </li>
              <li>
                <a href="{{ route('rewards.index') }}" class="flex items-center gap-2 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                  <i data-lucide="gift" class="w-4 h-4 text-emerald-500"></i> Dompet Poin ({{ number_format(Auth::user()?->eco_points ?? 0) }})
                </a>
              </li>
              <li>
                <a href="{{ route('membership.index') }}" class="flex items-center gap-2 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                  <i data-lucide="crown" class="w-4 h-4 text-amber-500"></i> Paket Membership
                </a>
              </li>
              <li>
                <button type="button" onclick="openOnboardingModal()" class="w-full text-left flex items-center gap-2 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                  <i data-lucide="compass" class="w-4 h-4 text-blue-500"></i> Panduan Edukasi
                </button>
              </li>
              <li>
                <a href="{{ route('demo.login') }}" class="flex items-center gap-2 py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                  <i data-lucide="refresh-cw" class="w-4 h-4 text-gray-400"></i> Reset Akun Demo
                </a>
              </li>
              <li class="border-t border-gray-100 dark:border-gray-600">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full text-left flex items-center gap-2 py-2 px-4 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 dark:text-rose-400 cursor-pointer">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Log Out
                  </button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- Sidebar Navigation -->
    <aside
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 bg-[#FDFCF8] border-r border-stone-200/80 dark:bg-stone-900 dark:border-stone-800 transition-transform duration-300 ease-in-out"
      aria-label="Sidenav"
      id="drawer-navigation"
    >
      <div class="overflow-y-auto pt-5 px-3 pb-20 h-full bg-[#FDFCF8] dark:bg-stone-900">
        <!-- Mobile Sidebar Close Header -->
        <div class="flex items-center justify-between pb-2 mb-2 border-b border-stone-200/60 dark:border-stone-800 md:hidden">
          <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Navigasi OLARA</span>
          <button id="sidebar-close-btn" type="button" onclick="closeMobileSidebar()" class="p-1.5 text-stone-500 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 cursor-pointer" title="Tutup Menu">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
        <!-- Sidebar Mobile Search -->
        <form action="{{ route('dropoff.index') }}" method="GET" class="md:hidden mb-3">
          <label for="sidebar-search" class="sr-only">Search</label>
          <div class="relative">
            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none text-stone-400">
              <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input
              type="text"
              name="search"
              id="sidebar-search"
              class="bg-stone-50 border border-stone-200 text-stone-800 text-sm rounded-xl focus:ring-[#FFB7B2] focus:border-[#FFB7B2] block w-full pl-9 p-2 dark:bg-stone-800 dark:border-stone-700 dark:placeholder-stone-400 dark:text-white"
              placeholder="Cari lokasi dropoff..."
            />
          </div>
        </form>

        <!-- Main Navigation Links -->
        <ul class="space-y-1.5 font-medium">
          <!-- Overview -->
          <li>
            <a
              href="{{ route('home') }}"
              class="flex items-center p-2 text-sm rounded-xl transition-colors group {{ request()->routeIs('home') ? 'bg-[#FFE4E1]/60 text-stone-900 dark:bg-stone-800 dark:text-white font-bold shadow-sm' : 'text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white' }}"
            >
              <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->routeIs('home') ? 'text-[#ef5348] dark:text-[#ffb7b2]' : 'text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200' }}"></i>
              <span class="ml-3">Dashboard</span>
            </a>
          </li>

          <!-- Category Header: Layanan Inti -->
          <li class="pt-4 pb-1">
            <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500">Layanan Sampah</span>
          </li>

          <!-- 1. AI Scanner -->
          <li>
            <a
              href="{{ route('scanner.index') }}"
              class="flex items-center p-2 text-sm rounded-xl transition-colors group {{ request()->routeIs('scanner.*') ? 'bg-[#FFE4E1]/50 text-stone-900 dark:bg-stone-800 dark:text-white font-bold shadow-xs' : 'text-stone-700 dark:text-stone-300 hover:bg-stone-100/70 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white' }}"
            >
              <i data-lucide="scan-line" class="w-5 h-5 {{ request()->routeIs('scanner.*') ? 'text-[#cb3930] dark:text-[#ffb7b2]' : 'text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200' }}"></i>
              <span class="flex-1 ml-3 whitespace-nowrap">Scan AI</span>
              <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                +10 Pts
              </span>
            </a>
          </li>

          <!-- 2. Jemput Sampah -->
          <li>
            <a
              href="{{ route('pickup.index') }}"
              class="flex items-center p-2 text-sm rounded-xl transition-colors group {{ request()->routeIs('pickup.*') ? 'bg-[#FFE4E1]/50 text-stone-900 dark:bg-stone-800 dark:text-white font-bold shadow-xs' : 'text-stone-700 dark:text-stone-300 hover:bg-stone-100/70 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white' }}"
            >
              <i data-lucide="truck" class="w-5 h-5 {{ request()->routeIs('pickup.*') ? 'text-[#cb3930] dark:text-[#ffb7b2]' : 'text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200' }}"></i>
              <span class="flex-1 ml-3 whitespace-nowrap">Jemput Sampah</span>
            </a>
          </li>

          <!-- 3. Peta Mitra Drop-off -->
          <li>
            <a
              href="{{ route('dropoff.index') }}"
              class="flex items-center p-2 text-sm rounded-xl transition-colors group {{ request()->routeIs('dropoff.*') ? 'bg-[#FFE4E1]/50 text-stone-900 dark:bg-stone-800 dark:text-white font-bold shadow-xs' : 'text-stone-700 dark:text-stone-300 hover:bg-stone-100/70 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white' }}"
            >
              <i data-lucide="map-pin" class="w-5 h-5 {{ request()->routeIs('dropoff.*') ? 'text-[#cb3930] dark:text-[#ffb7b2]' : 'text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200' }}"></i>
              <span class="flex-1 ml-3 whitespace-nowrap">Peta Mitra</span>
            </a>
          </li>

          <!-- Category Header: Insentif & Sirkular -->
          <li class="pt-4 pb-1">
            <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500">Ekonomi Sirkular</span>
          </li>

          <!-- 4. Tukar Poin -->
          <li>
            <a
              href="{{ route('rewards.index') }}"
              class="flex items-center p-2 text-sm rounded-xl transition-colors group {{ request()->routeIs('rewards.*') ? 'bg-[#FFE4E1]/50 text-stone-900 dark:bg-stone-800 dark:text-white font-bold shadow-xs' : 'text-stone-700 dark:text-stone-300 hover:bg-stone-100/70 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white' }}"
            >
              <i data-lucide="gift" class="w-5 h-5 {{ request()->routeIs('rewards.*') ? 'text-[#cb3930] dark:text-[#ffb7b2]' : 'text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200' }}"></i>
              <span class="flex-1 ml-3 whitespace-nowrap">Tukar Poin</span>
            </a>
          </li>

          <!-- 5. Marketplace Daur Ulang (Dropdown) -->
          <li>
            <button
              type="button"
              id="marketplace-menu-btn"
              onclick="toggleMarketplaceDropdown(event)"
              class="flex items-center w-full p-2 text-sm rounded-xl transition-colors group cursor-pointer {{ request()->routeIs('marketplace.*') ? 'bg-[#FFE4E1]/50 text-stone-900 dark:bg-stone-800 dark:text-white font-bold shadow-xs' : 'text-stone-700 dark:text-stone-300 hover:bg-stone-100/70 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white' }}"
            >
              <i data-lucide="shopping-bag" class="w-5 h-5 {{ request()->routeIs('marketplace.*') ? 'text-[#cb3930] dark:text-[#ffb7b2]' : 'text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200' }}"></i>
              <span class="flex-1 ml-3 text-left whitespace-nowrap">Marketplace B2B</span>
              <span id="marketplace-chevron-wrapper" class="inline-flex transition-transform duration-200 {{ request()->routeIs('marketplace.*') ? 'rotate-180' : '' }}">
                <i data-lucide="chevron-down" id="marketplace-chevron" class="w-4 h-4 text-stone-400"></i>
              </span>
            </button>
            <div id="marketplace-submenu" class="{{ request()->routeIs('marketplace.*') ? '' : 'hidden' }} py-1 pl-6 space-y-1">
              <a
                href="{{ route('marketplace.index') }}"
                onclick="closeMobileSidebar()"
                class="flex items-center gap-2 p-2 text-xs rounded-lg transition-colors {{ request()->routeIs('marketplace.index') ? 'text-[#cb3930] font-bold dark:text-[#ffb7b2] bg-[#FFE4E1]/40 dark:bg-stone-800' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 hover:bg-stone-100 dark:hover:bg-stone-800 dark:hover:text-white' }}"
              >
                <i data-lucide="store" class="w-3.5 h-3.5"></i>
                <span>Katalog Produk</span>
              </a>
              <a
                href="{{ route('marketplace.orders') }}"
                onclick="closeMobileSidebar()"
                class="flex items-center justify-between p-2 text-xs rounded-lg transition-colors {{ request()->routeIs('marketplace.orders') || request()->routeIs('marketplace.orderDetail') ? 'text-[#cb3930] font-bold dark:text-[#ffb7b2] bg-[#FFE4E1]/40 dark:bg-stone-800' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 hover:bg-stone-100 dark:hover:bg-stone-800 dark:hover:text-white' }}"
              >
                <span class="flex items-center gap-2">
                  <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                  <span>Lacak Pesanan</span>
                </span>
                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                  Live
                </span>
              </a>
            </div>
          </li>

          <!-- 6. Carbon Analytics -->
          <li>
            <a
              href="{{ route('analytics.index') }}"
              class="flex items-center p-2 text-sm rounded-xl transition-colors group {{ request()->routeIs('analytics.*') ? 'bg-[#FFE4E1]/50 text-stone-900 dark:bg-stone-800 dark:text-white font-bold shadow-xs' : 'text-stone-700 dark:text-stone-300 hover:bg-stone-100/70 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white' }}"
            >
              <i data-lucide="bar-chart-3" class="w-5 h-5 {{ request()->routeIs('analytics.*') ? 'text-[#cb3930] dark:text-[#ffb7b2]' : 'text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200' }}"></i>
              <span class="flex-1 ml-3 whitespace-nowrap">Dampak Karbon</span>
            </a>
          </li>

          <!-- 7. Membership -->
          <li>
            <a
              href="{{ route('membership.index') }}"
              class="flex items-center p-2 text-sm rounded-xl transition-colors group {{ request()->routeIs('membership.*') ? 'bg-[#FFE4E1]/50 text-stone-900 dark:bg-stone-800 dark:text-white font-bold shadow-xs' : 'text-stone-700 dark:text-stone-300 hover:bg-stone-100/70 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white' }}"
            >
              <i data-lucide="crown" class="w-5 h-5 {{ request()->routeIs('membership.*') ? 'text-[#cb3930] dark:text-[#ffb7b2]' : 'text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200' }}"></i>
              <span class="flex-1 ml-3 whitespace-nowrap">Membership</span>
            </a>
          </li>
        </ul>

        <!-- Bottom Secondary Links -->
        <ul class="pt-4 mt-4 space-y-1.5 border-t border-stone-200/80 dark:border-stone-800">
          <li>
            <button
              type="button"
              onclick="openOnboardingModal()"
              class="w-full flex items-center p-2 text-sm text-stone-700 dark:text-stone-300 rounded-xl hover:bg-stone-100 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white group"
            >
              <i data-lucide="help-circle" class="w-5 h-5 text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200"></i>
              <span class="ml-3">Panduan</span>
            </button>
          </li>
          <li>
            <a
              href="{{ route('demo.login') }}"
              class="flex items-center p-2 text-sm text-stone-700 dark:text-stone-300 rounded-xl hover:bg-stone-100 dark:hover:bg-stone-800 hover:text-stone-900 dark:hover:text-white group"
            >
              <i data-lucide="refresh-cw" class="w-5 h-5 text-stone-400 group-hover:text-stone-700 dark:group-hover:text-stone-200"></i>
              <span class="ml-3">Akun Demo</span>
            </a>
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
              @csrf
              <button
                type="submit"
                class="w-full flex items-center p-2 text-sm text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/30 group transition-colors cursor-pointer"
                title="Keluar dari akun Anda"
              >
                <i data-lucide="log-out" class="w-5 h-5 text-rose-500 group-hover:text-rose-700 dark:group-hover:text-rose-300 transition-colors"></i>
                <span class="ml-3 font-semibold">Log Out</span>
              </button>
            </form>
          </li>
        </ul>
      </div>

      <!-- Bottom Sticky Sidenav Toolbar -->
      <div class="hidden absolute bottom-0 left-0 justify-between items-center px-4 py-3 w-full lg:flex bg-[#FDFCF8] dark:bg-stone-900 border-t border-stone-200/80 dark:border-stone-800 z-20">
        <div class="flex items-center gap-2">
          <a href="{{ route('membership.index') }}" class="p-1.5 text-stone-500 rounded hover:text-stone-900 dark:text-stone-400 dark:hover:text-white hover:bg-stone-100 dark:hover:bg-stone-800" title="Pengaturan Akun">
            <i data-lucide="settings" class="w-4 h-4"></i>
          </a>
          <button type="button" onclick="openOnboardingModal()" class="p-1.5 text-stone-500 rounded hover:text-stone-900 dark:text-stone-400 dark:hover:text-white hover:bg-stone-100 dark:hover:bg-stone-800" title="Bantuan">
            <i data-lucide="info" class="w-4 h-4"></i>
          </button>
        </div>
        <span class="text-[11px] font-mono text-stone-400 dark:text-stone-500">v1.0 MVP</span>
      </div>
    </aside>

    <!-- Mobile Backdrop -->
    <div id="sidebar-backdrop" onclick="closeMobileSidebar()" class="fixed inset-0 bg-gray-900/50 z-30 hidden md:hidden transition-opacity cursor-pointer"></div>

    <!-- Main View Content Container -->
    <main id="main-content" class="p-4 h-auto pt-20 flex-grow transition-all duration-300 ease-in-out">
      <!-- Global Alert / Toast Notifications -->
      @if(session('success'))
        <div id="flashToast" class="mb-4">
          <div class="flex items-center p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl dark:bg-gray-800 dark:text-emerald-400 dark:border-emerald-800 shadow-sm" role="alert">
            <i data-lucide="check-circle" class="flex-shrink-0 w-5 h-5 mr-3 text-emerald-600 dark:text-emerald-400"></i>
            <div class="text-sm font-semibold flex-1">
              {{ session('success') }}
            </div>
            <button type="button" onclick="document.getElementById('flashToast').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-emerald-50 text-emerald-500 rounded-lg p-1.5 hover:bg-emerald-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-emerald-400 dark:hover:bg-gray-700">
              <i data-lucide="x" class="w-4 h-4"></i>
            </button>
          </div>
        </div>
      @endif

      @if(session('error'))
        <div id="flashError" class="mb-4">
          <div class="flex items-center p-4 text-red-800 bg-red-50 border border-red-200 rounded-xl dark:bg-gray-800 dark:text-red-400 dark:border-red-800 shadow-sm" role="alert">
            <i data-lucide="alert-circle" class="flex-shrink-0 w-5 h-5 mr-3 text-red-600 dark:text-red-400"></i>
            <div class="text-sm font-semibold flex-1">
              {{ session('error') }}
            </div>
            <button type="button" onclick="document.getElementById('flashError').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700">
              <i data-lucide="x" class="w-4 h-4"></i>
            </button>
          </div>
        </div>
      @endif

      @yield('content')
    </main>
  </div>

  <!-- Interactive Onboarding Modal (PRD Section A - Panduan Edukasi 3 Langkah) -->
  <div id="onboardingModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border border-gray-200 dark:border-gray-700">
      <button onclick="closeOnboardingModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>

      <!-- Slides -->
      <div id="onboardingSlides">
        <!-- Slide 1 -->
        <div class="onboarding-slide text-center space-y-4">
          <div class="w-16 h-16 rounded-2xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center">
            <i data-lucide="scan-line" class="w-8 h-8"></i>
          </div>
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950 px-2.5 py-1 rounded-full">Langkah 1 dari 3</span>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-2">Pindai Sampah via AI</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1.5 leading-relaxed">
              Arahkan kamera ke sampah. AI langsung mengenali jenis material dan estimasi harganya.
            </p>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="onboarding-slide hidden text-center space-y-4">
          <div class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 mx-auto flex items-center justify-center">
            <i data-lucide="truck" class="w-8 h-8"></i>
          </div>
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 px-2.5 py-1 rounded-full">Langkah 2 dari 3</span>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-2">Jemput atau Setor Mandiri</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1.5 leading-relaxed">
              Pesan armada jemput ke rumah atau bawa langsung ke Bank Sampah mitra terdekat.
            </p>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="onboarding-slide hidden text-center space-y-4">
          <div class="w-16 h-16 rounded-2xl bg-pink-100 dark:bg-pink-950 text-pink-600 dark:text-pink-400 mx-auto flex items-center justify-center">
            <i data-lucide="gift" class="w-8 h-8"></i>
          </div>
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-pink-600 dark:text-pink-400 bg-pink-50 dark:bg-pink-950 px-2.5 py-1 rounded-full">Langkah 3 dari 3</span>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-2">Tukar Poin Jadi Hadiah</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1.5 leading-relaxed">
              Kumpulkan poin tiap setoran dan tukar jadi saldo e-wallet, voucher, atau bibit pohon.
            </p>
          </div>
        </div>
      </div>

      <!-- Slide Indicators -->
      <div class="flex items-center justify-center gap-2 my-6">
        <span class="slide-dot w-6 h-2 rounded-full bg-primary-600 transition-all"></span>
        <span class="slide-dot w-2 h-2 rounded-full bg-gray-200 dark:bg-gray-600 transition-all"></span>
        <span class="slide-dot w-2 h-2 rounded-full bg-gray-200 dark:bg-gray-600 transition-all"></span>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-3">
        <button type="button" onclick="prevSlide()" id="btnPrevSlide" class="hidden flex-1 py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
          Kembali
        </button>
        <button type="button" onclick="nextSlide()" id="btnNextSlide" class="flex-1 py-2 px-4 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold shadow-sm transition">
          Lanjut
        </button>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // Initialize Lucide Icons
    lucide.createIcons();

    // Dark Mode Toggle Logic
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    const themeToggleBtn = document.getElementById('theme-toggle');

    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon.classList.remove('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    }

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
                d.className = 'slide-dot w-6 h-2 rounded-full bg-primary-600 transition-all';
            } else {
                d.className = 'slide-dot w-2 h-2 rounded-full bg-gray-200 dark:bg-gray-600 transition-all';
            }
        });

        const prevBtn = document.getElementById('btnPrevSlide');
        const nextBtn = document.getElementById('btnNextSlide');
        if (prevBtn) prevBtn.classList.toggle('hidden', currentSlide === 0);
        if (nextBtn) {
            nextBtn.textContent = currentSlide === slides.length - 1 ? 'Mulai Sekarang' : 'Lanjut';
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

    // Sidebar Open/Close Logic (Desktop & Mobile)
    const sidebar = document.getElementById('drawer-navigation');
    const mainContent = document.getElementById('main-content');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');

    function isDesktop() {
        return window.innerWidth >= 768;
    }

    function initSidebarState() {
        if (!sidebar) return;
        if (isDesktop()) {
            const isCollapsed = localStorage.getItem('olara_sidebar_collapsed') === 'true';
            if (isCollapsed) {
                sidebar.style.transform = 'translateX(-100%)';
                if (mainContent) mainContent.style.marginLeft = '0px';
            } else {
                sidebar.style.transform = 'translateX(0px)';
                if (mainContent) mainContent.style.marginLeft = '16rem';
            }
            sidebarBackdrop?.classList.add('hidden');
        } else {
            // Mobile: closed by default
            sidebar.style.transform = 'translateX(-100%)';
            if (mainContent) mainContent.style.marginLeft = '0px';
            sidebarBackdrop?.classList.add('hidden');
        }
    }

    window.toggleSidebar = function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        if (!sidebar) return;

        if (isDesktop()) {
            const isCurrentlyHidden = sidebar.style.transform === 'translateX(-100%)' || localStorage.getItem('olara_sidebar_collapsed') === 'true';
            if (isCurrentlyHidden) {
                // Open sidebar on desktop
                sidebar.style.transform = 'translateX(0px)';
                if (mainContent) mainContent.style.marginLeft = '16rem';
                localStorage.setItem('olara_sidebar_collapsed', 'false');
            } else {
                // Close sidebar on desktop
                sidebar.style.transform = 'translateX(-100%)';
                if (mainContent) mainContent.style.marginLeft = '0px';
                localStorage.setItem('olara_sidebar_collapsed', 'true');
            }
        } else {
            // Mobile toggle
            const isCurrentlyOpen = sidebar.style.transform === 'translateX(0px)';
            if (isCurrentlyOpen) {
                sidebar.style.transform = 'translateX(-100%)';
                sidebarBackdrop?.classList.add('hidden');
            } else {
                sidebar.style.transform = 'translateX(0px)';
                sidebarBackdrop?.classList.remove('hidden');
            }
        }
    };

    window.closeMobileSidebar = function() {
        if (!isDesktop() && sidebar) {
            sidebar.style.transform = 'translateX(-100%)';
            sidebarBackdrop?.classList.add('hidden');
        }
    };

    window.toggleMarketplaceDropdown = function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const submenu = document.getElementById('marketplace-submenu');
        const chevronWrapper = document.getElementById('marketplace-chevron-wrapper');
        if (!submenu) return;

        const isHidden = submenu.classList.contains('hidden');
        if (isHidden) {
            submenu.classList.remove('hidden');
            chevronWrapper?.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            chevronWrapper?.classList.remove('rotate-180');
        }
    };

    // Initialize sidebar state on page load
    initSidebarState();
    window.addEventListener('resize', function() {
        initSidebarState();
    });
  </script>

  @stack('scripts')
</body>
</html>
