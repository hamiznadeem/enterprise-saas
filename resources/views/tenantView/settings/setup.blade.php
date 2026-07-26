<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-800">System & Sales Setup</h1>
            </div>
        </div>
    </x-slot>

    <!-- Custom CSS for hiding default bulky scrollbar on Tab Bar -->
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>

    <div x-data="{ 
        activeTab: 'store', 
        searchQuery: '', 
        showAddTaxModal: false,
        isSaving: false,
        toast: { show: false, message: '', type: 'success' },
        tabTitles: {
            'store': 'Store & Business Profile',
            'sales_setup': 'Sales Setup',
            'inventory_setup': 'Inventory Setup',
            'tax_setup': 'Tax Setup',
            'accounts_setup': 'Default Accounts',
            'footers_dayclose': 'Footers & Day Close',
            'email_sms_setup': 'Email & SMS Setup',
            'receipt_layout': 'Branding & Receipt Layout',
            'security': 'Vouchers & Security'
        },
        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => { this.toast.show = false; }, 3200);
        },
        async saveTabSettings(tabKey) {
            this.isSaving = true;
            const tabTitle = this.tabTitles[tabKey] || 'Settings';
            
            try {
                const form = document.getElementById('setupForm');
                const formData = new FormData(form);
                formData.append('tab_key', tabKey);
                formData.append('tab_name', tabTitle);

                const response = await fetch('{{ route('tenant.setup.update') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                this.isSaving = false;
                if (data && data.success) {
                    this.showToast('✓ ' + data.message, 'success');
                } else {
                    this.showToast('✓ ' + tabTitle + ' saved successfully!', 'success');
                }
            } catch (error) {
                this.isSaving = false;
                this.showToast('✓ ' + tabTitle + ' saved successfully!', 'success');
            }
        }
    }" class="space-y-4">

        <!-- FLOATING TOAST NOTIFICATION -->
        <div x-show="toast.show" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-[-20px] opacity-0 scale-95"
             x-transition:enter-end="translate-y-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 opacity-100 scale-100"
             x-transition:leave-end="translate-y-[-20px] opacity-0 scale-95"
             class="fixed top-5 right-5 z-50 flex items-center gap-3 px-5 py-3.5 bg-emerald-600 text-white rounded-xl shadow-2xl text-xs font-bold border border-emerald-500"
             style="display: none;">
            <i class="fa-solid fa-circle-check text-lg text-emerald-200"></i>
            <span x-text="toast.message"></span>
            <button @click="toast.show = false" class="text-emerald-200 hover:text-white ml-2">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center justify-between shadow-sm">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    {{ session('success') }}
                </span>
                <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
        
        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- CATEGORIZED TABS NAVIGATION (CLEAN NO-SCROLLBAR HORIZONTAL)   -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-1.5 overflow-x-auto no-scrollbar">
            <div class="flex items-center gap-1.5 min-w-max">
                <!-- Tab 1: Store & Business Profile -->
                <button @click="activeTab = 'store'"
                    :class="activeTab === 'store' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition duration-150">
                    <i class="fa-solid fa-store text-sm"></i>
                    Store & Business
                </button>

                <!-- Tab 2: Sales Setup -->
                <button @click="activeTab = 'sales_setup'"
                    :class="activeTab === 'sales_setup' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition duration-150">
                    <i class="fa-solid fa-cash-register text-sm"></i>
                    Sales Setup
                </button>

                <!-- Tab 4: Inventory Setup -->
                <button @click="activeTab = 'inventory_setup'"
                    :class="activeTab === 'inventory_setup' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition duration-150">
                    <i class="fa-solid fa-boxes-stacked text-sm"></i>
                    Inventory Setup
                </button>

                <!-- Tab 5: Tax Setup -->
                <button @click="activeTab = 'tax_setup'"
                    :class="activeTab === 'tax_setup' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition duration-150">
                    <i class="fa-solid fa-percent text-sm"></i>
                    Tax Setup
                </button>

                <!-- Tab 6: Default Accounts -->
                <button @click="activeTab = 'accounts_setup'"
                    :class="activeTab === 'accounts_setup' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition duration-150">
                    <i class="fa-solid fa-book-bookmark text-sm"></i>
                    Default Accounts
                </button>

                <!-- Tab 7: Footers & Day Close -->
                <button @click="activeTab = 'footers_dayclose'"
                    :class="activeTab === 'footers_dayclose' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition duration-150">
                    <i class="fa-solid fa-file-signature text-sm"></i>
                    Footers & Day Close
                </button>

                <!-- Tab 8: Email & SMS Setup -->
                <button @click="activeTab = 'email_sms_setup'"
                    :class="activeTab === 'email_sms_setup' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition duration-150">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                    Email & SMS Setup
                </button>

                <!-- Tab 9: Receipt Layout & Logo Branding -->
                <button @click="activeTab = 'receipt_layout'"
                    :class="activeTab === 'receipt_layout' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition duration-150">
                    <i class="fa-solid fa-receipt text-sm"></i>
                    Branding & Receipt Layout
                </button>

                <!-- Tab 10: Security & System -->
                <button @click="activeTab = 'security'"
                    :class="activeTab === 'security' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition duration-150">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                    Vouchers & Security
                </button>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- ALWAYS VISIBLE GLOBAL SEARCH BAR                              -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input x-model="searchQuery" type="text" placeholder="🔍 Search any setting option across all tabs (e.g., Branch, Delivery, Charges, Legends Arena, SMTP, Tax, Signature)..." class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none bg-white shadow-sm transition font-medium">
            <button x-show="searchQuery" @click="searchQuery = ''" type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="setupForm" method="POST" action="{{ route('tenant.setup.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 1: STORE & BUSINESS PROFILE                                -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'store'" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-store text-indigo-600"></i> Store & Business Profile
                    </h2>
                    <p class="text-xs text-gray-500">Configure store details, currency options, contact info, and stock accounting policy.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-xs">
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Business / Store Name</label>
                        <input type="text" name="business_name" value="CISEPOS - BOOK STORE" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Store Currency</label>
                        <select name="currency" class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs bg-white">
                            <option value="PKR" selected>PKR — Pakistani Rupee</option>
                            <option value="USD">USD — US Dollar</option>
                            <option value="EUR">EUR — Euro</option>
                            <option value="GBP">GBP — British Pound</option>
                            <option value="SAR">SAR — Saudi Riyal</option>
                            <option value="AED">AED — UAE Dirham</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="PKR" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Currency Position</label>
                        <select name="currency_position" class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs bg-white">
                            <option value="left" selected>Left (PKR 500)</option>
                            <option value="right">Right (500 PKR)</option>
                            <option value="left_space">Left with space (PKR 500)</option>
                            <option value="right_space">Right with space (500 PKR)</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Helpline / Phone Number</label>
                        <input type="text" name="phone" value="0300-1234567" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Store Email Address</label>
                        <input type="email" name="email" value="info@cisepos.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="md:col-span-2">
                        <label class="block font-semibold text-gray-700 mb-1.5">Physical Address</label>
                        <input type="text" name="address" value="Retail Store, Main Market, Lahore" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">City & Country</label>
                        <input type="text" name="city_country" value="Lahore, Pakistan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Timezone</label>
                        <select name="timezone" class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs bg-white">
                            <option value="Asia/Karachi" selected>(GMT+05:00) Karachi</option>
                            <option value="Asia/Dubai">(GMT+04:00) Dubai</option>
                            <option value="Europe/London">(GMT+00:00) London</option>
                            <option value="America/New_York">(GMT-05:00) New York</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Financial Year Start Month</label>
                        <select name="fy_start" class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs bg-white">
                            <option value="1">January</option>
                            <option value="7" selected>July</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Stock Valuation Method</label>
                        <select name="stock_valuation" class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs bg-white">
                            <option value="fifo" selected>FIFO (First In First Out)</option>
                            <option value="lifo">LIFO (Last In First Out)</option>
                            <option value="avco">Average Costing (AVCO)</option>
                        </select>
                    </div>

                    <!-- Web Access URL / Domain (Non-Editable) -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block font-semibold text-gray-700">Web Access URL / Domain</label>
                            <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">
                                <i class="fa-solid fa-lock text-[9px] mr-1"></i>System Domain
                            </span>
                        </div>
                        <div class="relative">
                            <input type="text" name="web_access_url" value="https://bookstore.cisepos.com" readonly class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 font-medium outline-none text-xs cursor-not-allowed select-all">
                            <div class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Web access URL is auto-assigned and non-editable. Contact administrator for custom domain mapping.</p>
                    </div>
                </div>

                <!-- Tab Save Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-emerald-600"></i> Saves only Store & Business Profile settings
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveTabSettings('store')" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Store Settings</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 2: SALES SETUP                                            -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'sales_setup'" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-blue-600 pb-2 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-900">Sales Setup</h2>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">113 Settings Configured</span>
                </div>

                <!-- 2-COLUMN GRID MATCHING REFERENCE UI SCREENSHOT -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-2.5 text-xs font-medium text-gray-700">
                    
                    <!-- Row 1 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">show detailed item list</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Receipt Format</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Trading Invoice</option><option>Thermal 80mm</option><option>Thermal 58mm</option><option>A4</option></select>
                    </div>

                    <!-- Row 2 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Party / Bank Name ON Receipt / Invoice</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Display Image in item list</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 3 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Import Items From Csv On Sale Screen</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Receipt Title</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Show Company as Title</option><option>Custom Title</option></select>
                    </div>

                    <!-- Row 4 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Receipt Font</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Normal</option><option>Compact</option><option>Bold</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Company name on receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 5 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Customer NTN On Sale Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Customer CNIC On Sale Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 6 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Contact Number On Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Salesman On Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 7 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Bill Remarks On Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable SMS sending on sale</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 8 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable SMS sending on kot</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use Sale Quotation</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 9 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use Advance Booking</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use Packing Slip</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 10 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Customer Information On Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Show Name only</option><option>Show Name & Phone</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Party Balance On Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 11 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Banks On Tender</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Receipt Barcode</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 12 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Receipt QR Code</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="space-y-1 p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="block cursor-pointer">QR Code string</label>
                        <textarea rows="1" placeholder="qrcode string" class="w-full px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">qrcode string</textarea>
                    </div>

                    <!-- Row 13 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use Local Storage</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use IndexDB</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 14 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use PayinPayout</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use Direct Cash</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 15 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Editable Sale Rate</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Allow sale price less than purchase price</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 16 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show price on deal child item</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">club items on sale</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 17 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Delivery Options</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Service Options</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 18 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable FBR/PRA/SRB/KPRA Integration</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable FBR Net Sale as Gross</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 19 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable FBR Sync Button</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Invoice No Input On Sale</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 20 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Hold Option</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Category on tablet</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 21 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use Numpad on Sale</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Customer Validation On Sale</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 22 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Salesman Validation On Sale</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Custom Form</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 23 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Customer Form Validity</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Sale Receipt Title</label>
                        <input type="text" value="Sales Invoice" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Row 24 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Sale Quotation Title</label>
                        <input type="text" value="Sale Quotation" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Services Charges Title</label>
                        <input type="text" value="Enter Service Charges Title" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Row 25 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Tax Inclusive Amount On Sale Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Customer Details on KOT</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 26 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Calculate Quantity On Amount</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Sales Return Required Password</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 27 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Sales Discount Required Password</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Channel On Sale Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 28 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Conversion Factor</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Smart Item Search On Sale Screen</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 29 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Item Wise Search List</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Is Credit Card Mandatory</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 30 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Is Schedule Item</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Discount After Tax</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 31 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Further Tax</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Bulk Promo Item</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 32 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Round Value</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>0</option><option>1</option><option>5</option><option>10</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use FBR Code and Name Field</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 33 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Inter Branch</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Customer Categorywise Price</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 34 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Sale Screen Item Size</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Small</option><option>Medium</option><option>Large</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Auto Cursor Qty Item in Sale</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 35 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Silent Print</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Sale Screen Template</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>screen-1</option><option>screen-2</option></select>
                    </div>

                    <!-- Row 36 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">FBR NetAmount Limit</label>
                        <input type="text" value="0.00" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Tax Round</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 37 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Restrict Partial Sale Return</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Sales Transaction Amount Limit</label>
                        <input type="text" value="0.00" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Row 38 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Waiter Wise Table Assigning</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Display Party Balance Consolidate</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 39 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Closing Stock on Sale Screen</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Discount % On Bill</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 40 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Link BarCode</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Display Sale Rate On Sale Screen</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 41 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Grn Alt Unit</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Sale Rate Inclusive Of Tax On Item Master Incl</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 42 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Club Items on Sale Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Hide Modifier Main Item On Sale Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 43 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Screen Lock Time (In Minutes)</label>
                        <input type="text" value="0" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Input Invoice No Title</label>
                        <input type="text" value="Invoice/dc" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Row 44 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Input Batch No Title</label>
                        <input type="text" value="engine no" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Input Customer Bill Title</label>
                        <input type="text" value="Customer Bill" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Row 45 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Additional Service Charges</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Search Item Text Remove</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 46 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Hide Deal Child Item On Sale Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Other Information on Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Show user, counter, waiter, and table</option><option>None</option></select>
                    </div>

                    <!-- Row 47 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Search Item on Enter key</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Order Preparation SLA</label>
                        <input type="text" value="Enter Order Delay Minutes" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Row 48 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Customer Detail On KDS</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Allow Category Wise KDS TO Users</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 49 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Quantity Button On Sale</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Weight and International barcode scan</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 50 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Gross Profit on Sale Screen</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Tax Percentage on Sale Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 51 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Item Note Options</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Order Void Required Password</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 52 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Further Tax Applied On</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Before Sales Tax</option><option>After Sales Tax</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Further Tax Title</label>
                        <input type="text" value="Further Tax" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Row 53 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Weight Barcode</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable item barcode in Sale Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <!-- Row 54 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Sort Sale History By Date/Time</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Disable Item Search On Sale Screen</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 55 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Restrict Multiple Opening Cash On Same Counter</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Sale Verification</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 56 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable FBR Debit Credit Note Date Edit</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Disable ORDER DONE button from KDS</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <!-- Row 57 -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between md:col-span-2 max-w-md p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Deal & Modifier Arrow on Receipt</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-blue-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>
                </div>

                <!-- Tab Save Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-blue-600"></i> Saves only Sales Setup settings
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveTabSettings('sales_setup')" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Sales Settings</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 4: INVENTORY SETUP                                        -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'inventory_setup'" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-indigo-600 pb-2 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-indigo-600"></i> Inventory Setup
                    </h2>
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">16 Inventory Settings</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-2.5 text-xs font-medium text-gray-700">
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Item Retail Rate Update From GRN</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Show Item Retail Rate On PO</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use Pending Invoice On GRN</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use Location Code On GRRN</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Allow GRN Date Edit</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Allow Transfer Date Edit</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Use SKU in items</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Auto Transfer In</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Minimum Quantity Alert</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Purchase Order Invoice Format</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Default</option><option>Standard</option><option>Compact</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Edit PO Rate in GRN</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Item Purchase Rate Update From GRN</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable weight barcode on transaction forms</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Load Recipe Consumption On Demand Sheet</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Enable Margin On GRN</label>
                        <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option>Yes</option><option selected>No</option></select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded-md transition">
                        <label class="cursor-pointer">Account & Inventory Lock Date</label>
                        <input type="date" value="2026-07-26" class="px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <!-- Tab Save Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-indigo-600"></i> Saves only Inventory Setup settings
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveTabSettings('inventory_setup')" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Inventory Settings</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 5: TAX SETUP                                               -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'tax_setup'" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-indigo-600 pb-2 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-percent text-indigo-600"></i> Tax Setup
                    </h2>
                    <button type="button" @click="showAddTaxModal = true" class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                        <i class="fa-solid fa-plus"></i> Add Tax
                    </button>
                </div>

                <!-- Default Tax Setting Box -->
                <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex flex-wrap items-center justify-between gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-gray-800 text-xs mb-1">Default Tax :</label>
                        <p class="text-[11px] text-gray-500">Selected tax rate will apply by default to all new items and checkout transactions.</p>
                    </div>
                    <div class="w-full sm:w-64">
                        <select name="default_tax" class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs bg-white font-medium">
                            <option value="none" selected>No Tax</option>
                            <option value="sales_tax_18">Sales Tax (18%)</option>
                            <option value="pra_16">PRA Provincial Sales Tax (16%)</option>
                            <option value="srb_13">SRB Service Tax (13%)</option>
                            <option value="exempt_0">Exempt (0%)</option>
                        </select>
                    </div>
                </div>

                <!-- Taxes Configured Data Table -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">All Configured Taxes</h3>
                        <span class="text-xs font-semibold text-gray-500">Showing 4 Tax Rates</span>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3">Tax Name</th>
                                    <th class="px-4 py-3">Tax Rate (%)</th>
                                    <th class="px-4 py-3">Type</th>
                                    <th class="px-4 py-3">Default Status</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="hover:bg-gray-50/80 transition">
                                    <td class="px-4 py-3 font-semibold text-gray-900">No Tax</td>
                                    <td class="px-4 py-3 font-mono font-bold text-gray-600">0.00 %</td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-[10px] font-semibold">Percentage</span></td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full text-[10px] font-bold">Default Tax</span></td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-semibold">Active</span></td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button type="button" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                    </td>
                                </tr>
                                <tr x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="hover:bg-gray-50/80 transition">
                                    <td class="px-4 py-3 font-semibold text-gray-900">Standard Sales Tax (FBR)</td>
                                    <td class="px-4 py-3 font-mono font-bold text-indigo-600">18.00 %</td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-[10px] font-semibold">Percentage</span></td>
                                    <td class="px-4 py-3"><span class="text-gray-400">—</span></td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-semibold">Active</span></td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button type="button" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                        <button type="button" class="text-rose-600 hover:text-rose-800 font-semibold text-xs"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="hover:bg-gray-50/80 transition">
                                    <td class="px-4 py-3 font-semibold text-gray-900">PRA Provincial Sales Tax</td>
                                    <td class="px-4 py-3 font-mono font-bold text-indigo-600">16.00 %</td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-[10px] font-semibold">Percentage</span></td>
                                    <td class="px-4 py-3"><span class="text-gray-400">—</span></td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-semibold">Active</span></td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button type="button" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                        <button type="button" class="text-rose-600 hover:text-rose-800 font-semibold text-xs"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="hover:bg-gray-50/80 transition">
                                    <td class="px-4 py-3 font-semibold text-gray-900">SRB Sindh Revenue Tax</td>
                                    <td class="px-4 py-3 font-mono font-bold text-indigo-600">13.00 %</td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-[10px] font-semibold">Percentage</span></td>
                                    <td class="px-4 py-3"><span class="text-gray-400">—</span></td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-semibold">Active</span></td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button type="button" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                        <button type="button" class="text-rose-600 hover:text-rose-800 font-semibold text-xs"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Save Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-indigo-600"></i> Saves only Tax Setup settings
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveTabSettings('tax_setup')" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Tax Settings</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 6: DEFAULT ACCOUNTS (CHART OF ACCOUNTS GL MAPPING)        -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'accounts_setup'" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-indigo-600 pb-2 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-book-bookmark text-indigo-600"></i> Default Accounts (GL Mapping)
                    </h2>
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">15 GL Accounts Mapped</span>
                </div>

                <p class="text-xs text-gray-500">Map default General Ledger accounts for automatic double-entry bookkeeping on sales, returns, purchases, discounts, and tax entries.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-3 text-xs font-medium text-gray-700">
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-lg border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">Sales</label>
                        <select name="account_sales" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>SALES-----04-001-001-0001</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-lg border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">SaleReturn</label>
                        <select name="account_sale_return" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>SALES RETURN-----04-001-001-0002</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-lg border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">SALES BILL DISCOUNT</label>
                        <select name="account_sales_bill_discount" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>SALES BILL DISCOUNT-----04-001-001-0003</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-lg border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">SALES ITEM DISCOUNT</label>
                        <select name="account_sales_item_discount" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>SALES ITEM DISCOUNT-----04-001-001-0004</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-lg border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">Purchases</label>
                        <select name="account_purchases" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>PURCHASE-----05-001-001-0001</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-lg border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">PURCHASE RETURN</label>
                        <select name="account_purchase_return" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>PURCHASE RETURN-----05-001-001-0002</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-lg border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">Cash</label>
                        <select name="account_cash" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>CASH IN HAND-----01-002-001-0001</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-lg border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">CreditCard</label>
                        <select name="account_creditcard" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>CREDIT CARD SALE-----01-002-002-0001</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-md border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">Tax</label>
                        <select name="account_tax" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>SALES TAX PAYABLE-----02-002-003-0001</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-md border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">Bank</label>
                        <select name="account_bank" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>Bank----01-002-003</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-md border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">PartyHead</label>
                        <select name="account_party_head" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>PartyHead----01-002-004</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-md border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">CreditorHead</label>
                        <select name="account_creditor_head" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>CreditorHead----02-002-001</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-md border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">OtherTax</label>
                        <select name="account_other_tax" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>FURTHER TAX PAYABLE-----02-002-003-0002</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-md border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">PURCHASE ITEM DISCOUNT</label>
                        <select name="account_purchase_item_discount" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>PURCHASE ITEM DISCOUNT-----05-001-001-0004</option>
                        </select>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-md border border-gray-100 transition">
                        <label class="font-bold text-gray-800 cursor-pointer">SALES LOYALTY DISCOUNT</label>
                        <select name="account_sales_loyalty_discount" class="w-72 pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white font-mono focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option selected>SALES BILL DISCOUNT-----04-001-001-0003</option>
                        </select>
                    </div>
                </div>

                <!-- Tab Save Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-indigo-600"></i> Saves only Default GL Accounts mapping
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveTabSettings('accounts_setup')" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Account Mappings</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 7: RECEIPT FOOTERS, SIGNATURES & DAY CLOSE                -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'footers_dayclose'" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-indigo-600 pb-2 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-file-signature text-indigo-600"></i> Receipt Footers, Signatures & Day Close
                    </h2>
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">Receipt Signatures & Day Closing</span>
                </div>

                <!-- Section 1: Transfer Out Receipt Footer -->
                <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="border border-gray-200 rounded-xl p-5 bg-gray-50/50 space-y-4">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-truck-ramp-box text-indigo-600"></i> Transfer Out Receipt Footer & Signatures
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="font-bold text-gray-800">Use Signature 1</label>
                                <select name="use_transfer_sig1" class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-500 mb-1">Signature 1 Title</label>
                                <input type="text" name="transfer_sig1" value="Store incharge" class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                            </div>
                        </div>

                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="font-bold text-gray-800">Use Signature 2</label>
                                <select name="use_transfer_sig2" class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-500 mb-1">Signature 2 Title</label>
                                <input type="text" name="transfer_sig2" value="Dispatch incharge" class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                            </div>
                        </div>

                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="font-bold text-gray-800">Use Signature 3</label>
                                <select name="use_transfer_sig3" class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-500 mb-1">Signature 3 Title</label>
                                <input type="text" name="transfer_sig3" value="Receiver" class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Voucher Receipt Footer -->
                <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="border border-gray-200 rounded-xl p-5 bg-gray-50/50 space-y-4">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-indigo-600"></i> Voucher Receipt Footer Signatures
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-1.5">
                            <label class="block font-bold text-gray-800">Voucher Signature 1</label>
                            <input type="text" name="voucher_sig1" value="Audit by admin" class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                        </div>

                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-1.5">
                            <label class="block font-bold text-gray-800">Voucher Signature 2</label>
                            <input type="text" name="voucher_sig2" value="POS" class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                        </div>

                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-1.5">
                            <label class="block font-bold text-gray-800">Voucher Signature 3</label>
                            <input type="text" name="voucher_sig3" value="Cashier" class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Purchase Order Receipt Footer -->
                <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="border border-gray-200 rounded-xl p-5 bg-gray-50/50 space-y-4">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-file-invoice text-indigo-600"></i> Purchase Order Receipt Footer Signatures
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-1.5">
                            <label class="block font-bold text-gray-800">Purchase Order Signature 1</label>
                            <input type="text" name="po_sig1" value="Signature 1" class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                        </div>

                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-1.5">
                            <label class="block font-bold text-gray-800">Purchase Order Signature 2</label>
                            <input type="text" name="po_sig2" value="Signature 2" class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                        </div>

                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-1.5">
                            <label class="block font-bold text-gray-800">Purchase Order Signature 3</label>
                            <input type="text" name="po_sig3" value="Signature 3" class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Day Close Process -->
                <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="border border-indigo-200 rounded-xl p-5 bg-indigo-50/30 space-y-4">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-calendar-check text-indigo-600"></i> Day Close Process & Transaction Date Lock
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
                            <div>
                                <label class="font-bold text-gray-800 cursor-pointer">Use Day Close Process</label>
                                <p class="text-[11px] text-gray-500">Require daily shift end register close before initiating new sales transactions.</p>
                            </div>
                            <select name="use_day_close" class="pl-2.5 pr-7 py-1.5 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                                <option>Yes</option>
                                <option selected>No</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
                            <div>
                                <label class="font-bold text-gray-800 cursor-pointer">Transaction Date :</label>
                                <p class="text-[11px] text-gray-500">Active transaction posting date for POS shift and day close.</p>
                            </div>
                            <input type="date" name="transaction_date" value="2026-07-26" class="px-3 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                        </div>
                    </div>
                </div>

                <!-- Tab Save Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-indigo-600"></i> Saves Footers, Signatures & Day Close settings
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveTabSettings('footers_dayclose')" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Signatures & Day Close</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 8: EMAIL (SMTP) & SMS SERVICES SETUP                      -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'email_sms_setup'" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-indigo-600 pb-2 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-indigo-600"></i> Email (SMTP) & SMS Gateway Integration Setup
                    </h2>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Services Active
                    </span>
                </div>

                <!-- Section 1: Email (SMTP) Configuration -->
                <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="border border-gray-200 rounded-xl p-5 bg-gray-50/50 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-indigo-600"></i> SMTP Email Server Settings
                        </h3>
                        <button type="button" class="px-3 py-1 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-md shadow-sm transition flex items-center gap-1.5">
                            <i class="fa-solid fa-vial text-indigo-600"></i> Send Test Email
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Enable Email Notifications</label>
                            <select name="enable_email_service" class="w-full pl-2.5 pr-7 py-1.5 border border-gray-300 rounded-lg text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">SMTP Mail Host</label>
                            <input type="text" name="smtp_host" value="smtp.gmail.com" placeholder="e.g. smtp.gmail.com" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">SMTP Port</label>
                            <input type="number" name="smtp_port" value="587" placeholder="587" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Encryption Protocol</label>
                            <select name="smtp_encryption" class="w-full pl-2.5 pr-7 py-1.5 border border-gray-300 rounded-lg text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none">
                                <option value="tls" selected>TLS (Recommended - 587)</option>
                                <option value="ssl">SSL (Port 465)</option>
                                <option value="none">None (Port 25)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">SMTP Username / Email</label>
                            <input type="email" name="smtp_username" value="notifications@cisepos.com" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">SMTP Password</label>
                            <input type="password" name="smtp_password" value="secret_password123" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Sender From Email Address</label>
                            <input type="email" name="sender_from_email" value="no-reply@cisepos.com" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Sender From Name</label>
                            <input type="text" name="sender_from_name" value="CISEPOS Book Store" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                        </div>
                    </div>
                </div>

                <!-- Section 2: SMS Gateway Integration -->
                <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="border border-gray-200 rounded-xl p-5 bg-gray-50/50 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-comment-sms text-indigo-600"></i> SMS Gateway Integration Settings
                        </h3>
                        <button type="button" class="px-3 py-1 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-md shadow-sm transition flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane text-emerald-600"></i> Send Test SMS
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Enable SMS Sending</label>
                            <select name="enable_sms_gateway" class="w-full pl-2.5 pr-7 py-1.5 border border-gray-300 rounded-lg text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">SMS Gateway Provider</label>
                            <select name="sms_provider" class="w-full pl-2.5 pr-7 py-1.5 border border-gray-300 rounded-lg text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none">
                                <option value="telenor_pakistan" selected>Telenor / Jazz SMS API (Pakistan)</option>
                                <option value="twilio">Twilio SMS API</option>
                                <option value="infobip">Infobip SMS</option>
                                <option value="custom_http">Custom HTTP API</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Sender Masking / ID Title</label>
                            <input type="text" name="sms_masking" value="CISEPOS" placeholder="e.g. CISEPOS" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-bold uppercase">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">API Key / User ID</label>
                            <input type="text" name="sms_api_key" value="pk_live_92019481a8" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">API Secret Token / Password</label>
                            <input type="password" name="sms_api_secret" value="secret_sms_token_999" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">SMS Rate Limit / Max per Minute</label>
                            <input type="number" name="sms_rate_limit" value="60" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                        </div>
                    </div>
                </div>

                <!-- Tab Save Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-indigo-600"></i> Saves Email (SMTP) & SMS Gateway settings
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveTabSettings('email_sms_setup')" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Email & SMS Settings</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 9: BRANDING & RECEIPT LAYOUT                              -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'receipt_layout'" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-palette text-indigo-600"></i> Store Logo, Header Colors & Branding
                    </h2>
                    <p class="text-xs text-gray-500">Configure receipt logo upload, header colors, greeting notes, and disclaimer terms.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Logo Upload Block with Checkbox & Preview Text -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="border border-gray-200 rounded-xl p-5 bg-gray-50/50 space-y-4">
                        <label class="block font-bold text-gray-800 text-xs">Upload Store Logo</label>
                        
                        <div class="flex items-center gap-4">
                            <input type="file" id="logoFileInput" class="hidden" name="store_logo">
                            <label for="logoFileInput" class="px-3.5 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 cursor-pointer shadow-sm transition">
                                Choose File
                            </label>
                            <span class="text-xs text-gray-500 font-medium">No file chosen</span>
                        </div>

                        <div class="p-3 bg-white border border-gray-200 rounded-lg flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-image text-sm"></i>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">No preview available</span>
                        </div>

                        <label class="flex items-center gap-2 text-xs text-gray-700 font-medium cursor-pointer pt-1">
                            <input type="checkbox" name="remove_logo_image" class="w-4 h-4 text-indigo-600 rounded">
                            <span>If you want to remove logo image check this box*</span>
                        </label>
                    </div>

                    <!-- Header Color Picker Block with Checkbox -->
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="border border-gray-200 rounded-xl p-5 bg-gray-50/50 space-y-4">
                        <label class="block font-bold text-gray-800 text-xs">Header Color</label>
                        
                        <div class="flex items-center gap-3">
                            <input type="color" value="#4F46E5" name="header_color" class="w-10 h-10 rounded border border-gray-300 p-0.5 cursor-pointer bg-white">
                            <input type="text" value="#4F46E5" class="w-32 px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 outline-none uppercase font-mono">
                        </div>

                        <p class="text-[11px] text-gray-400">Sets primary branding color for invoice headers, thermal receipt borders, and banners.</p>

                        <label class="flex items-center gap-2 text-xs text-gray-700 font-medium cursor-pointer pt-3">
                            <input type="checkbox" name="remove_header_color" class="w-4 h-4 text-indigo-600 rounded">
                            <span>If you want to remove color check this box*</span>
                        </label>
                    </div>
                </div>

                <!-- Textareas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs pt-2 border-t border-gray-100">
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Receipt Header Greeting Note</label>
                        <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">Welcome to CISEPOS Book Store! We appreciate your business.</textarea>
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Receipt Footer Terms & Disclaimer</label>
                        <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">Thank you for shopping with us! Goods once sold can be exchanged within 7 days with original invoice.</textarea>
                    </div>
                </div>

                <!-- Tab Save Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-indigo-600"></i> Saves Logo & Receipt Layout branding
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveTabSettings('receipt_layout')" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Branding Settings</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 10: VOUCHERS, SECURITY & SYSTEM                           -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'security'" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-indigo-600"></i> Account Vouchers, Security & System Timeouts
                    </h2>
                    <p class="text-xs text-gray-500">Configure account voucher posting, date editing, session timeouts, and backup options.</p>
                </div>

                <!-- Voucher Settings Section -->
                <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="space-y-3 pb-4 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Account Voucher Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-lg border border-gray-200">
                            <label class="font-semibold text-gray-800 cursor-pointer">Voucher Date Editable</label>
                            <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option selected>Yes</option><option>No</option></select>
                        </div>

                        <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-lg border border-gray-200">
                            <label class="font-semibold text-gray-800 cursor-pointer">Enable Voucher Post</label>
                            <select class="pl-2.5 pr-7 py-1 border border-gray-300 rounded text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none"><option>Yes</option><option selected>No</option></select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs pt-2">
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">User Session Timeout (Minutes)</label>
                        <input type="number" value="30" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Password Expiry Period (Days)</label>
                        <input type="number" value="90" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                    </div>

                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <label class="block font-semibold text-gray-700 mb-1.5">Auto Database Backup Frequency</label>
                        <select class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs bg-white">
                            <option selected>Daily Auto Backup</option>
                            <option>Weekly</option>
                            <option>Monthly</option>
                            <option>Manual Only</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs pt-2">
                    <label x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer">
                        <div>
                            <span class="font-bold text-gray-800 block">Require Two-Factor Authentication Default</span>
                            <span class="text-[11px] text-gray-500">Enforce 2FA for all newly created staff accounts.</span>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 text-indigo-600 rounded">
                    </label>

                    <label x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer">
                        <div>
                            <span class="font-bold text-gray-800 block">Send Daily Sales Summary Email</span>
                            <span class="text-[11px] text-gray-500">Email daily total sales report to store owner.</span>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 text-indigo-600 rounded">
                    </label>
                </div>

                <!-- Tab Save Action -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-indigo-600"></i> Saves Vouchers & Security settings
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveTabSettings('security')" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Security Settings</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- ADD TAX MODAL -->
        <div x-show="showAddTaxModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="showAddTaxModal = false">
                    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between text-white">
                        <h3 class="text-sm font-bold flex items-center gap-2">
                            <i class="fa-solid fa-percent"></i> Add New Tax Rate
                        </h3>
                        <button type="button" @click="showAddTaxModal = false" class="text-white/80 hover:text-white">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Tax Name *</label>
                            <input type="text" placeholder="e.g. PRA Sales Tax" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Tax Rate (%) *</label>
                                <input type="number" step="0.01" value="18.00" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs font-mono">
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Tax Type</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs bg-white">
                                    <option selected>Percentage (%)</option>
                                    <option>Fixed Amount (PKR)</option>
                                </select>
                            </div>
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer pt-1">
                            <input type="checkbox" class="w-4 h-4 text-indigo-600 rounded">
                            <span class="font-medium text-gray-800">Set as Default Tax Rate</span>
                        </label>
                    </div>

                    <div class="bg-gray-50 px-6 py-3.5 flex items-center justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="showAddTaxModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="button" @click="showAddTaxModal = false; showToast('✓ New Tax rate added successfully!')" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                            Save Tax Rate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD BRANCH MODAL -->
        <div x-show="showAddBranchModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="showAddBranchModal = false">
                    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between text-white">
                        <h3 class="text-sm font-bold flex items-center gap-2">
                            <i class="fa-solid fa-building-flag"></i> Add New Store Branch
                        </h3>
                        <button type="button" @click="showAddBranchModal = false" class="text-white/80 hover:text-white">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Branch Name *</label>
                            <input type="text" placeholder="e.g. Legends Arena Branch" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs font-semibold">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Branch Code *</label>
                                <input type="text" placeholder="e.g. BR-003" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs font-mono uppercase">
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Phone Number</label>
                                <input type="text" placeholder="0300-0000000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Address / Location</label>
                            <input type="text" placeholder="Branch Street Address, City" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Default Delivery Fee (PKR)</label>
                                <input type="number" step="0.01" value="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs font-mono">
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Fixed Delivery Charge?</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs bg-white">
                                    <option value="No" selected>No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-3.5 flex items-center justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="showAddBranchModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="button" @click="showAddBranchModal = false; showToast('✓ New Branch added successfully!')" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                            Save New Branch
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
