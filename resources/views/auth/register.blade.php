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

        /* Mascot Floating & Pulse Keyframes */
        @keyframes floatMascot {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-14px) rotate(1deg);
            }
        }
        @keyframes pulseShadow {
            0%, 100% {
                transform: scale(1);
                opacity: 0.35;
            }
            50% {
                transform: scale(0.85);
                opacity: 0.2;
            }
        }
        @keyframes floatBadge1 {
            0%, 100% { transform: translateY(0px) rotate(-3deg); }
            50% { transform: translateY(-8px) rotate(-1deg); }
        }
        @keyframes floatBadge2 {
            0%, 100% { transform: translateY(0px) rotate(4deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }
        @keyframes ambientGlow {
            0%, 100% { opacity: 0.25; transform: scale(1); }
            50% { opacity: 0.45; transform: scale(1.12); }
        }

        .mascot-floating {
            animation: floatMascot 4s ease-in-out infinite;
        }
        .mascot-shadow {
            animation: pulseShadow 4s ease-in-out infinite;
        }
        .badge-floating-1 {
            animation: floatBadge1 3.8s ease-in-out infinite;
        }
        .badge-floating-2 {
            animation: floatBadge2 4.4s ease-in-out infinite 0.5s;
        }
        .ambient-glow {
            animation: ambientGlow 6s ease-in-out infinite;
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

        <!-- Right Side: Green Section with Animated Mascot Carousel (IKON 1, 2, 3) -->
        <div class="w-full lg:w-1/2 p-6 sm:p-8 lg:p-10 flex flex-col items-center justify-center olara-gradient-panel relative overflow-hidden min-h-[440px] lg:min-h-full select-none" id="mascotContainer">
            
            <!-- Ambient Subtle Background Glow Elements -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/20 blur-3xl pointer-events-none ambient-glow"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-emerald-950/50 blur-3xl pointer-events-none ambient-glow"></div>

            <!-- Floating Micro-Badges for 3D depth -->
            <div class="absolute top-6 left-6 lg:top-10 lg:left-10 z-20 badge-floating-1 hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 text-white text-xs font-semibold shadow-lg shadow-emerald-950/20">
                <span class="text-sm">🌱</span> <span>Eco-Friendly</span>
            </div>

            <div class="absolute bottom-14 right-6 lg:bottom-16 lg:right-10 z-20 badge-floating-2 hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 text-white text-xs font-semibold shadow-lg shadow-emerald-950/20">
                <span class="text-sm">♻️</span> <span>100% Recycle</span>
            </div>

            <!-- Mascot Animated Carousel Wrapper -->
            <div class="relative z-10 w-full flex flex-col items-center justify-center my-auto">
                
                <!-- Main Floating Mascot Stage -->
                <div class="relative w-full max-w-[340px] sm:max-w-[420px] lg:max-w-[480px] h-[300px] sm:h-[380px] lg:h-[440px] flex items-center justify-center mascot-floating">
                    
                    <!-- Mascot 1 (IKON1.png) -->
                    <img 
                        id="mascot-img-0"
                        src="{{ asset('assets/img/IKON1.png') }}" 
                        alt="Maskot OLARA 1" 
                        class="mascot-slide absolute inset-0 w-full h-full object-contain drop-shadow-[0_20px_45px_rgba(0,0,0,0.45)] transition-all duration-700 ease-out opacity-100 scale-100 cursor-pointer"
                    />

                    <!-- Mascot 2 (IKON2.png) -->
                    <img 
                        id="mascot-img-1"
                        src="{{ asset('assets/img/IKON2.png') }}" 
                        alt="Maskot OLARA 2" 
                        class="mascot-slide absolute inset-0 w-full h-full object-contain drop-shadow-[0_20px_45px_rgba(0,0,0,0.45)] transition-all duration-700 ease-out opacity-0 scale-90 pointer-events-none cursor-pointer"
                    />

                    <!-- Mascot 3 (IKON3.png) -->
                    <img 
                        id="mascot-img-2"
                        src="{{ asset('assets/img/IKON3.png') }}" 
                        alt="Maskot OLARA 3" 
                        class="mascot-slide absolute inset-0 w-full h-full object-contain drop-shadow-[0_20px_45px_rgba(0,0,0,0.45)] transition-all duration-700 ease-out opacity-0 scale-90 pointer-events-none cursor-pointer"
                    />

                </div>

                <!-- Subtle Dynamic Ground Shadow beneath mascot -->
                <div class="w-48 sm:w-64 h-4 bg-emerald-950/40 rounded-full blur-md mascot-shadow -mt-2 mb-4 pointer-events-none"></div>

                <!-- Pose Title & Subtitle Badge -->
                <div class="text-center z-20 mt-1 px-4">
                    <p id="mascotCaption" class="text-white text-sm sm:text-base font-bold tracking-wide drop-shadow-sm transition-all duration-500">
                        Koleksi & Pilah Sampah
                    </p>
                    <p id="mascotSubCaption" class="text-emerald-100/80 text-xs font-normal mt-0.5 transition-all duration-500">
                        Jadikan sampah bernilai tinggi bersama OLARA
                    </p>
                </div>

                <!-- Carousel Controls / Dot Indicators -->
                <div class="flex items-center gap-2.5 mt-4 z-20">
                    <button 
                        type="button" 
                        onclick="switchMascot(0)" 
                        id="mascot-dot-0" 
                        class="h-2 rounded-full transition-all duration-300 w-8 bg-white shadow-sm cursor-pointer" 
                        aria-label="Tampilkan Karakter 1"
                    ></button>
                    <button 
                        type="button" 
                        onclick="switchMascot(1)" 
                        id="mascot-dot-1" 
                        class="h-2 rounded-full transition-all duration-300 w-2.5 bg-white/40 hover:bg-white/70 shadow-sm cursor-pointer" 
                        aria-label="Tampilkan Karakter 2"
                    ></button>
                    <button 
                        type="button" 
                        onclick="switchMascot(2)" 
                        id="mascot-dot-2" 
                        class="h-2 rounded-full transition-all duration-300 w-2.5 bg-white/40 hover:bg-white/70 shadow-sm cursor-pointer" 
                        aria-label="Tampilkan Karakter 3"
                    ></button>
                </div>

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

    <script>
        const mascotData = [
            {
                title: "Koleksi & Pilah Sampah",
                subtitle: "Kumpulkan sampah plastik bernilai tinggi dengan mudah"
            },
            {
                title: "Daur Ulang Modern & AI",
                subtitle: "Deteksi jenis sampah seketika dengan pemindai cerdas"
            },
            {
                title: "Raih Eco-Points & Hadiah",
                subtitle: "Tukarkan sampahmu jadi saldo uang & voucher belanja"
            }
        ];

        let currentMascot = 0;
        let mascotInterval;

        function switchMascot(index) {
            currentMascot = index;
            for (let i = 0; i < 3; i++) {
                const img = document.getElementById('mascot-img-' + i);
                const dot = document.getElementById('mascot-dot-' + i);
                if (img && dot) {
                    if (i === index) {
                        img.classList.remove('opacity-0', 'scale-90', 'pointer-events-none');
                        img.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
                        dot.classList.remove('w-2.5', 'bg-white/40');
                        dot.classList.add('w-8', 'bg-white');
                    } else {
                        img.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                        img.classList.add('opacity-0', 'scale-90', 'pointer-events-none');
                        dot.classList.remove('w-8', 'bg-white');
                        dot.classList.add('w-2.5', 'bg-white/40');
                    }
                }
            }

            const caption = document.getElementById('mascotCaption');
            const subCaption = document.getElementById('mascotSubCaption');
            if (caption && subCaption) {
                caption.style.opacity = '0';
                caption.style.transform = 'translateY(4px)';
                subCaption.style.opacity = '0';
                subCaption.style.transform = 'translateY(4px)';
                setTimeout(() => {
                    caption.textContent = mascotData[index].title;
                    subCaption.textContent = mascotData[index].subtitle;
                    caption.style.opacity = '1';
                    caption.style.transform = 'translateY(0)';
                    subCaption.style.opacity = '1';
                    subCaption.style.transform = 'translateY(0)';
                }, 200);
            }
        }

        function startMascotCycle() {
            stopMascotCycle();
            mascotInterval = setInterval(() => {
                const next = (currentMascot + 1) % 3;
                switchMascot(next);
            }, 3500);
        }

        function stopMascotCycle() {
            if (mascotInterval) clearInterval(mascotInterval);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('mascotContainer');
            if (container) {
                container.addEventListener('mouseenter', stopMascotCycle);
                container.addEventListener('mouseleave', startMascotCycle);
            }
            startMascotCycle();
        });
    </script>
</body>
</html>
