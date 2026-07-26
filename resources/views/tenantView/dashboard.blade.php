<x-app-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    <div class="space-y-6">

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- CLEAN ENTERPRISE TOP HEADER BAR                               -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 tracking-tight flex items-center gap-2">
                        Welcome, {{ auth()->user()->name }}
                        @php
                            $activeBranch = app()->bound('currentBranch') ? app('currentBranch') : null;
                        @endphp
                        <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100 font-mono">
                            {{ $activeBranch ? $activeBranch->branch_name : 'RETAIL STORE' }}
                        </span>
                    </h1>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">
                        Overview of sales performance, transactions, and store activity for <span class="font-bold text-gray-700">{{ now()->format('d M, Y') }}</span>.
                    </p>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ Route::has('pos.index') ? route('pos.index') : '#' }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-cash-register"></i> Open POS Terminal
                </a>
                <a href="{{ route('tenant.branch-setup') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition">
                    <i class="fa-solid fa-store text-gray-500"></i> Branch Setup
                </a>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- SALES STATISTICS GRID CARD (EXACT 10 OPTIONS FROM USER IMAGE) -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-2xs overflow-hidden">
            
            <!-- Card Header -->
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                <div class="w-9 h-9 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="fa-solid fa-wallet text-base"></i>
                </div>
                <h2 class="text-base font-bold text-gray-900 tracking-tight">Sales Statistics</h2>
            </div>

            <!-- 10 Stat Grid (2 Rows x 5 Columns) -->
            <div class="divide-y divide-gray-200">
                
                <!-- Row 1: Today's 5 Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 divide-y sm:divide-y-0 sm:divide-x divide-gray-200 p-4 text-center bg-white">
                    
                    <!-- 1. Today's Total Sale Amount -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-black text-gray-900 font-mono tracking-tight">PKR 504,500</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1.5">Today's Total Sale Amount</div>
                    </div>

                    <!-- 2. Total Sales Today -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-black text-gray-900 font-mono tracking-tight">2</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1.5">Total Sales Today</div>
                    </div>

                    <!-- 3. Total Item Discounts Today -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-black text-gray-900 font-mono tracking-tight">PKR 0.00</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1.5">Total Item Discounts Today</div>
                    </div>

                    <!-- 4. Total Bill Discounts Today -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-black text-gray-900 font-mono tracking-tight">PKR 0.00</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1.5">Total Bill Discounts Today</div>
                    </div>

                    <!-- 5. Total Sales Today (Net) -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-black text-indigo-700 font-mono tracking-tight">PKR 504,500.00</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1.5">Total Sales Today</div>
                    </div>

                </div>

                <!-- Row 2: Yesterday's 5 Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 divide-y sm:divide-y-0 sm:divide-x divide-gray-200 p-4 text-center bg-gray-50/40">
                    
                    <!-- 6. Yesterday's Total Sale Amount -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-bold text-gray-800 font-mono tracking-tight">PKR 29,272</div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mt-1.5">Yesterday's Total Sale Amount</div>
                    </div>

                    <!-- 7. Total Sales Yesterday -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-bold text-gray-800 font-mono tracking-tight">5</div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mt-1.5">Total Sales Yesterday</div>
                    </div>

                    <!-- 8. Total Item Discounts Yesterday -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-bold text-gray-800 font-mono tracking-tight">PKR 200.00</div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mt-1.5">Total Item Discounts Yesterday</div>
                    </div>

                    <!-- 9. Total Bill Discounts Yesterday -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-bold text-gray-800 font-mono tracking-tight">PKR 30.00</div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mt-1.5">Total Bill Discounts Yesterday</div>
                    </div>

                    <!-- 10. Total Sales Yesterday (Net) -->
                    <div class="p-3.5 flex flex-col justify-center items-center">
                        <div class="text-xl lg:text-2xl font-bold text-gray-800 font-mono tracking-tight">PKR 29,042.00</div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mt-1.5">Total Sales Yesterday</div>
                    </div>

                </div>

            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- SALES ANALYTICS & PAYMENT BREAKDOWN                           -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Chart Section (2 Columns) -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-chart-area text-indigo-600"></i> Sales Performance History
                        </h2>
                        <p class="text-xs text-gray-500">Daily sales revenue timeline over recent periods.</p>
                    </div>
                    <select class="px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 bg-white outline-none">
                        <option selected>Last 30 Days</option>
                        <option>This Week</option>
                        <option>This Month</option>
                    </select>
                </div>

                <div class="w-full h-72 relative">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Payment Breakdown (1 Column) -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-2xs flex flex-col justify-between space-y-5">
                <div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-wallet text-indigo-600"></i> Today's Payment Modes
                        </h2>
                        <span class="text-xs font-mono font-bold text-gray-500">PKR</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <!-- Cash -->
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-800 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Cash Sale
                                </span>
                                <span class="font-mono font-bold text-gray-900">460,000.00</span>
                            </div>
                            <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 91%"></div>
                            </div>
                        </div>

                        <!-- Credit Card -->
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-800 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span> Credit Card Sale
                                </span>
                                <span class="font-mono font-bold text-gray-900">40,000.00</span>
                            </div>
                            <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 8%"></div>
                            </div>
                        </div>

                        <!-- Party Sale -->
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-800 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Party / Credit Sale
                                </span>
                                <span class="font-mono font-bold text-gray-900">4,500.00</span>
                            </div>
                            <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-purple-500 h-1.5 rounded-full" style="width: 1%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-gray-500 font-medium">Gross Total Today:</span>
                    <span class="font-mono font-extrabold text-indigo-700">PKR 504,500.00</span>
                </div>
            </div>

        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- ITEM WISE & CATEGORY WISE SALES TABLES                        -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Table 1: Item Wise Sales -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-indigo-600"></i> Item Wise Sales by Value
                    </h2>
                    <span class="text-xs font-semibold text-gray-500">Last 30 Days</span>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-200">
                            <tr>
                                <th class="py-2.5 px-3">Product Name</th>
                                <th class="py-2.5 px-3">Quantity</th>
                                <th class="py-2.5 px-3 text-right">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium bg-white">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">zip pata black</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">1,200.00</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-indigo-700">PKR 600,000.00</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">11 line org LCT</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">600.00</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-indigo-700">PKR 150,000.00</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">sunslik 300ml</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">190.00</td>
                                <td class="py-2.5 px-3 text-right font-mono text-gray-800">PKR 114,000.00</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">Sunblk</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">120.00</td>
                                <td class="py-2.5 px-3 text-right font-mono text-gray-800">PKR 72,000.00</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">Book Oxford</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">100.00</td>
                                <td class="py-2.5 px-3 text-right font-mono text-gray-800">PKR 49,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table 2: Category Wise Sales -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-indigo-600"></i> Category Wise Sales by Value
                    </h2>
                    <span class="text-xs font-semibold text-gray-500">Last 30 Days</span>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-200">
                            <tr>
                                <th class="py-2.5 px-3">Category Name</th>
                                <th class="py-2.5 px-3">Quantity</th>
                                <th class="py-2.5 px-3 text-right">Total Sales</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium bg-white">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">zip pata</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">1,209.00</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-indigo-700">PKR 604,500.00</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">11 LINE ORG</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">601.00</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-indigo-700">PKR 150,250.00</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">shampos</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">190.00</td>
                                <td class="py-2.5 px-3 text-right font-mono text-gray-800">PKR 114,000.00</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">2 by Maaz</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">120.00</td>
                                <td class="py-2.5 px-3 text-right font-mono text-gray-800">PKR 72,000.00</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-900">4 line</td>
                                <td class="py-2.5 px-3 font-mono text-gray-600">796.00</td>
                                <td class="py-2.5 px-3 text-right font-mono text-gray-800">PKR 52,251.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            // Clean Crisp Gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.15)');
            gradient.addColorStop(1, 'rgba(255, 255, 255, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['9 Jul', '10 Jul', '11 Jul', '13 Jul', '15 Jul', '16 Jul', '21 Jul', '22 Jul', '23 Jul', '24 Jul', '25 Jul', 'Today'],
                    datasets: [{
                        label: 'Sales Revenue',
                        data: [50000, 20000, 30000, 40000, 20000, 10000, 30000, 350000, 180000, 220000, 490000, 504500],
                        borderColor: '#4f46e5',
                        borderWidth: 2,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Sales: PKR ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 }, color: '#64748b' }
                        },
                        y: {
                            min: 0,
                            max: 600000,
                            ticks: {
                                stepSize: 100000,
                                font: { size: 10 },
                                color: '#64748b',
                                callback: function(value) { return 'PKR ' + value.toLocaleString(); }
                            },
                            grid: { color: '#f1f5f9' }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>