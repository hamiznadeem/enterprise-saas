<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enterprise POS — All-in-One Cloud Point of Sale Platform</title>
    <meta name="description" content="Enterprise POS system for marts, restaurants, cafes, retail stores, and clinics. Fast billing, real-time inventory, multi-branch management, and sales analytics.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }

        .btn-primary {
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #4338ca;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        }

        .bento-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            transition: all 0.25s ease;
        }
        .bento-card:hover {
            border-color: #a5b4fc;
            box-shadow: 0 16px 32px -8px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }

        .pos-badge {
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
            border: 1px solid rgba(79, 70, 229, 0.2);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- ==================== TOP ANNOUNCEMENT BAR ==================== -->
    <div class="bg-slate-900 text-white text-xs py-2 px-4 text-center font-medium flex items-center justify-center gap-2">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
        <span>Enterprise POS 2.0 is live with Multi-Branch Support & Instant OpenStreetMap Address Autofill!</span>
        <a href="{{ route('trial.form') }}" class="underline font-bold hover:text-indigo-300 ml-1">Start 14-Day Free Trial &rarr;</a>
    </div>

    <!-- ==================== NAVBAR ==================== -->
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand -->
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-8.5 h-8.5 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm">
                        <i class="fa-solid fa-bolt text-xs"></i>
                    </div>
                    <span class="font-extrabold text-slate-900 text-lg tracking-tight">Enterprise POS</span>
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#showcase" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">Product Tour</a>
                    <a href="#industries" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">Industries</a>
                    <a href="#how-it-works" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">How it Works</a>
                    <a href="#pricing" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">Pricing</a>
                    <a href="#faq" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">FAQ</a>
                </div>

                <!-- Right Action Button (Only Start Free Trial — No Sign In) -->
                <div>
                    <a href="{{ route('trial.form') }}" class="btn-primary text-xs px-4 py-2 rounded-lg">
                        Start Free Trial
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ==================== HERO SECTION ==================== -->
    <section class="relative pt-12 pb-20 md:pt-20 md:pb-28 overflow-hidden bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-indigo-50 border border-indigo-100 rounded-full text-indigo-700 text-xs font-semibold mb-6">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span>Trusted by 500+ Retail Stores, Restaurants & Clinics Across Pakistan</span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.12]">
                    The All-in-One Cloud POS for Retail, Restaurants & Clinics
                </h1>

                <!-- Subheadline -->
                <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed font-normal">
                    Streamline cashier billing, track real-time inventory across multiple branches, manage patient queues, and view instant sales analytics from one unified platform.
                </p>

                <!-- Action CTAs -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                    <a href="{{ route('trial.form') }}" class="w-full sm:w-auto btn-primary px-7 py-3.5 rounded-xl text-sm font-bold shadow-md flex items-center justify-center gap-2">
                        <span>Start 14-Day Free Trial</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <button onclick="openVideo()" class="w-full sm:w-auto px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl border border-slate-200 flex items-center justify-center gap-2 transition cursor-pointer">
                        <i class="fa-solid fa-circle-play text-indigo-600 text-base"></i>
                        <span>Watch Demo Video (2 Min)</span>
                    </button>
                </div>

                <!-- Trust Badges -->
                <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-slate-500 text-xs font-medium">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-500"></i> No Credit Card Needed</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-500"></i> Instant 60-Sec Setup</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-500"></i> Thermal Printer & Barcode Ready</span>
                </div>
            </div>

            <!-- Product Showcase Screen Mockup with Real Authentic POS Image -->
            <div class="mt-14 relative max-w-5xl mx-auto">
                <div class="bg-slate-900 p-2.5 sm:p-3 rounded-2xl shadow-2xl border border-slate-800">
                    <!-- Browser Window Header Bar -->
                    <div class="flex items-center justify-between px-3.5 py-2 border-b border-slate-800 bg-slate-950 rounded-t-xl">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        </div>
                        <div class="bg-slate-900 border border-slate-800 rounded-md px-4 py-1 text-[11px] font-mono text-slate-400 truncate max-w-xs text-center">
                            https://yourstore.enterprisepos.com/pos
                        </div>
                        <div class="w-12"></div>
                    </div>

                    <!-- App UI Authentic POS Terminal Screenshot Preview -->
                    <div class="relative aspect-[16/9] bg-slate-900 rounded-b-xl overflow-hidden group cursor-pointer" onclick="openVideo()">
                        <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?w=1200&q=80" alt="Real POS Cashier Register Terminal" class="w-full h-full object-cover">
                        
                        <!-- Overlay Play Trigger -->
                        <div class="absolute inset-0 bg-slate-950/40 group-hover:bg-slate-950/20 transition-all flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-indigo-600 hover:bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-xl shadow-indigo-600/50 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-play text-xl ml-1"></i>
                            </div>
                            <span class="mt-3 px-4 py-1.5 bg-slate-900/90 backdrop-blur-md text-white text-xs font-bold rounded-full border border-slate-700">
                                Click to Watch Real POS System Demo
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ==================== LOGO BAR ==================== -->
    <section class="py-10 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs font-bold uppercase tracking-wider text-slate-400 mb-6">Powering Businesses Across Multiple Sectors</p>
            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16 opacity-80 grayscale hover:grayscale-0 transition">
                <div class="flex items-center gap-2 font-bold text-slate-700 text-sm"><i class="fa-solid fa-cart-shopping text-indigo-600"></i> SuperMart Grocery</div>
                <div class="flex items-center gap-2 font-bold text-slate-700 text-sm"><i class="fa-solid fa-utensils text-indigo-600"></i> Royal Cuisine</div>
                <div class="flex items-center gap-2 font-bold text-slate-700 text-sm"><i class="fa-solid fa-mug-hot text-indigo-600"></i> Cafe Aroma</div>
                <div class="flex items-center gap-2 font-bold text-slate-700 text-sm"><i class="fa-solid fa-shirt text-indigo-600"></i> Urban Apparel</div>
                <div class="flex items-center gap-2 font-bold text-slate-700 text-sm"><i class="fa-solid fa-heart-pulse text-indigo-600"></i> HealthCare Clinic</div>
            </div>
        </div>
    </section>

    <!-- ==================== WORKING VIDEO MODAL ==================== -->
    <div id="videoModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md transition-all duration-300">
        <div class="relative w-full max-w-4xl bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-800 bg-slate-900">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 bg-indigo-600 rounded flex items-center justify-center">
                        <i class="fa-solid fa-play text-white text-[10px]"></i>
                    </div>
                    <span class="text-xs font-bold text-white">Enterprise POS — Live Software Demo</span>
                </div>
                <button onclick="closeVideo()" class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
            <div class="relative aspect-video bg-black">
                <iframe id="videoIframe" class="w-full h-full" src="" title="POS System Demo Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- ==================== BENTO GRID PRODUCT SHOWCASE WITH REAL POS IMAGES ==================== -->
    <section id="showcase" class="py-20 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider border border-indigo-100">Product Tour</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-3">Built for Speed, Control & Multi-Branch Scale</h2>
                <p class="text-sm text-slate-600 mt-2">Explore authentic interfaces designed for daily store cashiers, managers, and owners.</p>
            </div>

            <!-- Bento Grid with Authentic POS Screenshots -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 max-w-6xl mx-auto">
                
                <!-- Bento 1: Large Main POS Checkout (8 cols) -->
                <div class="md:col-span-8 bento-card p-6 flex flex-col justify-between overflow-hidden relative">
                    <div class="mb-4">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 pos-badge rounded-md text-[11px] font-bold uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-cash-register"></i> Cashier Terminal
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900">Touchscreen POS Terminal & Barcode Scanner</h3>
                        <p class="text-xs text-slate-600 mt-1">Rapid checkout with barcode support, instant discounts, split payments, and thermal receipts.</p>
                    </div>
                    <div class="rounded-xl overflow-hidden border border-slate-200 aspect-[16/9] shadow-sm mt-2">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=900&q=80" alt="POS Retail Checkout Terminal" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Bento 2: Real-time Analytics (4 cols) -->
                <div class="md:col-span-4 bento-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 pos-badge rounded-md text-[11px] font-bold uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-chart-line"></i> Revenue Analytics
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Live Sales & Revenue Dashboard</h3>
                        <p class="text-xs text-slate-600 mt-1">Track daily sales totals, net profits, and peak hours in real time.</p>
                    </div>
                    <div class="rounded-xl overflow-hidden border border-slate-200 aspect-square shadow-sm mt-4">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=700&q=80" alt="Sales Revenue Dashboard Analytics" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Bento 3: Live Inventory Control (4 cols) -->
                <div class="md:col-span-4 bento-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 pos-badge rounded-md text-[11px] font-bold uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-boxes-stacked"></i> Stock Management
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Inventory & Low-Stock Alerts</h3>
                        <p class="text-xs text-slate-600 mt-1">Automatic stock deductions, expiry warnings, and batch tracking.</p>
                    </div>
                    <div class="rounded-xl overflow-hidden border border-slate-200 aspect-square shadow-sm mt-4">
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=700&q=80" alt="Warehouse Inventory Stock Control" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Bento 4: Multi-Branch & Restaurant/Clinic POS (8 cols) -->
                <div class="md:col-span-8 bento-card p-6 flex flex-col justify-between">
                    <div class="mb-4">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 pos-badge rounded-md text-[11px] font-bold uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-store"></i> Multi-Outlet & Restaurant POS
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900">Branch Switcher, KOT & Clinic Tokens</h3>
                        <p class="text-xs text-slate-600 mt-1">Seamlessly switch between store outlets, manage restaurant kitchen orders (KOT), and print clinic queue tokens.</p>
                    </div>
                    <div class="rounded-xl overflow-hidden border border-slate-200 aspect-[16/9] shadow-sm mt-2">
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=900&q=80" alt="Restaurant POS Touchscreen Terminal" class="w-full h-full object-cover">
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ==================== HOW IT WORKS ==================== -->
    <section id="how-it-works" class="py-20 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider border border-indigo-100">Simple 3-Step Setup</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-3">Up and Running in Less Than 2 Minutes</h2>
                <p class="text-sm text-slate-600 mt-2">No complicated installation or hardware requirements. Runs on any web browser.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                
                <!-- Step 1 -->
                <div class="bg-white border border-slate-200 rounded-2xl p-8 relative shadow-sm text-center">
                    <div class="w-12 h-12 bg-indigo-600 text-white font-extrabold rounded-xl flex items-center justify-center text-lg mx-auto mb-5 shadow-md shadow-indigo-600/20">
                        1
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Create Free Trial Account</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Enter your business name, city, and subdomain. Instant dashboard access without email verification during trial.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white border border-slate-200 rounded-2xl p-8 relative shadow-sm text-center">
                    <div class="w-12 h-12 bg-indigo-600 text-white font-extrabold rounded-xl flex items-center justify-center text-lg mx-auto mb-5 shadow-md shadow-indigo-600/20">
                        2
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Configure Products & Staff</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Add your inventory items, prices, staff accounts, and branch locations through intuitive setup wizards.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white border border-slate-200 rounded-2xl p-8 relative shadow-sm text-center">
                    <div class="w-12 h-12 bg-indigo-600 text-white font-extrabold rounded-xl flex items-center justify-center text-lg mx-auto mb-5 shadow-md shadow-indigo-600/20">
                        3
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Start Billing & Sales</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Open the POS terminal, scan barcodes, issue receipts, and track your daily store revenue live.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- ==================== PRICING ==================== -->
    <section id="pricing" class="py-20 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="px-3.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider border border-indigo-100">Transparent Pricing</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-3 tracking-tight">Simple Plans for Businesses of Any Size</h2>
                <p class="text-sm text-slate-600 mt-2">Start with a 14-day free trial. Upgrade or renew manually anytime.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch max-w-5xl mx-auto">
                
                <!-- Starter Plan -->
                <div class="bg-white border border-slate-200 rounded-2xl p-8 flex flex-col justify-between shadow-sm hover:border-indigo-300 transition">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Single Outlet</h3>
                        <p class="text-xs text-slate-500 mt-1">Ideal for individual retail shops, cafes, or mini-marts.</p>
                        <div class="mt-6 mb-6">
                            <span class="text-3xl font-extrabold text-slate-900">PKR 3,500</span>
                            <span class="text-xs font-semibold text-slate-500">/ month</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-600">
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> 1 Store Location</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> 2 Staff Accounts</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> POS Terminal & Receipts</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Live Stock Management</li>
                        </ul>
                    </div>
                    <a href="{{ route('trial.form') }}" class="mt-8 block text-center px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl transition">Start 14-Day Free Trial</a>
                </div>

                <!-- Professional Plan (Featured) -->
                <div class="bg-white border-2 border-indigo-600 rounded-2xl p-8 flex flex-col justify-between shadow-xl relative">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-[10px] font-extrabold uppercase px-3.5 py-1 rounded-full tracking-wider shadow-sm">Recommended</div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Multi-Branch Chain</h3>
                        <p class="text-xs text-slate-500 mt-1">Designed for growing multi-location stores & clinics.</p>
                        <div class="mt-6 mb-6">
                            <span class="text-3xl font-extrabold text-indigo-600">PKR 7,500</span>
                            <span class="text-xs font-semibold text-slate-500">/ month</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-600">
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-indigo-600"></i> Up to 3 Branch Outlets</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-indigo-600"></i> Unlimited Staff Accounts</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-indigo-600"></i> Clinic Tokens & Doctor View</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-indigo-600"></i> Real-time Analytics & Audit Logs</li>
                        </ul>
                    </div>
                    <a href="{{ route('trial.form') }}" class="mt-8 block text-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl transition shadow-md shadow-indigo-600/20">Start 14-Day Free Trial</a>
                </div>

                <!-- Custom Plan -->
                <div class="bg-white border border-slate-200 rounded-2xl p-8 flex flex-col justify-between shadow-sm hover:border-indigo-300 transition">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Enterprise Custom</h3>
                        <p class="text-xs text-slate-500 mt-1">For large enterprise networks with custom needs.</p>
                        <div class="mt-6 mb-6">
                            <span class="text-3xl font-extrabold text-slate-900">Custom Quote</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-600">
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Unlimited Outlets & Users</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Custom API & ERP Connectors</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Dedicated Priority Support</li>
                        </ul>
                    </div>
                    <a href="https://wa.me/923001234567" target="_blank" class="mt-8 block text-center px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl transition">Contact Sales</a>
                </div>

            </div>
        </div>
    </section>

    <!-- ==================== FAQ SECTION ==================== -->
    <section id="faq" class="py-20 bg-slate-50 border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider border border-indigo-100">Got Questions?</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-3">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900">How do store owners and staff log into their accounts?</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">Each registered tenant receives a custom web access URL (subdomain). Store owners and staff log in directly through their dedicated business web access URL.</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900">Do I need email verification to use the 14-day free trial?</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">No. Free trial registrations provide instant dashboard access without requiring email verification. Verification is only required when upgrading to an active paid plan.</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900">Can I manage multiple store branches from one account?</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">Yes! You can configure multiple branches, switch between branches seamlessly, and view aggregated sales reports for your entire business chain.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FINAL CTA BANNER ==================== -->
    <section class="py-20 bg-indigo-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Ready to Upgrade Your Business Operations?</h2>
            <p class="text-indigo-100 text-sm mt-3 max-w-xl mx-auto">Start your 14-day free trial now. Full features unlocked, zero commitment required.</p>
            <div class="mt-8">
                <a href="{{ route('trial.form') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-indigo-600 font-extrabold text-sm rounded-xl hover:bg-indigo-50 transition shadow-xl">
                    <span>Start Free Trial Now</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
    <footer class="bg-slate-900 text-slate-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6 text-xs">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                    <i class="fa-solid fa-bolt text-xs"></i>
                </div>
                <span class="font-bold text-white text-sm">Enterprise POS Platform</span>
            </div>
            <p>© 2026 Enterprise POS. All rights reserved.</p>
            <div>
                <a href="{{ route('trial.form') }}" class="hover:text-white transition">Start Free Trial</a>
            </div>
        </div>
    </footer>

    <!-- ==================== SCRIPTS ==================== -->
    <script>
        // Working Video Modal
        function openVideo() {
            const modal = document.getElementById('videoModal');
            const iframe = document.getElementById('videoIframe');
            iframe.src = "https://www.youtube.com/embed/LDU_Txk06tM?autoplay=1";
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeVideo() {
            const modal = document.getElementById('videoModal');
            const iframe = document.getElementById('videoIframe');
            iframe.src = "";
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>