<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SwiftPOS — All-in-One POS for Every Business</title>
    <meta name="description" content="Powerful POS system for marts, restaurants, cafes, retail stores & clinics. Inventory, billing, reports — everything in one place. Start free.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }

        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-glow {
            background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(59, 130, 246, 0.15), transparent);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(59, 130, 246, 0.15);
        }

        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            transition: all 0.7s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .video-glow {
            box-shadow: 0 0 80px 20px rgba(59, 130, 246, 0.2),
                        0 0 160px 40px rgba(59, 130, 246, 0.1);
        }

        .stat-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .feature-icon {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 0.5; }
            100% { transform: scale(1.8); opacity: 0; }
        }
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid currentColor;
            animation: pulse-ring 2s ease-out infinite;
        }

        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255, 0.25), transparent);
            transition: left 0.5s ease;
        }
        .btn-shine:hover::before {
            left: 120%;
        }

        .industry-card {
            transition: all 0.3s ease;
        }
        .industry-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(0,0,0,0.1);
            border-color: rgba(59, 130, 246, 0.2);
        }
        .industry-card:hover .industry-icon {
            transform: scale(1.1);
        }
        .industry-icon {
            transition: transform 0.3s ease;
        }

        .section-gap { padding-top: 7rem; padding-bottom: 7rem; }

        .dot-pattern {
            background-image: radial-gradient(rgba(148, 163, 184, 0.15) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .glass-card {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.8);
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .marquee-track {
            animation: marquee 30s linear infinite;
        }
        .marquee-track:hover {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased overflow-x-hidden">

    <!-- ==================== NAVBAR ==================== -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/80 backdrop-blur-xl border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="flex items-center justify-between h-[72px]">
                <a href="/" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 bg-brand-500 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/25 group-hover:shadow-brand-500/40 transition-shadow">
                        <i class="fa-solid fa-bolt text-white text-sm"></i>
                    </div>
                    <span class="font-extrabold text-gray-900 text-lg tracking-tight">SwiftPOS</span>
                </a>

                <div class="hidden lg:flex items-center gap-1">
                    <a href="#industries" class="px-4 py-2 text-[13px] font-medium text-gray-500 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition">Industries</a>
                    <a href="#features" class="px-4 py-2 text-[13px] font-medium text-gray-500 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition">Features</a>
                    <a href="#how-it-works" class="px-4 py-2 text-[13px] font-medium text-gray-500 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition">How It Works</a>
                    <a href="#pricing" class="px-4 py-2 text-[13px] font-medium text-gray-500 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition">Pricing</a>
                    <a href="#contact" class="px-4 py-2 text-[13px] font-medium text-gray-500 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition">Contact</a>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('trial.form') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-[13px] font-semibold rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/20 btn-shine">
                        Free Trial
                    </a>
                    <button onclick="toggleMobileMenu()" class="lg:hidden p-2.5 rounded-xl text-gray-500 hover:bg-gray-50 transition">
                        <svg id="menuIcon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        <svg id="closeIcon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 shadow-xl shadow-gray-900/5">
            <div class="max-w-7xl mx-auto px-5 py-4 space-y-1">
                <a href="#industries" onclick="closeMobileMenu()" class="block px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition">Industries</a>
                <a href="#features" onclick="closeMobileMenu()" class="block px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition">Features</a>
                <a href="#how-it-works" onclick="closeMobileMenu()" class="block px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition">How It Works</a>
                <a href="#pricing" onclick="closeMobileMenu()" class="block px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition">Pricing</a>
                <a href="#contact" onclick="closeMobileMenu()" class="block px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition">Contact</a>
                <div class="flex gap-3 pt-3 border-t border-gray-100 mt-3">
                    <a href="{{ route('platform.login') }}" class="flex-1 text-center px-4 py-3 text-sm font-semibold text-gray-700 bg-gray-50 rounded-xl hover:bg-gray-100 transition">Login</a>
                    <a href="{{ route('trial.form') }}" class="flex-1 text-center px-4 py-3 text-sm font-semibold text-white bg-brand-600 rounded-xl hover:bg-brand-700 transition">Free Trial</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ==================== HERO ==================== -->
    <section class="relative min-h-screen flex items-center overflow-hidden bg-gray-950">
        <div class="absolute inset-0 hero-glow"></div>
        <div class="absolute top-20 left-10 w-72 h-72 bg-brand-500/10 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-400/8 rounded-full blur-3xl float-animation" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 right-1/3 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl float-animation" style="animation-delay: -1.5s;"></div>

        <div class="relative max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 pt-32 pb-20">
            <div class="grid lg:grid-cols-2 gap-14 lg:gap-20 items-center">

                <div class="fade-up">
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-white/5 border border-white/10 rounded-full mb-8">
                        <span class="relative w-2 h-2 rounded-full bg-emerald-400 pulse-ring text-emerald-400"></span>
                        <span class="text-gray-300 text-xs font-semibold uppercase tracking-widest">POS for Every Business</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] xl:text-6xl font-extrabold text-white leading-[1.08] tracking-tight">
                        One POS System<br>
                        <span class="gradient-text">For Every Business</span>
                    </h1>

                    <p class="mt-7 text-lg text-gray-400 max-w-lg leading-relaxed">
                        Marts, restaurants, cafes, retail stores, clinics — one powerful POS to run them all. Fast billing, live inventory, real-time reports.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3.5 mt-10">
                        <a href="{{ route('trial.form') }}" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-brand-600 text-white text-sm font-bold rounded-2xl hover:bg-brand-700 transition-all shadow-2xl shadow-brand-600/30 hover:shadow-brand-600/40 hover:-translate-y-0.5 btn-shine">
                            Start Free 14-Day Trial
                        </a>
                        <a href="#" onclick="openVideo()" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-white/5 text-white text-sm font-semibold rounded-2xl border border-white/10 hover:bg-white/10 hover:border-white/20 backdrop-blur-sm transition-all">
                            <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-play text-brand-400 text-[10px] ml-0.5"></i>
                            </div>
                            Watch Demo
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 mt-12">
                        <div class="stat-card rounded-2xl px-5 py-4">
                            <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest mb-1">Businesses</p>
                            <p class="text-white text-2xl font-extrabold">500+</p>
                        </div>
                        <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                        <div class="stat-card rounded-2xl px-5 py-4">
                            <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest mb-1">Transactions/Mo</p>
                            <p class="text-white text-2xl font-extrabold">2M+</p>
                        </div>
                        <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                        <div class="stat-card rounded-2xl px-5 py-4">
                            <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest mb-1">Uptime</p>
                            <p class="text-white text-2xl font-extrabold">99.9%</p>
                        </div>
                    </div>
                </div>

                <!-- Right: POS Mockup -->
                <div class="fade-up hidden lg:block" style="transition-delay: 0.2s">
                    <div class="video-glow rounded-3xl overflow-hidden border border-white/10">
                        <div class="relative aspect-video bg-gray-800 rounded-3xl">
                            <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&q=80" alt="POS Dashboard" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-transparent"></div>
                            <button onclick="openVideo()" class="absolute inset-0 flex items-center justify-center group cursor-pointer">
                                <div class="w-20 h-12 bg-brand-600/90 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-2xl shadow-brand-600/40">
                                    <i class="fa-solid fa-play text-white text-xl ml-1"></i>
                                </div>
                            </button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== TRUSTED BY (Marquee) ==================== -->
    <section class="py-14 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <p class="text-center text-xs font-bold uppercase tracking-widest text-gray-400 mb-10">Trusted across industries</p>

            <div class="overflow-hidden relative">
                <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-white to-transparent z-10"></div>
                <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-white to-transparent z-10"></div>
                <div class="flex marquee-track" style="width: max-content;">
                    <div class="flex items-center gap-6 px-3">
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-cart-shopping text-orange-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">SuperMart</p><p class="text-[10px] text-gray-400">Grocery Chain</p></div>
                        </div>
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-utensils text-red-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">Spice Kitchen</p><p class="text-[10px] text-gray-400">Restaurant</p></div>
                        </div>
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-mug-hot text-amber-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">Brew & Bean</p><p class="text-[10px] text-gray-400">Cafe Chain</p></div>
                        </div>
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-shirt text-pink-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">StyleHub</p><p class="text-[10px] text-gray-400">Retail Store</p></div>
                        </div>
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-heart-pulse text-emerald-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">MediCare</p><p class="text-[10px] text-gray-400">Clinic</p></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-6 px-3" aria-hidden="true">
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-cart-shopping text-orange-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">SuperMart</p><p class="text-[10px] text-gray-400">Grocery Chain</p></div>
                        </div>
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-utensils text-red-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">Spice Kitchen</p><p class="text-[10px] text-gray-400">Restaurant</p></div>
                        </div>
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-mug-hot text-amber-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">Brew & Bean</p><p class="text-[10px] text-gray-400">Cafe Chain</p></div>
                        </div>
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-shirt text-pink-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">StyleHub</p><p class="text-[10px] text-gray-400">Retail Store</p></div>
                        </div>
                        <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 flex-shrink-0">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-heart-pulse text-emerald-500"></i></div>
                            <div><p class="font-bold text-gray-900 text-sm">MediCare</p><p class="text-[10px] text-gray-400">Clinic</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== INDUSTRIES ==================== -->
    <section id="industries" class="section-gap bg-gray-50/70 dot-pattern">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-blue-50 rounded-full mb-5 border border-blue-100">
                    <i class="fa-solid fa-building text-blue-600 text-xs"></i>
                    <span class="text-blue-700 text-xs font-bold uppercase tracking-widest">Built For Your Industry</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">One POS, every industry covered</h2>
                <p class="text-gray-500 mt-4 text-lg max-w-2xl mx-auto leading-relaxed">Industry-specific modules that understand your business. Not a generic tool — a tailored solution.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">

                <div class="industry-card bg-white rounded-2xl p-7 border border-gray-200/80 fade-up cursor-pointer">
                    <div class="flex items-start gap-4">
                        <div class="industry-icon w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-cart-shopping text-orange-600 text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-gray-900 mb-1.5">Marts & Grocery</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-3">Barcode scanning, bulk pricing, expiry tracking, weigh-scale integration, GST billing.</p>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="text-[10px] font-semibold bg-orange-50 text-orange-700 px-2.5 py-1 rounded-full border border-orange-100">Barcode</span>
                                <span class="text-[10px] font-semibold bg-orange-50 text-orange-700 px-2.5 py-1 rounded-full border border-orange-100">GST</span>
                                <span class="text-[10px] font-semibold bg-orange-50 text-orange-700 px-2.5 py-1 rounded-full border border-orange-100">Expiry Alert</span>
                                <span class="text-[10px] font-semibold bg-orange-50 text-orange-700 px-2.5 py-1 rounded-full border border-orange-100">Weigh Scale</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="industry-card bg-white rounded-2xl p-7 border border-gray-200/80 fade-up cursor-pointer" style="transition-delay:0.05s">
                    <div class="flex items-start gap-4">
                        <div class="industry-icon w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-utensils text-red-600 text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-gray-900 mb-1.5">Restaurants</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-3">Table management, KOT to kitchen, split bills, waiter app, menu modifiers.</p>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="text-[10px] font-semibold bg-red-50 text-red-700 px-2.5 py-1 rounded-full border border-red-100">KOT</span>
                                <span class="text-[10px] font-semibold bg-red-50 text-red-700 px-2.5 py-1 rounded-full border border-red-100">Tables</span>
                                <span class="text-[10px] font-semibold bg-red-50 text-red-700 px-2.5 py-1 rounded-full border border-red-100">Split Bill</span>
                                <span class="text-[10px] font-semibold bg-red-50 text-red-700 px-2.5 py-1 rounded-full border border-red-100">Waiter App</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="industry-card bg-white rounded-2xl p-7 border border-gray-200/80 fade-up cursor-pointer" style="transition-delay:0.1s">
                    <div class="flex items-start gap-4">
                        <div class="industry-icon w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-mug-hot text-amber-600 text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-gray-900 mb-1.5">Cafes & Bakeries</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-3">Quick counter billing, recipe management, customizations, loyalty points.</p>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="text-[10px] font-semibold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full border border-amber-100">Quick Bill</span>
                                <span class="text-[10px] font-semibold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full border border-amber-100">Recipes</span>
                                <span class="text-[10px] font-semibold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full border border-amber-100">Loyalty</span>
                                <span class="text-[10px] font-semibold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full border border-amber-100">Customize</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="industry-card bg-white rounded-2xl p-7 border border-gray-200/80 fade-up cursor-pointer" style="transition-delay:0.15s">
                    <div class="flex items-start gap-4">
                        <div class="industry-icon w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-shirt text-pink-600 text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-gray-900 mb-1.5">Retail & Fashion</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-3">Variant management (size/color), SKU tracking, return exchanges, customer CRM.</p>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="text-[10px] font-semibold bg-pink-50 text-pink-700 px-2.5 py-1 rounded-full border border-pink-100">Variants</span>
                                <span class="text-[10px] font-semibold bg-pink-50 text-pink-700 px-2.5 py-1 rounded-full border border-pink-100">SKU</span>
                                <span class="text-[10px] font-semibold bg-pink-50 text-pink-700 px-2.5 py-1 rounded-full border border-pink-100">Returns</span>
                                <span class="text-[10px] font-semibold bg-pink-50 text-pink-700 px-2.5 py-1 rounded-full border border-pink-100">CRM</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="industry-card bg-white rounded-2xl p-7 border border-gray-200/80 fade-up cursor-pointer" style="transition-delay:0.2s">
                    <div class="flex items-start gap-4">
                        <div class="industry-icon w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-heart-pulse text-emerald-600 text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-gray-900 mb-1.5">Clinics & Pharmacy</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-3">OPD queue, digital prescriptions, pharmacy POS, patient records, doctor management.</p>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="text-[10px] font-semibold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full border border-emerald-100">OPD Queue</span>
                                <span class="text-[10px] font-semibold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full border border-emerald-100">Prescriptions</span>
                                <span class="text-[10px] font-semibold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full border border-emerald-100">Pharmacy</span>
                                <span class="text-[10px] font-semibold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full border border-emerald-100">Patients</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="industry-card bg-white rounded-2xl p-7 border border-gray-200/80 fade-up cursor-pointer" style="transition-delay:0.25s">
                    <div class="flex items-start gap-4">
                        <div class="industry-icon w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-store text-violet-600 text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-gray-900 mb-1.5">General Stores</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-3">Fast billing, credit customer accounts, daily purchase tracking, simple inventory.</p>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="text-[10px] font-semibold bg-violet-50 text-violet-700 px-2.5 py-1 rounded-full border border-violet-100">Fast POS</span>
                                <span class="text-[10px] font-semibold bg-violet-50 text-violet-700 px-2.5 py-1 rounded-full border border-violet-100">Credit</span>
                                <span class="text-[10px] font-semibold bg-violet-50 text-violet-700 px-2.5 py-1 rounded-full border border-violet-100">Purchase</span>
                                <span class="text-[10px] font-semibold bg-violet-50 text-violet-700 px-2.5 py-1 rounded-full border border-violet-100">Simple UI</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ==================== CORE FEATURES ==================== -->
    <section id="features" class="section-gap bg-white">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-blue-50 rounded-full mb-5 border border-blue-100">
                    <i class="fa-solid fa-table-cells text-blue-600 text-xs"></i>
                    <span class="text-blue-700 text-xs font-bold uppercase tracking-widest">Core Features</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Everything a POS should have</h2>
                <p class="text-gray-500 mt-4 text-lg max-w-2xl mx-auto leading-relaxed">Powerful features that work out of the box. No add-ons, no extra charges.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">

                <div class="bg-white rounded-2xl p-7 border border-gray-200/80 card-hover fade-up group">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-bolt text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-[15px] font-bold text-gray-900 mb-2">Lightning Fast Billing</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Scan, tap, bill — under 3 seconds per transaction. Even on slow internet.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 border border-gray-200/80 card-hover fade-up group" style="transition-delay:0.05s">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-boxes-stacked text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-[15px] font-bold text-gray-900 mb-2">Live Inventory</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Real-time stock levels across locations. Low stock alerts, auto-purchase suggestions.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 border border-gray-200/80 card-hover fade-up group" style="transition-delay:0.1s">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-chart-pie text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-[15px] font-bold text-gray-900 mb-2">Reports & Analytics</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Daily sales, profit margins, top items, hour-wise analysis — all on dashboard.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 border border-gray-200/80 card-hover fade-up group" style="transition-delay:0.15s">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-barcode text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-[15px] font-bold text-gray-900 mb-2">Barcode & QR</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Scan barcodes, generate QR codes, support all major barcode formats.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 border border-gray-200/80 card-hover fade-up group" style="transition-delay:0.2s">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-users text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-[15px] font-bold text-gray-900 mb-2">Multi-User & Roles</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Cashier, manager, owner — each with specific permissions. Shift tracking included.</p>
                </div>

                <div class="bg-white rounded-2xl p-7 border border-gray-200/80 card-hover fade-up group" style="transition-delay:0.25s">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-receipt text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-[15px] font-bold text-gray-900 mb-2">Thermal Receipt Print</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Direct print to any thermal printer. Custom receipt templates. No drivers needed.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ==================== POS BILLING SCREEN ==================== -->
    <section class="section-gap bg-gray-50/70">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-center">

                <div class="fade-up">
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-emerald-50 rounded-full mb-6 border border-emerald-100">
                        <i class="fa-solid fa-cash-register text-emerald-600 text-xs"></i>
                        <span class="text-emerald-700 text-xs font-bold uppercase tracking-widest">POS Screen</span>
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-5 tracking-tight">Billing That Never Slows Down</h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-10">Clean interface, instant search, keyboard shortcuts — designed for speed. Your cashier will love it.</p>

                    <ul class="space-y-5">
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 bg-emerald-100 rounded-lg flex items-center justify-center mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Instant Product Search</p>
                                <p class="text-gray-500 text-sm mt-0.5">Type name, barcode, or SKU — results in under 100ms.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 bg-emerald-100 rounded-lg flex items-center justify-center mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Keyboard Shortcuts</p>
                                <p class="text-gray-500 text-sm mt-0.5">F1-F12 for quick actions. No mouse needed for fast billing.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 bg-emerald-100 rounded-lg flex items-center justify-center mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Hold & Split Bills</p>
                                <p class="text-gray-500 text-sm mt-0.5">Put a bill on hold, serve next customer, come back later.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 bg-emerald-100 rounded-lg flex items-center justify-center mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Multiple Payment Methods</p>
                                <p class="text-gray-500 text-sm mt-0.5">Cash, card, UPI, bank transfer, split payment — all supported.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Right: POS Mockup -->
                <div class="fade-up hidden lg:block" style="transition-delay: 0.15s;">
                    <div class="relative">
                        <div class="bg-white rounded-2xl shadow-2xl shadow-gray-300/30 border border-gray-200/80 p-5">
                            <div class="bg-gray-900 rounded-xl p-5">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                                        <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                                    </div>
                                    <h4 class="font-bold text-white text-xs">SwiftPOS — Billing</h4>
                                    <span class="text-[10px] bg-emerald-500/20 text-emerald-400 font-bold px-2.5 py-1 rounded-full">Terminal 1</span>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div class="col-span-2 space-y-2">
                                        <div class="flex items-center justify-between bg-white/5 rounded-lg px-3 py-2.5 border border-white/10">
                                            <div>
                                                <p class="text-xs font-medium text-white">Basmati Rice 5kg</p>
                                                <p class="text-[10px] text-gray-500">1 × Rs. 450</p>
                                            </div>
                                            <span class="text-xs font-semibold text-white">Rs. 450</span>
                                        </div>
                                        <div class="flex items-center justify-between bg-white/5 rounded-lg px-3 py-2.5 border border-white/10">
                                            <div>
                                                <p class="text-xs font-medium text-white">Tandoori Atta 10kg</p>
                                                <p class="text-[10px] text-gray-500">1 × Rs. 680</p>
                                            </div>
                                            <span class="text-xs font-semibold text-white">Rs. 680</span>
                                        </div>
                                        <div class="flex items-center justify-between bg-white/5 rounded-lg px-3 py-2.5 border border-white/10">
                                            <div>
                                                <p class="text-xs font-medium text-white">Amul Butter 500g</p>
                                                <p class="text-[10px] text-gray-500">2 × Rs. 280</p>
                                            </div>
                                            <span class="text-xs font-semibold text-white">Rs. 560</span>
                                        </div>
                                        <div class="flex items-center justify-between bg-white/5 rounded-lg px-3 py-2.5 border border-white/10">
                                            <div>
                                                <p class="text-xs font-medium text-white">Sugar 1kg</p>
                                                <p class="text-[10px] text-gray-500">1 × Rs. 120</p>
                                            </div>
                                            <span class="text-xs font-semibold text-white">Rs. 120</span>
                                        </div>

                                        <div class="flex items-center gap-2 bg-white/5 rounded-lg px-3 py-2.5 border border-white/10 mt-3">
                                            <i class="fa-solid fa-search text-gray-500 text-[10px]"></i>
                                            <span class="text-[10px] text-gray-500">Scan barcode or search product...</span>
                                            <span class="ml-auto text-[9px] text-gray-600 bg-white/5 px-1.5 py-0.5 rounded">F2</span>
                                        </div>
                                    </div>

                                    <div class="bg-white/5 rounded-lg p-3.5 border border-white/10">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-3">Bill</p>
                                        <div class="space-y-2 text-xs">
                                            <div class="flex justify-between"><span class="text-gray-400">Items</span><span class="text-white">5</span></div>
                                            <div class="flex justify-between"><span class="text-gray-400">Subtotal</span><span class="text-white">Rs. 1,810</span></div>
                                            <div class="flex justify-between"><span class="text-gray-400">Discount</span><span class="text-emerald-400">- Rs. 0</span></div>
                                            <div class="flex justify-between"><span class="text-gray-400">Tax</span><span class="text-white">Rs. 0</span></div>
                                            <div class="border-t border-white/10 pt-2 mt-2">
                                                <div class="flex justify-between">
                                                    <span class="font-bold text-white">Total</span>
                                                    <span class="font-extrabold text-brand-400 text-base">Rs. 1,810</span>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="w-full mt-3.5 bg-emerald-600 text-white text-[10px] font-bold py-2.5 rounded-lg hover:bg-emerald-700 transition">PAY — F8</button>
                                        <button class="w-full mt-2 bg-white/5 text-gray-300 text-[10px] font-semibold py-2 rounded-lg hover:bg-white/10 transition">Hold Bill — F4</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -top-3 -right-3 bg-emerald-500 text-white text-[10px] font-bold px-3.5 py-2 rounded-full shadow-lg shadow-emerald-500/30 float-animation">
                            Billed in 8 seconds
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== INVENTORY ==================== -->
    <section class="section-gap bg-white">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-center">

                <div class="fade-up order-2 lg:order-1 hidden lg:block">
                    <div class="relative">
                        <div class="bg-white rounded-2xl shadow-2xl shadow-gray-300/30 border border-gray-200/80 p-5">
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-5">
                                    <h4 class="font-bold text-gray-900 text-sm">Inventory — Stock Levels</h4>
                                    <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2.5 py-1 rounded-full border border-blue-200">Live</span>
                                </div>

                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3.5 border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-orange-100 rounded-lg flex items-center justify-center"><i class="fa-solid fa-wheat-awn text-orange-600 text-xs"></i></div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Basmati Rice 5kg</p>
                                                <p class="text-[10px] text-gray-400">SKU: GR-001</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-emerald-600">148 pcs</p>
                                            <p class="text-[10px] text-gray-400">Min: 50</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3.5 border border-red-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center"><i class="fa-solid fa-oil-well text-yellow-600 text-xs"></i></div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Cooking Oil 1L</p>
                                                <p class="text-[10px] text-gray-400">SKU: OL-003</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-red-600">8 pcs</p>
                                            <p class="text-[10px] text-red-500 font-semibold">Low Stock!</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3.5 border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center"><i class="fa-solid fa-bottle-water text-blue-600 text-xs"></i></div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Mineral Water 500ml</p>
                                                <p class="text-[10px] text-gray-400">SKU: BW-010</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-emerald-600">320 pcs</p>
                                            <p class="text-[10px] text-gray-400">Min: 100</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3.5 border border-red-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-pink-100 rounded-lg flex items-center justify-center"><i class="fa-solid fa-cookie text-pink-600 text-xs"></i></div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Biscuit Pack</p>
                                                <p class="text-[10px] text-gray-400">SKU: BK-022</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-red-600">0 pcs</p>
                                            <p class="text-[10px] text-red-500 font-semibold">Out of Stock!</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between text-[11px] text-gray-400 pt-4 border-t border-gray-100">
                                    <span>Total Products: <strong class="text-gray-600">1,247</strong></span>
                                    <span>Low Stock: <strong class="text-red-500">12 items</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-3 -left-3 bg-re4d-500 text-white text-[10px] font-bold px-3.5 py-2 rounded-full shadow-lg shadow-red-500/30 float-animation" style="animation-delay:-2s;">
                            2 Low Stock Alerts
                        </div>
                    </div>
                </div>

                <div class="fade-up order-1 lg:order-2" style="transition-delay:0.1s">
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-blue-50 rounded-full mb-6 border border-blue-100">
                        <i class="fa-solid fa-boxes-stacked text-blue-600 text-xs"></i>
                        <span class="text-blue-700 text-xs font-bold uppercase tracking-widest">Inventory</span>
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-5 tracking-tight">Stock That Manages Itself</h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-10">Every sale auto-deducts stock. Purchase entries add it back. Low stock alerts before you run out.</p>

                    <ul class="space-y-5">
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-check text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Auto Stock Deduction</p>
                                <p class="text-gray-500 text-sm mt-0.5">Stock updates the moment a bill is generated.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-check text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Low Stock Alerts</p>
                                <p class="text-gray-500 text-sm mt-0.5">Get notified before items run out. Never lose a sale.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-check text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Multi-Location Sync</p>
                                <p class="text-gray-500 text-sm mt-0.5">Stock syncs across all your branches in real-time.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-check text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Purchase Management</p>
                                <p class="text-gray-500 text-sm mt-0.5">Track purchases, manage suppliers, auto-update stock.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== HOW IT WORKS ==================== -->
    <section id="how-it-works" class="section-gap bg-gray-50/70 dot-pattern">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-violet-50 rounded-full mb-5 border border-violet-100">
                    <i class="fa-solid fa-list-ol text-violet-600 text-xs"></i>
                    <span class="text-violet-700 text-xs font-bold uppercase tracking-widest">How It Works</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Up and running in 3 steps</h2>
                <p class="text-gray-500 mt-4 text-lg max-w-2xl mx-auto leading-relaxed">No complex setup. No technical skills needed. Start billing in under 10 minutes.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
                <div class="relative bg-white rounded-2xl p-8 border border-gray-200/80 fade-up text-center">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-brand-600 text-white text-sm font-extrabold rounded-full flex items-center justify-center shadow-lg shadow-brand-600/30">1</div>
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6 mt-2">
                        <i class="fa-solid fa-user-plus text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Sign Up Free</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Create your account in 30 seconds. No credit card needed for the 14-day trial.</p>
                </div>

                <div class="relative bg-white rounded-2xl p-8 border border-gray-200/80 fade-up text-center" style="transition-delay:0.1s">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-brand-600 text-white text-sm font-extrabold rounded-full flex items-center justify-center shadow-lg shadow-brand-600/30">2</div>
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6 mt-2">
                        <i class="fa-solid fa-gear text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Setup Your Store</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Add products, set prices, configure tax. Import from Excel if you have data.</p>
                </div>

                <div class="relative bg-white rounded-2xl p-8 border border-gray-200/80 fade-up text-center" style="transition-delay:0.2s">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-brand-600 text-white text-sm font-extrabold rounded-full flex items-center justify-center shadow-lg shadow-brand-600/30">3</div>
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6 mt-2">
                        <i class="fa-solid fa-cash-register text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Start Billing</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Open the POS screen and start serving customers. It's that simple.</p>
                </div>
            </div>

            <div class="text-center mt-12 fade-up" style="transition-delay:0.3s">
                <a href="{{ route('trial.form') }}" class="inline-flex items-center gap-2.5 px-8 py-4 bg-brand-600 text-white text-sm font-bold rounded-2xl hover:bg-brand-700 transition-all shadow-xl shadow-brand-600/20 hover:shadow-brand-600/30 hover:-translate-y-0.5 btn-shine">
                    Start Your Free Trial
                </a>
            </div>
        </div>
    </section>

    <!-- ==================== PRICING ==================== -->
    <section id="pricing" class="section-gap bg-white">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-emerald-50 rounded-full mb-5 border border-emerald-100">
                    <i class="fa-solid fa-tag text-emerald-600 text-xs"></i>
                    <span class="text-emerald-700 text-xs font-bold uppercase tracking-widest">Pricing</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Simple, transparent pricing</h2>
                <p class="text-gray-500 mt-4 text-lg max-w-2xl mx-auto leading-relaxed">No hidden fees. No per-transaction charges. Pick your plan and grow.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto">

                <div class="bg-white rounded-2xl p-8 border border-gray-200/80 fade-up card-hover">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Starter</p>
                    <div class="mt-4 mb-1">
                        <span class="text-4xl font-extrabold text-gray-900">Rs. 2,999</span>
                        <span class="text-gray-400 text-sm">/mo</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-8">Perfect for single stores</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2.5 text-sm text-gray-600"><i class="fa-solid fa-check text-emerald-500 text-xs"></i>1 Terminal</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600"><i class="fa-solid fa-check text-emerald-500 text-xs"></i>Up to 500 Products</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600"><i class="fa-solid fa-check text-emerald-500 text-xs"></i>Basic Reports</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600"><i class="fa-solid fa-check text-emerald-500 text-xs"></i>Thermal Print</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-400"><i class="fa-solid fa-xmark text-gray-300 text-xs"></i>Multi-Location</li>
                    </ul>
                    <a href="{{ route('trial.form') }}" class="block text-center px-6 py-3.5 border-2 border-gray-200 text-gray-700 font-semibold text-sm rounded-xl hover:border-brand-500 hover:text-brand-600 transition">Start Free Trial</a>
                </div>

                <div class="relative bg-gray-900 rounded-2xl p-8 border border-gray-700 fade-up card-hover" style="transition-delay:0.1s">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-brand-600 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-1.5 rounded-full shadow-lg shadow-brand-600/30">Most Popular</div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Growth</p>
                    <div class="mt-4 mb-1">
                        <span class="text-4xl font-extrabold text-white">Rs. 5,999</span>
                        <span class="text-gray-500 text-sm">/mo</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-8">For growing businesses</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2.5 text-sm text-gray-300"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>3 Terminals</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-300"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Unlimited Products</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-300"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Advanced Reports</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-300"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Multi-Location</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-300"><i class="fa-solid fa-check text-emerald-400 text-xs"></i>Priority Support</li>
                    </ul>
                    <a href="{{ route('trial.form') }}" class="block text-center px-6 py-3.5 bg-brand-600 text-white font-semibold text-sm rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/25 btn-shine">Start Free Trial</a>
                </div>

                <div class="bg-white rounded-2xl p-8 border border-gray-200/80 fade-up card-hover" style="transition-delay:0.2s">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Enterprise</p>
                    <div class="mt-4 mb-1">
                        <span class="text-4xl font-extrabold text-gray-900">Custom</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-8">For chains & franchises</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2.5 text-sm text-gray-600"><i class="fa-solid fa-check text-emerald-500 text-xs"></i>Unlimited Terminals</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600"><i class="fa-solid fa-check text-emerald-500 text-xs"></i>Unlimited Products</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600"><i class="fa-solid fa-check text-emerald-500 text-xs"></i>Custom Integrations</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600"><i class="fa-solid fa-check text-emerald-500 text-xs"></i>Dedicated Manager</li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600"><i class="fa-solid fa-check text-emerald-500 text-xs"></i>SLA Guarantee</li>
                    </ul>
                    <a href="#contact" class="block text-center px-6 py-3.5 border-2 border-gray-200 text-gray-700 font-semibold text-sm rounded-xl hover:border-brand-500 hover:text-brand-600 transition">Contact Sales</a>
                </div>

            </div>
        </div>
    </section>

    <!-- ==================== CTA ==================== -->
    <section class="relative py-24 bg-gray-950 overflow-hidden">
        <div class="absolute inset-0 hero-glow"></div>
        <div class="absolute top-10 right-20 w-72 h-72 bg-brand-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-20 w-96 h-96 bg-indigo-500/8 rounded-full blur-3xl"></div>

        <div class="relative max-w-3xl mx-auto px-5 sm:px-8 text-center fade-up">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                Ready to switch to<br><span class="gradient-text">a smarter POS?</span>
            </h2>
            <p class="mt-6 text-lg text-gray-400 max-w-xl mx-auto leading-relaxed">Join 500+ businesses already using SwiftPOS. Start your free 14-day trial — no credit card required.</p>
            <div class="flex flex-col sm:flex-row gap-3.5 justify-center mt-10">
                <a href="{{ route('trial.form') }}" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-brand-600 text-white text-sm font-bold rounded-2xl hover:bg-brand-700 transition-all shadow-2xl shadow-brand-600/30 hover:-translate-y-0.5 btn-shine">
                    Start Free 14-Day Trial
                </a>
                <a href="#contact" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-white/5 text-white text-sm font-semibold rounded-2xl border border-white/10 hover:bg-white/10 transition">
                    Talk to Sales
                </a>
            </div>
        </div>
    </section>

    <!-- ==================== CONTACT ==================== -->
    <section id="contact" class="section-gap bg-white">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-20">
                <div class="fade-up">
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-blue-50 rounded-full mb-6 border border-blue-100">
                        <i class="fa-solid fa-envelope text-blue-600 text-xs"></i>
                        <span class="text-blue-700 text-xs font-bold uppercase tracking-widest">Contact</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-5 tracking-tight">Get in touch</h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-10">Have questions? Want a demo? Our team is ready to help you get started.</p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0 border border-blue-100">
                                <i class="fa-solid fa-phone text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Phone</p>
                                <p class="text-gray-500 text-sm mt-0.5">+92 300 1234567</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0 border border-blue-100">
                                <i class="fa-solid fa-envelope text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Email</p>
                                <p class="text-gray-500 text-sm mt-0.5">hello@swiftpos.com</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0 border border-blue-100">
                                <i class="fa-solid fa-location-dot text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Office</p>
                                <p class="text-gray-500 text-sm mt-0.5">Lahore, Pakistan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fade-up" style="transition-delay:0.1s">
                    <form class="bg-gray-50 rounded-2xl p-8 border border-gray-200/80 space-y-5">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Name</label>
                                <input type="text" placeholder="Your name" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phone</label>
                                <input type="tel" placeholder="+92 3XX XXXXXXX" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                            <input type="email" placeholder="you@business.com" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Business Type</label>
                            <select class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-500 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition appearance-none">
                                <option>Select your industry</option>
                                <option>Mart / Grocery</option>
                                <option>Restaurant</option>
                                <option>Cafe / Bakery</option>
                                <option>Retail / Fashion</option>
                                <option>Clinic / Pharmacy</option>
                                <option>General Store</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Message</label>
                            <textarea rows="4" placeholder="Tell us about your business..." class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-3.5 bg-brand-600 text-white font-semibold text-sm rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/20 btn-shine">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
    <footer class="bg-gray-950 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/10">
                <div>
                    <a href="/" class="flex items-center gap-2.5 mb-5">
                        <div class="w-9 h-9 bg-brand-500 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-bolt text-white text-sm"></i>
                        </div>
                        <span class="font-extrabold text-white text-lg">SwiftPOS</span>
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">All-in-one POS system built for every business. Fast, reliable, and easy to use.</p>
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-9 h-9 bg-white/5 rounded-lg flex items-center justify-center text-gray-500 hover:text-white hover:bg-white/10 transition"><i class="fa-brands fa-facebook-f text-sm"></i></a>
                        <a href="#" class="w-9 h-9 bg-white/5 rounded-lg flex items-center justify-center text-gray-500 hover:text-white hover:bg-white/10 transition"><i class="fa-brands fa-instagram text-sm"></i></a>
                        <a href="#" class="w-9 h-9 bg-white/5 rounded-lg flex items-center justify-center text-gray-500 hover:text-white hover:bg-white/10 transition"><i class="fa-brands fa-whatsapp text-sm"></i></a>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Product</p>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-sm text-gray-500 hover:text-white transition">Features</a></li>
                        <li><a href="#pricing" class="text-sm text-gray-500 hover:text-white transition">Pricing</a></li>
                        <li><a href="#industries" class="text-sm text-gray-500 hover:text-white transition">Industries</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-white transition">Integrations</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Company</p>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-500 hover:text-white transition">About Us</a></li>
                        <li><a href="#contact" class="text-sm text-gray-500 hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-white transition">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Get Started</p>
                    <ul class="space-y-3">
                        <li><a href="{{ route('trial.form') }}" class="text-sm text-gray-500 hover:text-white transition">Free Trial</a></li>
                        <li><a href="{{ route('platform.login') }}" class="text-sm text-gray-500 hover:text-white transition">Login</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-white transition">Help Center</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-white transition">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
                <p class="text-xs text-gray-600">© 2025 SwiftPOS. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-xs text-gray-600 hover:text-gray-400 transition">Terms</a>
                    <a href="#" class="text-xs text-gray-600 hover:text-gray-400 transition">Privacy</a>
                    <a href="#" class="text-xs text-gray-600 hover:text-gray-400 transition">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ==================== SCRIPTS ==================== -->
    <script>
        // Scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 50) {
                navbar.classList.add('shadow-sm');
            } else {
                navbar.classList.remove('shadow-sm');
            }
            lastScroll = currentScroll;
        });

        // Mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('menuIcon');
            const closeIcon = document.getElementById('closeIcon');
            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }

        function closeMobileMenu() {
            document.getElementById('mobileMenu').classList.add('hidden');
            document.getElementById('menuIcon').classList.remove('hidden');
            document.getElementById('closeIcon').classList.add('hidden');
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Video modal placeholder
        function openVideo() {
            alert('Video modal will open here. Connect your demo video URL.');
        }
    </script>
</body>
</html>