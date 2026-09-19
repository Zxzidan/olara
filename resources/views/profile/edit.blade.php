@extends('layouts.app')

@section('title', 'Edit Profil & Pengaturan Akun — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

    <!-- Top Header Breadcrumb & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-stone-200/80 dark:border-stone-800 pb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-stone-500 dark:text-stone-400 mb-1.5">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-stone-900 dark:text-white font-medium">Pengaturan Akun</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1B211E] dark:text-white tracking-tight flex items-center gap-2.5">
                <i data-lucide="user-pen" class="w-7 h-7 text-emerald-500"></i>
                Edit Profil Saya
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400 mt-1">
                Kelola identitas diri, kontak penjemputan sampah, foto profil, dan kata sandi akun Anda.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('membership.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-700 dark:text-stone-200 hover:bg-stone-50 dark:hover:bg-stone-700 shadow-xs transition">
                <i data-lucide="crown" class="w-4 h-4 text-amber-500"></i>
                Paket {{ ucfirst($user->membership_tier ?? 'Lite') }}
            </a>
            <a href="{{ route('rewards.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shadow-xs">
                <i data-lucide="gift" class="w-4 h-4 text-emerald-500"></i>
                {{ number_format($user->eco_points ?? 0) }} Poin
            </a>
        </div>
    </div>

    <!-- Main Content Layout (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Column: User Card & Eco Impact Summary (PRD Section 10) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Profile Identity Card -->
            <div class="bg-white dark:bg-stone-900 rounded-3xl border border-stone-200/80 dark:border-stone-800 p-6 shadow-sm text-center relative overflow-hidden">
                <!-- Background Accent Ribbon -->
                <div class="absolute top-0 left-0 right-0 h-20 bg-gradient-to-r from-emerald-500/20 via-teal-500/10 to-amber-500/20 dark:from-emerald-900/30 dark:to-stone-800"></div>

                <div class="relative pt-4">
                    <!-- Avatar Image Display -->
                    <div class="relative inline-block mx-auto mb-3">
                        <img 
                            id="preview-avatar"
                            src="{{ $user->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&auto=format&fit=crop&q=80' }}" 
                            alt="{{ $user->name }}" 
                            class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-white dark:border-stone-900 shadow-md ring-2 ring-emerald-500/30"
                        />
                        <button 
                            type="button" 
                            onclick="document.getElementById('avatar-input').click()"
                            class="absolute bottom-1 right-1 p-2 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white shadow-md transition transform hover:scale-105"
                            title="Ganti Foto Profil"
                        >
                            <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>

                    <h2 class="text-lg font-bold text-stone-900 dark:text-white leading-tight">
                        {{ $user->name }}
                    </h2>
                    <p class="text-xs text-stone-500 dark:text-stone-400 mt-0.5">
                        {{ $user->email }}
                    </p>

                    <!-- Level Badge -->
                    <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold">
                        <i data-lucide="award" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>{{ $user->recycler_level ?? 'Level 1 — Starter' }}</span>
                    </div>

                    @if($user->origin)
                        <p class="text-xs text-stone-500 dark:text-stone-400 mt-2 flex items-center justify-center gap-1">
                            <i data-lucide="map-pin" class="w-3 h-3 text-stone-400"></i>
                            {{ $user->origin }}
                        </p>
                    @endif
                </div>

                <!-- Stats Grid inside card -->
                <div class="mt-6 pt-5 border-t border-stone-100 dark:border-stone-800 grid grid-cols-2 gap-3 text-left">
                    <div class="p-3 rounded-2xl bg-stone-50 dark:bg-stone-800/50 border border-stone-100 dark:border-stone-800">
                        <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block">Penjemputan</span>
                        <div class="text-base font-extrabold text-stone-900 dark:text-white mt-0.5 flex items-center gap-1.5">
                            <i data-lucide="truck" class="w-4 h-4 text-emerald-500"></i>
                            {{ $stats['total_pickups'] }} kali
                        </div>
                    </div>
                    <div class="p-3 rounded-2xl bg-stone-50 dark:bg-stone-800/50 border border-stone-100 dark:border-stone-800">
                        <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block">Scan AI</span>
                        <div class="text-base font-extrabold text-stone-900 dark:text-white mt-0.5 flex items-center gap-1.5">
                            <i data-lucide="scan-line" class="w-4 h-4 text-blue-500"></i>
                            {{ $stats['total_scans'] }} kali
                        </div>
                    </div>
                </div>

                <!-- Personal Waste Goal Note (PRD Section 10) -->
                <div class="mt-4 p-3.5 rounded-2xl bg-[#EEF9F2] dark:bg-emerald-950/30 border border-[#BFE7D0] dark:border-emerald-800/50 text-left">
                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-800 dark:text-emerald-300 mb-1">
                        <i data-lucide="leaf" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Komitmen Nol Sampah</span>
                    </div>
                    <p class="text-[11px] text-emerald-900/80 dark:text-emerald-300/80 leading-relaxed">
                        Data profil yang lengkap memudahkan kurir OLARA menemukan titik alamat penjemputan sampah Anda secara tepat waktu.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Column: Forms Section -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card 1: Data Diri & Kontak Penjemputan -->
            <div class="bg-white dark:bg-stone-900 rounded-3xl border border-stone-200/80 dark:border-stone-800 p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between pb-5 border-b border-stone-100 dark:border-stone-800 mb-6">
                    <div>
                        <h3 class="text-lg font-extrabold text-stone-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="id-card" class="w-5 h-5 text-emerald-600"></i>
                            Informasi Pribadi & Kontak
                        </h3>
                        <p class="text-xs text-stone-500 dark:text-stone-400 mt-0.5">
                            Perbarui identitas akun dan alamat pengiriman / penjemputan armada.
                        </p>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Hidden File Input for Avatar Upload -->
                    <input 
                        type="file" 
                        name="avatar" 
                        id="avatar-input" 
                        accept="image/jpeg,image/png,image/webp" 
                        class="hidden"
                        onchange="handleAvatarFileSelect(this)"
                    />
                    <input type="hidden" name="avatar_preset" id="avatar_preset" value="" />

                    <!-- Quick Preset Avatars Picker -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-2">
                            Pilih Foto Profil Cepat (Atau Unggah Sendiri di Kolom Kiri)
                        </label>
                        <div class="flex items-center gap-3 overflow-x-auto pb-2">
                            @php
                                $presets = [
                                    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
                                    'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=150&auto=format&fit=crop&q=80',
                                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
                                    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80',
                                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=150&auto=format&fit=crop&q=80',
                                    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
                                ];
                            @endphp
                            @foreach($presets as $presetUrl)
                                <button 
                                    type="button" 
                                    onclick="selectPresetAvatar('{{ $presetUrl }}')"
                                    class="w-12 h-12 rounded-full overflow-hidden border-2 border-transparent hover:border-emerald-500 focus:ring-2 focus:ring-emerald-500 shrink-0 transition transform hover:scale-105"
                                >
                                    <img src="{{ $presetUrl }}" alt="Avatar Preset" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                            <button 
                                type="button" 
                                onclick="document.getElementById('avatar-input').click()"
                                class="h-12 px-3 rounded-full border border-dashed border-stone-300 dark:border-stone-700 hover:border-emerald-500 text-stone-600 dark:text-stone-300 text-xs font-semibold flex items-center gap-1.5 shrink-0 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/30 transition"
                            >
                                <i data-lucide="upload" class="w-3.5 h-3.5 text-emerald-600"></i>
                                Unggah File
                            </button>
                        </div>
                        <p id="avatar-file-name" class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-1 hidden"></p>
                        @error('avatar')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name Inputs (First & Last) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">
                                Nama Depan <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="first_name" 
                                id="first_name" 
                                value="{{ old('first_name', $user->first_name ?? explode(' ', $user->name)[0]) }}" 
                                required
                                class="w-full px-4 py-2.5 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-900 dark:text-white text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                                placeholder="Contoh: Dandi"
                            />
                            @error('first_name')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">
                                Nama Belakang
                            </label>
                            <input 
                                type="text" 
                                name="last_name" 
                                id="last_name" 
                                value="{{ old('last_name', $user->last_name ?? (count(explode(' ', $user->name)) > 1 ? implode(' ', array_slice(explode(' ', $user->name), 1)) : '')) }}" 
                                class="w-full px-4 py-2.5 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-900 dark:text-white text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                                placeholder="Contoh: Azaidane"
                            />
                            @error('last_name')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email & Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">
                                Alamat Surel (Email) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                </span>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    value="{{ old('email', $user->email) }}" 
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-900 dark:text-white text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                                />
                            </div>
                            @error('email')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">
                                Nomor WhatsApp / Telepon
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="phone" 
                                    id="phone" 
                                    value="{{ old('phone', $user->phone) }}" 
                                    placeholder="081234567890"
                                    class="w-full pl-10 pr-4 py-2.5 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-900 dark:text-white text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                                />
                            </div>
                            @error('phone')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Asal Kota / Instansi / Komunitas -->
                    <div>
                        <label for="origin" class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">
                            Asal / Daerah / Komunitas
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                <i data-lucide="building" class="w-4 h-4"></i>
                            </span>
                            <input 
                                type="text" 
                                name="origin" 
                                id="origin" 
                                value="{{ old('origin', $user->origin) }}" 
                                placeholder="Contoh: Jakarta Selatan / Komunitas Eco Living"
                                class="w-full pl-10 pr-4 py-2.5 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-900 dark:text-white text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                            />
                        </div>
                        @error('origin')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Lengkap Penjemputan -->
                    <div>
                        <label for="address" class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">
                            Alamat Lengkap (Default Penjemputan Armada)
                        </label>
                        <textarea 
                            name="address" 
                            id="address" 
                            rows="3" 
                            placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, dan patokan lokasi untuk memudahkan armada Olara..."
                            class="w-full px-4 py-2.5 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-900 dark:text-white text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                        >{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Button: Simpan Perubahan Profil -->
                    <div class="pt-2 flex items-center justify-end">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 min-h-[44px] rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs tracking-wide shadow-md shadow-emerald-600/20 transition cursor-pointer"
                        >
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Keamanan & Ganti Kata Sandi -->
            <div class="bg-white dark:bg-stone-900 rounded-3xl border border-stone-200/80 dark:border-stone-800 p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between pb-5 border-b border-stone-100 dark:border-stone-800 mb-6">
                    <div>
                        <h3 class="text-lg font-extrabold text-stone-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-600"></i>
                            Keamanan & Kata Sandi
                        </h3>
                        <p class="text-xs text-stone-500 dark:text-stone-400 mt-0.5">
                            Gunakan kata sandi yang kuat dengan minimal 8 karakter untuk melindungi akun Anda.
                        </p>
                    </div>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Kata Sandi Saat Ini -->
                    <div>
                        <label for="current_password" class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="current_password" 
                                id="current_password" 
                                required
                                placeholder="Masukkan kata sandi saat ini"
                                class="w-full pl-4 pr-11 py-2.5 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-900 dark:text-white text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                            />
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('current_password')"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 focus:outline-none"
                            >
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kata Sandi Baru & Konfirmasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    required
                                    placeholder="Minimal 8 karakter"
                                    class="w-full pl-4 pr-11 py-2.5 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-900 dark:text-white text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                                />
                                <button 
                                    type="button" 
                                    onclick="togglePasswordVisibility('password')"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 focus:outline-none"
                                >
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">
                                Ulangi Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    required
                                    placeholder="Ketik ulang kata sandi baru"
                                    class="w-full pl-4 pr-11 py-2.5 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl text-stone-900 dark:text-white text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                                />
                                <button 
                                    type="button" 
                                    onclick="togglePasswordVisibility('password_confirmation')"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 focus:outline-none"
                                >
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button: Perbarui Kata Sandi -->
                    <div class="pt-3 flex items-center justify-end">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 min-h-[44px] rounded-xl bg-stone-800 hover:bg-stone-900 active:bg-black text-white dark:bg-stone-700 dark:hover:bg-stone-600 font-bold text-xs tracking-wide shadow-sm transition cursor-pointer"
                        >
                            <i data-lucide="lock" class="w-4 h-4"></i>
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>

<script>
function handleAvatarFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-avatar').src = e.target.result;
            const fileNameElem = document.getElementById('avatar-file-name');
            fileNameElem.textContent = 'File dipilih: ' + file.name;
            fileNameElem.classList.remove('hidden');
            document.getElementById('avatar_preset').value = '';
        };
        
        reader.readAsDataURL(file);
    }
}

function selectPresetAvatar(url) {
    document.getElementById('preview-avatar').src = url;
    document.getElementById('avatar_preset').value = url;
    document.getElementById('avatar-input').value = '';
    const fileNameElem = document.getElementById('avatar-file-name');
    fileNameElem.textContent = 'Avatar preset dipilih';
    fileNameElem.classList.remove('hidden');
}

function togglePasswordVisibility(fieldId) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>
@endsection
