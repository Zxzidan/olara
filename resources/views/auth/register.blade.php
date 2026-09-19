<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun — OLARA</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind & Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f5f4;
        }

        /* Olara Green Radiant Gradient for the Right Panel */
        .olara-gradient-panel {
            background: linear-gradient(135deg, #10b981 0%, #059669 35%, #047857 70%, #064e3b 100%);
        }

        /* Button Green Gradient */
        .olara-btn-gradient {
            background: linear-gradient(90deg, #10b981 0%, #168a5b 50%, #0f766e 100%);
            background-size: 200% auto;
            transition: all 0.3s ease;
        }
        .olara-btn-gradient:hover {
            background-position: right center;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
            transform: translateY(-1px);
        }
        .olara-btn-gradient:active {
            transform: translateY(0);
        }

        /* Outline Button */
        .olara-outline-btn {
            border: 1.5px solid #10b981;
            color: #059669;
            background-color: transparent;
            transition: all 0.2s ease;
        }
        .olara-outline-btn:hover {
            background-color: #10b981;
            border-color: #10b981;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px -3px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-0 sm:p-6 md:p-8 bg-[#f3f5f4]">

    <!-- Main Auth Card Container (Light Mode) -->
    <div class="w-full max-w-5xl lg:max-w-6xl min-h-screen sm:min-h-[680px] bg-white sm:rounded-3xl shadow-2xl shadow-emerald-950/10 overflow-hidden flex flex-col lg:flex-row border border-gray-200/80 my-auto">
        
        <!-- Left Side: White Form Section -->
        <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-14 flex flex-col justify-between bg-white text-gray-900">
            
            <div class="w-full max-w-sm mx-auto my-auto">
                <!-- Brand Logo & Title -->
                <div class="text-center mb-8">
                    <!-- OLARA Logo (Small size) -->
                    <div class="inline-block">
                        <img 
                            src="{{ asset('assets/img/LOGO ORALA.png') }}" 
                            alt="OLARA — Pengelola Sampah Plastik" 
                            class="h-14 sm:h-16 w-auto mx-auto object-contain drop-shadow-sm"
                        />
                    </div>

                    <!-- Team Name (Black/Dark text) -->
                    <h1 class="text-gray-900 text-lg sm:text-xl font-bold tracking-wide mt-3">We are The OLARA Team</h1>
                    <span class="sr-only">Buat Akun Baru</span>
                </div>

                <!-- Subtitle / Instruction (Dark muted text) -->
                <p class="text-gray-600 text-sm sm:text-base font-medium mb-6">Please register an account</p>

                <!-- Session Flash Messages / Errors -->
                @if (session('success'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs shadow-sm">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-1.5">
                                <span class="text-red-500 font-bold">•</span> {{ $error }}
                            </p>
                        @endforeach
                    </div>
                @endif

                <!-- Register Form -->
                <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Hidden or Optional Name derivation (auto-filled if not provided) -->
                    <input type="hidden" name="name" id="name" value="{{ old('name') }}" />

                    <!-- Email Address Input -->
                    <div>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email"
                            placeholder="Email address"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 text-sm placeholder-gray-400 focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition"
                        />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="Password"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 text-sm placeholder-gray-400 focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition"
                        />
                    </div>

                    <!-- Action Button: SIGN UP (Retaining bold white font on green button) -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-4 rounded-lg text-white font-extrabold text-xs sm:text-sm tracking-wider uppercase olara-btn-gradient shadow-lg shadow-emerald-500/25 cursor-pointer"
                        >
                            SIGN UP
                        </button>
                    </div>

                    <!-- Terms and Conditions link -->
                    <div class="text-center pt-2">
                        <a 
                            href="javascript:void(0)" 
                            onclick="document.getElementById('termsModal').classList.remove('hidden')" 
                            class="text-gray-500 hover:text-[#10b981] text-xs font-medium transition duration-150 inline-block py-1"
                        >
                            Terms and conditions
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bottom Row: Switch to Login -->
            <div class="w-full max-w-sm mx-auto pt-8 mt-6 border-t border-gray-100 flex items-center justify-between">
                <span class="text-gray-600 text-xs sm:text-sm font-medium">Have an account?</span>
                <a 
                    href="{{ route('login') }}" 
                    class="px-5 py-2 rounded-lg olara-outline-btn text-xs font-bold uppercase tracking-wider text-center cursor-pointer inline-block"
                >
                    LOGIN
                </a>
            </div>

        </div>

        <!-- Right Side: Green Section with Centered Mascot (IKON2.png) -->
        <div class="w-full lg:w-1/2 p-4 sm:p-6 lg:p-8 flex items-center justify-center olara-gradient-panel relative overflow-hidden min-h-[380px] lg:min-h-full">
            
            <!-- Ambient Subtle Background Glow Elements -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-emerald-950/40 blur-3xl pointer-events-none"></div>

            <!-- Centered Mascot / Logo (IKON2.png) - Prominent, Not Too Small -->
            <div class="relative z-10 w-full flex items-center justify-center p-2">
                <img 
                    src="{{ asset('assets/img/IKON2.png') }}" 
                    alt="Maskot OLARA" 
                    class="w-[300px] sm:w-[380px] md:w-[440px] lg:w-[480px] xl:w-[510px] max-w-full max-h-[520px] sm:max-h-[560px] h-auto object-contain drop-shadow-[0_25px_50px_rgba(0,0,0,0.35)] transition-transform duration-500 hover:scale-105"
                />
            </div>

        </div>

    </div>

    <!-- Self-Contained Terms Modal (Light Theme) -->
    <div id="termsModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-6 max-w-md w-full shadow-2xl text-gray-900">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-emerald-500">📋</span> Syarat & Ketentuan OLARA
                </h3>
                <button type="button" onclick="document.getElementById('termsModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 text-xl font-bold leading-none">&times;</button>
            </div>
            <div class="text-xs text-gray-600 space-y-2.5 max-h-60 overflow-y-auto pr-1 leading-relaxed">
                <p>1. <strong class="text-gray-900">Penggunaan Platform:</strong> OLARA adalah ekosistem digital untuk pengelolaan dan daur ulang sampah berkelanjutan.</p>
                <p>2. <strong class="text-gray-900">Bonus Pengguna Baru:</strong> Setiap registrasi akun baru yang valid secara otomatis menerima 50 Eco-Points yang dapat ditukarkan di Rewards Wallet.</p>
                <p>3. <strong class="text-gray-900">Keamanan Data:</strong> Data kredensial Anda disimpan secara terenkripsi dan aman sesuai standar privasi data nasional.</p>
                <p>4. <strong class="text-gray-900">Integritas Transaksi:</strong> Aktivitas pemilahan dan pengantaran sampah diverifikasi melalui sistem AI dan mitra bank sampah terpercaya.</p>
            </div>
            <div class="mt-5">
                <button type="button" onclick="document.getElementById('termsModal').classList.add('hidden')" class="w-full py-2.5 rounded-lg olara-btn-gradient text-white text-xs font-bold cursor-pointer shadow-md">
                    Saya Setuju & Mengerti
                </button>
            </div>
        </div>
    </div>

</body>
</html>
