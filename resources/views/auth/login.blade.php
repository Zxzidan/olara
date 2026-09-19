<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk Akun — OLARA</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind & Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f1211;
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
            border: 1px solid #10b981;
            color: #34d399;
            transition: all 0.2s ease;
        }
        .olara-outline-btn:hover {
            background-color: rgba(16, 185, 129, 0.15);
            border-color: #34d399;
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-0 sm:p-6 md:p-8 bg-[#0f1211]">

    <!-- Main Auth Card Container -->
    <div class="w-full max-w-5xl lg:max-w-6xl min-h-screen sm:min-h-[680px] bg-[#191c1b] sm:rounded-3xl shadow-2xl shadow-black/80 overflow-hidden flex flex-col lg:flex-row border border-white/5 my-auto">
        
        <!-- Left Side: Dark Form Section -->
        <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-14 flex flex-col justify-between bg-[#191c1b] text-white">
            
            <div class="w-full max-w-sm mx-auto my-auto">
                <!-- Brand Logo & Title -->
                <div class="text-center mb-8">
                    <!-- OLARA Logo (Small size matching previous logo) -->
                    <div class="inline-block">
                        <img 
                            src="{{ asset('assets/img/LOGO ORALA.png') }}" 
                            alt="OLARA — Pengelola Sampah Plastik" 
                            class="h-14 sm:h-16 w-auto mx-auto object-contain drop-shadow-md"
                        />
                    </div>

                    <!-- Team Name -->
                    <h1 class="text-white text-lg sm:text-xl font-bold tracking-wide mt-3">We are The OLARA Team</h1>
                    <span class="sr-only">Masuk ke Akun OLARA</span>
                </div>

                <!-- Subtitle / Instruction -->
                <p class="text-[#d1d5db] text-sm sm:text-base font-medium mb-6">Please login to your account</p>

                <!-- Session Flash Messages / Errors -->
                @if (session('success'))
                    <div class="mb-5 p-3 rounded-lg bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 p-3 rounded-lg bg-red-950/70 border border-red-500/50 text-red-200 text-xs">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-1.5">
                                <span class="text-red-400 font-bold">•</span> {{ $error }}
                            </p>
                        @endforeach
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Email Address Input -->
                    <div>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email', 'zidan@olara.id') }}" 
                            required 
                            autocomplete="email"
                            placeholder="Email address"
                            class="w-full px-4 py-3 bg-[#242826] border border-[#38423d] rounded-lg text-white text-sm placeholder-[#7e8983] focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] transition shadow-inner"
                        />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            value="password123"
                            required 
                            autocomplete="current-password"
                            placeholder="Password"
                            class="w-full px-4 py-3 bg-[#242826] border border-[#38423d] rounded-lg text-white text-sm placeholder-[#7e8983] focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] transition shadow-inner"
                        />
                    </div>

                    <!-- Action Button: LOG IN -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-4 rounded-lg text-white font-extrabold text-xs sm:text-sm tracking-wider uppercase olara-btn-gradient shadow-lg cursor-pointer"
                        >
                            LOG IN
                        </button>
                    </div>

                    <!-- Forgot password link -->
                    <div class="text-center pt-2">
                        <a 
                            href="javascript:void(0)" 
                            onclick="document.getElementById('forgotModal').classList.remove('hidden')" 
                            class="text-zinc-400 hover:text-emerald-400 text-xs transition duration-150 inline-block py-1"
                        >
                            Forgot password?
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bottom Row: Switch to Register -->
            <div class="w-full max-w-sm mx-auto pt-8 mt-6 border-t border-white/5 flex items-center justify-between">
                <span class="text-zinc-300 text-xs sm:text-sm font-medium">Don't have an account?</span>
                <a 
                    href="{{ route('register') }}" 
                    class="px-5 py-2 rounded-lg olara-outline-btn text-xs font-bold uppercase tracking-wider text-center cursor-pointer inline-block"
                >
                    SIGN UP
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

    <!-- Self-Contained Forgot Password Modal (No External Navigation) -->
    <div id="forgotModal" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-[#1c201e] border border-[#38423d] rounded-2xl p-6 max-w-sm w-full shadow-2xl text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i data-lucide="key" class="w-4 h-4 text-emerald-400"></i> Lupa Kata Sandi
                </h3>
                <button type="button" onclick="document.getElementById('forgotModal').classList.add('hidden')" class="text-zinc-400 hover:text-white text-lg leading-none">&times;</button>
            </div>
            <p class="text-xs text-zinc-300 mb-4 leading-relaxed">
                Silakan hubungi administrator sistem atau gunakan kredensial demo untuk masuk:
            </p>
            <div class="p-3 bg-[#242826] rounded-xl border border-[#38423d] text-xs space-y-1 mb-4">
                <p><span class="text-zinc-400">Email:</span> <code class="text-emerald-300 font-mono">zidan@olara.id</code></p>
                <p><span class="text-zinc-400">Password:</span> <code class="text-emerald-300 font-mono">password123</code></p>
            </div>
            <button type="button" onclick="document.getElementById('forgotModal').classList.add('hidden')" class="w-full py-2.5 rounded-lg olara-btn-gradient text-white text-xs font-bold cursor-pointer">
                Mengerti
            </button>
        </div>
    </div>

</body>
</html>
