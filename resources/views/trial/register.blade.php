<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Start 14-Day Free Trial — Enterprise POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        indigo: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            800: '#3730a3', 900: '#312e81', 950: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        
        .biz-card {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .biz-card:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
        }
        .biz-card.active {
            border-color: #4f46e5 !important;
            background-color: #eef2ff !important;
            transform: translateY(-2px);
        }
        .biz-card.active .biz-title { color: #4f46e5; font-weight: 800; }
        .biz-card.active .check-badge { display: flex; }
        .biz-card.active .biz-icon {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
            border-color: #4338ca !important;
        }

        .outlet-pill.active {
            border-color: #4f46e5;
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: 700;
        }

        .input-group:focus-within .input-icon {
            color: #4f46e5;
        }

        .spinner {
            width: 1.125rem; height: 1.125rem;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen lg:h-screen lg:overflow-hidden">

    <!-- 2-Column Split Layout Grid -->
    <div class="w-full h-full grid grid-cols-1 lg:grid-cols-12 items-stretch">
        
        <!-- LEFT COLUMN: ZERO-SCROLL COMPACT BRAND PANEL (5 Cols Desktop) -->
        <div class="lg:col-span-5 bg-slate-900 p-6 lg:p-8 flex flex-col justify-between border-r border-slate-800 relative overflow-hidden lg:h-screen lg:sticky lg:top-0">
            <div>
                <!-- Brand Header -->
                <a href="/" class="inline-flex items-center gap-3 mb-8">
                    <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">
                        <i class="fa-solid fa-bolt text-xs"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-white text-lg tracking-tight block leading-none">Enterprise POS</span>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Cloud Retail & POS Platform</span>
                    </div>
                </a>

                <!-- Main Value Headline -->
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-800 border border-slate-700 text-indigo-300 text-[11px] font-semibold rounded-md mb-3">
                        <i class="fa-solid fa-star text-[10px] text-amber-400"></i> Trusted by 500+ Businesses
                    </span>
                    <h1 class="text-xl lg:text-2xl font-extrabold text-white tracking-tight leading-snug">
                        Power Your Store Operations in One Smart Platform
                    </h1>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Instant billing checkout, real-time inventory tracking, and multi-branch management.
                    </p>
                </div>

                <!-- 3 Compact Key Feature Bullets -->
                <div class="mt-6 space-y-3 text-xs text-slate-300">
                    <div class="flex items-center gap-2.5">
                        <div class="w-5 h-5 rounded-md bg-indigo-600 text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <span class="font-medium text-slate-200">Touchscreen Billing & Barcode Reader Ready</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-5 h-5 rounded-md bg-indigo-600 text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <span class="font-medium text-slate-200">Automated Inventory & Batch Expiry Tracking</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-5 h-5 rounded-md bg-indigo-600 text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <span class="font-medium text-slate-200">Multi-Branch Outlets & Clinic Queue Tokens</span>
                    </div>
                </div>
            </div>

            <!-- ZERO-SCROLL FIT CUSTOMER TESTIMONIAL CARD -->
            <div class="pt-4 border-t border-slate-800 shrink-0">
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-xl p-3.5">
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="flex items-center gap-1 text-amber-400 text-[11px]">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <span class="text-[10px] text-emerald-400 font-bold bg-slate-900 px-2 py-0.5 rounded border border-slate-700">Verified Owner</span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed italic">
                        "Billing speed improved significantly from day 1. Best decision for our retail outlets!"
                    </p>
                    <div class="mt-2.5 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-indigo-600 text-white font-bold text-[10px] flex items-center justify-center">
                            BA
                        </div>
                        <div class="truncate">
                            <span class="text-xs font-bold text-white block truncate leading-none">Bilal Ahmed</span>
                            <span class="text-[10px] text-slate-400 block truncate">Owner, SuperMart Chain</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Scrollable Form Panel (7 Cols Desktop) -->
        <div class="lg:col-span-7 bg-slate-50 text-slate-900 p-6 lg:p-12 lg:h-screen lg:overflow-y-auto flex flex-col justify-between">
            
            <div class="max-w-xl mx-auto w-full">
                
                <!-- Form Header -->
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-200">
                    <div>
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[11px] font-extrabold rounded-md border border-indigo-100 uppercase tracking-wider">
                            14-Day Free Trial
                        </span>
                        <h2 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight mt-2">
                            Create Your Business Account
                        </h2>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-emerald-600 font-bold block"><i class="fa-solid fa-bolt text-[10px]"></i> Instant Access</span>
                        <span class="text-[10px] text-slate-400">No Credit Card Required</span>
                    </div>
                </div>

                <form id="trialForm" method="POST" action="{{ route('trial.register') }}" class="space-y-6 pb-12">
                    @csrf

                    <!-- Honeypot -->
                    <div class="hidden"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-xs text-red-800 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p class="flex items-center gap-2 font-medium">
                                    <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                                    <span>{{ $error }}</span>
                                </p>
                            @endforeach
                        </div>
                    @endif

                    <!-- SECTION 1: BUSINESS INDUSTRY CARDS WITH UNIFIED BRAND ICONS -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center">1</span>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Select Business Industry</h3>
                            </div>
                            <span class="text-[10px] text-slate-400 font-semibold">Choose your business model</span>
                        </div>

                        <!-- Industry Cards Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            
                            <!-- 1. Mart & Grocery -->
                            <div class="biz-card border-2 border-slate-200 rounded-xl p-3.5 cursor-pointer relative bg-white hover:bg-slate-50 transition flex items-center gap-3.5 @if(old('business_type') == 'mart') active @endif" onclick="selectBiz(this, 'mart')">
                                <div class="check-badge hidden absolute top-2.5 right-2.5 w-4 h-4 bg-indigo-600 rounded-full items-center justify-center text-white text-[9px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="biz-icon w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center text-sm shrink-0 transition">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </div>
                                <div>
                                    <div class="biz-title text-xs font-bold text-slate-900">Mart & Grocery</div>
                                    <div class="text-[10px] text-slate-500 font-medium mt-0.5">Barcode & Stock Control</div>
                                </div>
                            </div>

                            <!-- 2. Restaurant -->
                            <div class="biz-card border-2 border-slate-200 rounded-xl p-3.5 cursor-pointer relative bg-white hover:bg-slate-50 transition flex items-center gap-3.5 @if(old('business_type') == 'restaurant') active @endif" onclick="selectBiz(this, 'restaurant')">
                                <div class="check-badge hidden absolute top-2.5 right-2.5 w-4 h-4 bg-indigo-600 rounded-full items-center justify-center text-white text-[9px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="biz-icon w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center text-sm shrink-0 transition">
                                    <i class="fa-solid fa-utensils"></i>
                                </div>
                                <div>
                                    <div class="biz-title text-xs font-bold text-slate-900">Restaurant</div>
                                    <div class="text-[10px] text-slate-500 font-medium mt-0.5">KOT & Table Orders</div>
                                </div>
                            </div>

                            <!-- 3. Cafe & Bakery -->
                            <div class="biz-card border-2 border-slate-200 rounded-xl p-3.5 cursor-pointer relative bg-white hover:bg-slate-50 transition flex items-center gap-3.5 @if(old('business_type') == 'cafe') active @endif" onclick="selectBiz(this, 'cafe')">
                                <div class="check-badge hidden absolute top-2.5 right-2.5 w-4 h-4 bg-indigo-600 rounded-full items-center justify-center text-white text-[9px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="biz-icon w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center text-sm shrink-0 transition">
                                    <i class="fa-solid fa-mug-hot"></i>
                                </div>
                                <div>
                                    <div class="biz-title text-xs font-bold text-slate-900">Cafe & Bakery</div>
                                    <div class="text-[10px] text-slate-500 font-medium mt-0.5">Takeaway & Modifiers</div>
                                </div>
                            </div>

                            <!-- 4. Retail & Fashion -->
                            <div class="biz-card border-2 border-slate-200 rounded-xl p-3.5 cursor-pointer relative bg-white hover:bg-slate-50 transition flex items-center gap-3.5 @if(old('business_type') == 'retail') active @endif" onclick="selectBiz(this, 'retail')">
                                <div class="check-badge hidden absolute top-2.5 right-2.5 w-4 h-4 bg-indigo-600 rounded-full items-center justify-center text-white text-[9px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="biz-icon w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center text-sm shrink-0 transition">
                                    <i class="fa-solid fa-shirt"></i>
                                </div>
                                <div>
                                    <div class="biz-title text-xs font-bold text-slate-900">Retail & Apparel</div>
                                    <div class="text-[10px] text-slate-500 font-medium mt-0.5">Size Variants & Loyalty</div>
                                </div>
                            </div>

                            <!-- 5. Clinic & Pharmacy -->
                            <div class="biz-card border-2 border-slate-200 rounded-xl p-3.5 cursor-pointer relative bg-white hover:bg-slate-50 transition flex items-center gap-3.5 @if(old('business_type') == 'clinic') active @endif" onclick="selectBiz(this, 'clinic')">
                                <div class="check-badge hidden absolute top-2.5 right-2.5 w-4 h-4 bg-indigo-600 rounded-full items-center justify-center text-white text-[9px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="biz-icon w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center text-sm shrink-0 transition">
                                    <i class="fa-solid fa-heart-pulse"></i>
                                </div>
                                <div>
                                    <div class="biz-title text-xs font-bold text-slate-900">Clinic & Pharmacy</div>
                                    <div class="text-[10px] text-slate-500 font-medium mt-0.5">Queue Tokens & Rx</div>
                                </div>
                            </div>

                            <!-- 6. General Superstore -->
                            <div class="biz-card border-2 border-slate-200 rounded-xl p-3.5 cursor-pointer relative bg-white hover:bg-slate-50 transition flex items-center gap-3.5 @if(old('business_type') == 'general_store') active @endif" onclick="selectBiz(this, 'general_store')">
                                <div class="check-badge hidden absolute top-2.5 right-2.5 w-4 h-4 bg-indigo-600 rounded-full items-center justify-center text-white text-[9px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="biz-icon w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center text-sm shrink-0 transition">
                                    <i class="fa-solid fa-store"></i>
                                </div>
                                <div>
                                    <div class="biz-title text-xs font-bold text-slate-900">General Superstore</div>
                                    <div class="text-[10px] text-slate-500 font-medium mt-0.5">Fast Cashier Billing</div>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" id="business_type" name="business_type" value="{{ old('business_type') }}">

                        <!-- Outlets Selector -->
                        <div class="mt-4 pt-3.5 border-t border-slate-100">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Outlets Maintained
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <div class="outlet-pill border-2 border-slate-200 rounded-xl px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer bg-white transition @if(old('outlets') == '1') active @endif" onclick="selectOutlet(this, '1')">1 Outlet</div>
                                <div class="outlet-pill border-2 border-slate-200 rounded-xl px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer bg-white transition @if(old('outlets') == '2-5') active @endif" onclick="selectOutlet(this, '2-5')">2 – 5 Outlets</div>
                                <div class="outlet-pill border-2 border-slate-200 rounded-xl px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer bg-white transition @if(old('outlets') == '6-10') active @endif" onclick="selectOutlet(this, '6-10')">6 – 10 Outlets</div>
                                <div class="outlet-pill border-2 border-slate-200 rounded-xl px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer bg-white transition @if(old('outlets') == '10+') active @endif" onclick="selectOutlet(this, '10+')">10+ Outlets</div>
                            </div>
                            <input type="hidden" id="outlets" name="outlets" value="{{ old('outlets') }}">
                        </div>
                    </div>

                    <!-- SECTION 2: Store Identity & Icon-Prefixed Inputs -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center">2</span>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Store Identity & Location</h3>
                        </div>

                        <!-- Shop Name & Subdomain -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Shop / Business Name</label>
                                <div class="input-group relative">
                                    <i class="fa-solid fa-store input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition"></i>
                                    <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" required placeholder="My Super Mart" oninput="autoSlug()"
                                           class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition font-medium">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">City</label>
                                <div class="input-group relative">
                                    <i class="fa-solid fa-location-dot input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition pointer-events-none"></i>
                                    <select id="city" name="city" required class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition font-medium cursor-pointer appearance-none">
                                        <option value="" disabled {{ old('city') ? '' : 'selected' }}>Select city...</option>
                                        <option value="Karachi" {{ old('city') == 'Karachi' ? 'selected' : '' }}>Karachi</option>
                                        <option value="Lahore" {{ old('city') == 'Lahore' ? 'selected' : '' }}>Lahore</option>
                                        <option value="Faisalabad" {{ old('city') == 'Faisalabad' ? 'selected' : '' }}>Faisalabad</option>
                                        <option value="Rawalpindi" {{ old('city') == 'Rawalpindi' ? 'selected' : '' }}>Rawalpindi</option>
                                        <option value="Gujranwala" {{ old('city') == 'Gujranwala' ? 'selected' : '' }}>Gujranwala</option>
                                        <option value="Peshawar" {{ old('city') == 'Peshawar' ? 'selected' : '' }}>Peshawar</option>
                                        <option value="Multan" {{ old('city') == 'Multan' ? 'selected' : '' }}>Multan</option>
                                        <option value="Hyderabad" {{ old('city') == 'Hyderabad' ? 'selected' : '' }}>Hyderabad</option>
                                        <option value="Islamabad" {{ old('city') == 'Islamabad' ? 'selected' : '' }}>Islamabad</option>
                                        <option value="Quetta" {{ old('city') == 'Quetta' ? 'selected' : '' }}>Quetta</option>
                                        <option value="Bahawalpur" {{ old('city') == 'Bahawalpur' ? 'selected' : '' }}>Bahawalpur</option>
                                        <option value="Sargodha" {{ old('city') == 'Sargodha' ? 'selected' : '' }}>Sargodha</option>
                                        <option value="Sialkot" {{ old('city') == 'Sialkot' ? 'selected' : '' }}>Sialkot</option>
                                        <option value="Sukkur" {{ old('city') == 'Sukkur' ? 'selected' : '' }}>Sukkur</option>
                                        <option value="Abbottabad" {{ old('city') == 'Abbottabad' ? 'selected' : '' }}>Abbottabad</option>
                                        <option value="Other" {{ old('city') == 'Other' ? 'selected' : '' }}>Other City</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- OpenStreetMap Address Location Search -->
                        <div class="relative">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Business Address / Location</label>
                            <div class="input-group relative">
                                <i class="fa-solid fa-map-pin input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition"></i>
                                <input type="text" id="location" name="location" value="{{ old('location') }}" required placeholder="Start typing address or market (e.g. Main Market, Gulberg)..." autocomplete="off" oninput="searchLocation(this.value)"
                                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition font-medium">
                            </div>
                            <div id="locationSuggestions" class="hidden absolute left-0 right-0 top-full mt-1 z-50 bg-white border border-slate-200 rounded-xl max-h-52 overflow-y-auto shadow-xl"></div>
                        </div>

                        <!-- Desired Web Access Subdomain -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Web Access Login URL (Subdomain)</label>
                            <div class="flex items-stretch border border-slate-200 rounded-xl overflow-hidden bg-slate-50 focus-within:border-indigo-600 focus-within:ring-2 focus-within:ring-indigo-100 focus-within:bg-white transition">
                                <span class="pl-3.5 flex items-center text-slate-400 text-xs"><i class="fa-solid fa-globe"></i></span>
                                <input type="text" id="domain" name="domain" value="{{ old('domain') }}" required placeholder="your-store" oninput="cleanSlug()"
                                       class="flex-1 px-3 py-2.5 bg-transparent border-none text-xs text-slate-900 outline-none font-medium">
                                <span class="px-3.5 flex items-center bg-slate-100 border-l border-slate-200 text-xs font-semibold text-slate-500">
                                    .yoursaas.com
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Store staff will log in at: <span class="font-mono text-indigo-600">your-store.yoursaas.com/login</span></p>
                        </div>
                    </div>

                    <!-- SECTION 3: Owner Account Credentials -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center">3</span>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Owner Account & Security</h3>
                        </div>

                        <!-- Name & Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Owner Name</label>
                                <div class="input-group relative">
                                    <i class="fa-solid fa-user input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition"></i>
                                    <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required placeholder="Ahmed Khan"
                                           class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition font-medium">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number</label>
                                <div class="input-group relative">
                                    <i class="fa-solid fa-phone input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition"></i>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="03XX-XXXXXXX"
                                           class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Owner Email -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                            <div class="input-group relative">
                                <i class="fa-solid fa-envelope input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition"></i>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="owner@mybusiness.com"
                                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition font-medium">
                            </div>
                        </div>

                        <!-- Password & Confirm Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                                <div class="input-group relative">
                                    <i class="fa-solid fa-lock input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition"></i>
                                    <input type="password" id="password" name="password" required minlength="8" placeholder="Min 8 characters"
                                           class="w-full pl-9 pr-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition font-medium">
                                    <button type="button" onclick="togglePw('password',this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password</label>
                                <div class="input-group relative">
                                    <i class="fa-solid fa-lock input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition"></i>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" placeholder="Re-enter password"
                                           class="w-full pl-9 pr-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition font-medium">
                                    <button type="button" onclick="togglePw('password_confirmation',this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Trial Plan Badge Banner -->
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 text-xs">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-emerald-900">14-Day Free Trial Activated</p>
                                    <p class="text-[11px] text-emerald-700">Full POS & management features unlocked immediately.</p>
                                </div>
                            </div>
                            <span class="text-xs font-extrabold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-md">FREE</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" class="w-full py-4 px-6 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold rounded-xl transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                        <span id="btnText">CREATE MY POS ACCOUNT</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                        <div id="btnSpinner" class="spinner hidden"></div>
                    </button>
                </form>

                <p class="text-center text-[11px] text-slate-400 mt-4 pb-8">
                    By registering, you agree to our Terms of Service & Privacy Policy.
                </p>
            </div>

        </div>

    </div>

    <!-- JS Logic Scripts -->
    <script>
        function selectBiz(el, value) {
            document.querySelectorAll('.biz-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('business_type').value = value;
        }

        function selectOutlet(el, value) {
            document.querySelectorAll('.outlet-pill').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('outlets').value = value;
        }

        function autoSlug() {
            const name = document.getElementById('business_name').value;
            const slug = name.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,'');
            document.getElementById('domain').value = slug;
        }

        function cleanSlug() {
            const f = document.getElementById('domain');
            f.value = f.value.toLowerCase().replace(/[^a-z0-9-]/g,'').replace(/-+/g,'-').replace(/^-|-$/g,'');
        }

        function togglePw(id, btn) {
            const f = document.getElementById(id);
            const i = btn.querySelector('i');
            if (f.type === 'password') { f.type = 'text'; i.className = 'fa-solid fa-eye-slash'; }
            else { f.type = 'password'; i.className = 'fa-solid fa-eye'; }
        }

        // OpenStreetMap Nominatim Live Autocomplete
        let locationTimeout = null;
        function searchLocation(query) {
            clearTimeout(locationTimeout);
            const box = document.getElementById('locationSuggestions');
            if (!query || query.trim().length < 3) {
                box.classList.add('hidden');
                return;
            }

            locationTimeout = setTimeout(() => {
                const selectedCity = document.getElementById('city').value || '';
                const searchQuery = selectedCity ? `${query}, ${selectedCity}, Pakistan` : `${query}, Pakistan`;
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&countrycodes=pk&limit=5&addressdetails=1`;

                fetch(url, { headers: { 'User-Agent': 'EnterpriseSaaS/1.0' } })
                    .then(res => res.json())
                    .then(data => {
                        box.innerHTML = '';
                        if (!data || data.length === 0) {
                            box.classList.add('hidden');
                            return;
                        }
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'px-3.5 py-2.5 border-b border-slate-100 text-xs text-slate-700 cursor-pointer flex items-center gap-2 hover:bg-slate-50 transition';
                            div.innerHTML = `<i class="fa-solid fa-location-dot text-indigo-600 shrink-0"></i> <span>${item.display_name}</span>`;
                            
                            div.onclick = () => {
                                document.getElementById('location').value = item.display_name;
                                box.classList.add('hidden');

                                const citySelect = document.getElementById('city');
                                let matched = false;
                                const addr = item.address || {};
                                const possibleNames = [addr.city, addr.town, addr.village, addr.municipality, addr.county, addr.state_district, addr.suburb].filter(Boolean);

                                for (let name of possibleNames) {
                                    const cleanName = String(name).toLowerCase();
                                    for (let opt of citySelect.options) {
                                        if (opt.value && (opt.value.toLowerCase() === cleanName || cleanName.includes(opt.value.toLowerCase()))) {
                                            citySelect.value = opt.value;
                                            matched = true;
                                            break;
                                        }
                                    }
                                    if (matched) break;
                                }

                                if (!matched && item.display_name) {
                                    const fullText = item.display_name.toLowerCase();
                                    for (let opt of citySelect.options) {
                                        if (opt.value && opt.value !== 'Other' && fullText.includes(opt.value.toLowerCase())) {
                                            citySelect.value = opt.value;
                                            break;
                                        }
                                    }
                                }
                            };
                            box.appendChild(div);
                        });
                        box.classList.remove('hidden');
                    })
                    .catch(() => { box.classList.add('hidden'); });
            }, 300);
        }

        document.addEventListener('click', function(e) {
            const box = document.getElementById('locationSuggestions');
            const input = document.getElementById('location');
            if (box && e.target !== input && !box.contains(e.target)) {
                box.classList.add('hidden');
            }
        });

        document.getElementById('trialForm').addEventListener('submit', function(e) {
            if (document.querySelector('input[name="website"]').value) {
                e.preventDefault();
                return;
            }
            if (!document.getElementById('business_type').value) {
                e.preventDefault();
                alert('Please select your business industry.');
                return;
            }
            if (!document.getElementById('outlets').value) {
                e.preventDefault();
                alert('Please select number of outlets.');
                return;
            }

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            document.getElementById('btnText').textContent = 'CREATING MY ACCOUNT...';
            document.getElementById('btnSpinner').classList.remove('hidden');
        });
    </script>
</body>
</html>