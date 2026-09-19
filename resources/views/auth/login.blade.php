@extends('layouts.app')

@section('title', ($mode === 'register' ? 'Daftar Akun' : 'Masuk') . ' — OLARA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-md mx-auto">

        <!-- Welcome Bonus Pill Banner -->
        <div class="bg-[#EEF9F2] border border-[#BFE7D0] p-4 rounded-2xl mb-6 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#168A5B] text-white flex items-center justify-center shrink-0 text-lg">
                🎁
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#168A5B]">Bonus Pengguna Baru</span>
                <p class="text-xs text-[#0B4F38] font-medium leading-tight mt-0.5">
                    Dapatkan <strong>50 Eco-Points otomatis</strong> saat Anda mendaftarkan akun baru hari ini!
                </p>
            </div>
        </div>

        <!-- Auth Card -->
        <div class="bg-white border border-[#DDE3DF] rounded-3xl p-6 sm:p-8 shadow-sm">
            <!-- Mode Toggle Tabs -->
            <div class="flex rounded-xl bg-[#F7F8F6] p-1 mb-6">
                <button type="button" onclick="switchAuthTab('login')" id="tabLoginBtn" class="flex-1 py-2 text-xs font-bold rounded-lg transition {{ $mode === 'login' ? 'bg-white text-[#0B4F38] shadow-sm' : 'text-[#66716B]' }}">
                    Masuk
                </button>
                <button type="button" onclick="switchAuthTab('register')" id="tabRegisterBtn" class="flex-1 py-2 text-xs font-bold rounded-lg transition {{ $mode === 'register' ? 'bg-white text-[#0B4F38] shadow-sm' : 'text-[#66716B]' }}">
                    Daftar (+50 Pts)
                </button>
            </div>

            <!-- Login Form -->
            <div id="loginFormContainer" class="{{ $mode === 'login' ? '' : 'hidden' }}">
                <div class="mb-6">
                    <h2 class="text-xl font-extrabold text-[#1B211E]">Masuk ke Akun OLARA</h2>
                    <p class="text-xs text-[#66716B] mt-1">Lanjutkan pengelolaan sampah dan pantau poin Anda.</p>
                </div>

                <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="login_email" class="block text-xs font-bold text-[#1B211E] mb-1.5">Alamat Surel</label>
                        <input type="email" name="email" id="login_email" value="{{ old('email', 'zidan@olara.id') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-sm focus:outline-none focus:ring-2 focus:ring-[#168A5B] focus:border-transparent transition" placeholder="nama@email.com" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="login_password" class="block text-xs font-bold text-[#1B211E]">Kata Sandi</label>
                            <span class="text-[11px] text-[#168A5B] hover:underline cursor-pointer">Lupa kata sandi?</span>
                        </div>
                        <input type="password" name="password" id="login_password" value="password123" required class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-sm focus:outline-none focus:ring-2 focus:ring-[#168A5B] focus:border-transparent transition" placeholder="••••••••" />
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-[#168A5B] rounded border-gray-300 focus:ring-[#168A5B]" checked />
                        <label for="remember" class="ml-2 text-xs text-[#66716B]">Ingat saya di perangkat ini</label>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-sm font-bold shadow-sm transition">
                        Masuk ke Akun
                    </button>
                </form>

                <!-- Fast Demo Login Button -->
                <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                    <a href="{{ route('demo.login') }}" class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-[#EEF9F2] hover:bg-[#DDF4E8] text-[#0B4F38] text-xs font-bold border border-[#BFE7D0] transition">
                        <i data-lucide="zap" class="w-4 h-4 text-[#168A5B]"></i> 1-Klik Masuk sebagai Akun Demo (Zidan Ramadhan)
                    </a>
                </div>
            </div>

            <!-- Register Form -->
            <div id="registerFormContainer" class="{{ $mode === 'register' ? '' : 'hidden' }}">
                <div class="mb-6">
                    <h2 class="text-xl font-extrabold text-[#1B211E]">Buat Akun Baru</h2>
                    <p class="text-xs text-[#66716B] mt-1">Dapatkan instan <strong>50 Eco-Points</strong> setelah registrasi!</p>
                </div>

                <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="reg_name" class="block text-xs font-bold text-[#1B211E] mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="reg_name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-sm focus:outline-none focus:ring-2 focus:ring-[#168A5B] focus:border-transparent transition" placeholder="Contoh: Budi Pratama" />
                    </div>

                    <div>
                        <label for="reg_email" class="block text-xs font-bold text-[#1B211E] mb-1.5">Alamat Surel</label>
                        <input type="email" name="email" id="reg_email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-sm focus:outline-none focus:ring-2 focus:ring-[#168A5B] focus:border-transparent transition" placeholder="nama@email.com" />
                    </div>

                    <div>
                        <label for="reg_phone" class="block text-xs font-bold text-[#1B211E] mb-1.5">Nomor WhatsApp / Ponsel</label>
                        <input type="text" name="phone" id="reg_phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-sm focus:outline-none focus:ring-2 focus:ring-[#168A5B] focus:border-transparent transition" placeholder="0812xxxxxxxx" />
                    </div>

                    <div>
                        <label for="reg_password" class="block text-xs font-bold text-[#1B211E] mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" id="reg_password" required class="w-full px-4 py-2.5 rounded-xl border border-[#DDE3DF] text-sm focus:outline-none focus:ring-2 focus:ring-[#168A5B] focus:border-transparent transition" placeholder="Minimal 6 karakter" />
                    </div>

                    <div class="text-[11px] text-[#66716B] leading-relaxed">
                        Dengan mendaftar, Anda menyetujui <span class="text-[#168A5B] underline">Syarat & Ketentuan</span> serta <span class="text-[#168A5B] underline">Kebijakan Privasi</span> OLARA.
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#168A5B] hover:bg-[#0F6B47] text-white text-sm font-bold shadow-sm transition">
                        Daftar & Klaim 50 Poin
                    </button>
                </form>
            </div>

            <!-- Social Logins (PRD Spec) -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-center text-xs text-gray-400 font-medium mb-4">Atau lanjutkan dengan akun mitra</p>
                <div class="grid grid-cols-3 gap-2.5">
                    <button type="button" class="py-2.5 px-3 rounded-xl border border-[#DDE3DF] text-xs font-bold text-[#1B211E] hover:bg-gray-50 flex items-center justify-center gap-1.5 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                        Google
                    </button>
                    <button type="button" class="py-2.5 px-3 rounded-xl border border-[#DDE3DF] text-xs font-bold text-[#1B211E] hover:bg-gray-50 flex items-center justify-center gap-1.5 transition">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.37c.62-.75 1.04-1.8 0.93-2.85-.9.04-1.98.6-2.62 1.34-.56.64-1.06 1.7-0.93 2.72 1 .08 2-.46 2.62-1.21z"/></svg>
                        Apple
                    </button>
                    <button type="button" class="py-2.5 px-3 rounded-xl border border-[#DDE3DF] text-xs font-bold text-[#1B211E] hover:bg-gray-50 flex items-center justify-center gap-1.5 transition">
                        <svg class="w-4 h-4 fill-[#1877F2]" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function switchAuthTab(mode) {
        const loginContainer = document.getElementById('loginFormContainer');
        const registerContainer = document.getElementById('registerFormContainer');
        const tabLogin = document.getElementById('tabLoginBtn');
        const tabRegister = document.getElementById('tabRegisterBtn');

        if (mode === 'login') {
            loginContainer.classList.remove('hidden');
            registerContainer.classList.add('hidden');
            tabLogin.className = 'flex-1 py-2 text-xs font-bold rounded-lg transition bg-white text-[#0B4F38] shadow-sm';
            tabRegister.className = 'flex-1 py-2 text-xs font-bold rounded-lg transition text-[#66716B]';
        } else {
            loginContainer.classList.add('hidden');
            registerContainer.classList.remove('hidden');
            tabRegister.className = 'flex-1 py-2 text-xs font-bold rounded-lg transition bg-white text-[#0B4F38] shadow-sm';
            tabLogin.className = 'flex-1 py-2 text-xs font-bold rounded-lg transition text-[#66716B]';
        }
    }
</script>
@endsection
