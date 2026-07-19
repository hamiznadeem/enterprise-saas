<x-app-layout>
    <x-slot name="header">Pharmacy POS</x-slot>

    <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-10rem)]">
        <!-- Left: Product Search & List -->
        <div class="flex-1 flex flex-col bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" id="posSearch" placeholder="Search medicines by name, brand, generic, or barcode..." class="w-full pl-11 pr-4 py-3 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" autofocus>
                </div>
            </div>
            <div id="posResults" class="flex-1 overflow-y-auto p-4">
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>
                    <p class="text-sm">Search for medicines to add to cart</p>
                </div>
            </div>
        </div>

        <!-- Right: Cart -->
        <div class="w-full lg:w-96 flex flex-col bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    Cart
                    <span id="cartCount" class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold">0</span>
                </h2>
            </div>

            <div id="cartItems" class="flex-1 overflow-y-auto p-4 space-y-3">
                <div class="text-center py-12 text-gray-400">
                    <p class="text-sm">Cart is empty</p>
                </div>
            </div>

            <!-- Cart Footer -->
            <div class="border-t border-gray-200 p-4 space-y-3 bg-gray-50">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Patient (optional for walk-in)</label>
                    <input type="text" id="posPatientName" placeholder="Walk-in" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                </div>
                
                <!-- Discount & Tax Section -->
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Disc (Rs.)</label>
                        <input type="number" id="posDiscountAmount" value="0" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white" oninput="calculateTotals()">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Disc (%)</label>
                        <input type="number" id="posDiscountPercent" value="0" min="0" max="100" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white" oninput="calculateTotals()">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tax (%)</label>
                        <input type="number" id="posTaxPercent" value="0" min="0" max="100" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white" oninput="calculateTotals()">
                    </div>
                </div>

                <!-- Totals -->
                <div class="space-y-1.5 pt-2 border-t border-gray-200">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span id="subtotalDisplay">Rs. 0</span>
                    </div>
                    <div class="flex justify-between text-sm text-red-500">
                        <span>Discount</span>
                        <span id="discountDisplay">- Rs. 0</span>
                    </div>
                    <div class="flex justify-between text-sm text-blue-600">
                        <span>Tax</span>
                        <span id="taxDisplay">+ Rs. 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-900 pt-1.5 border-t border-gray-300">
                        <span>Total</span>
                        <span id="totalDisplay">Rs. 0</span>
                    </div>
                </div>
                
                <button id="checkoutBtn" onclick="checkout()" disabled class="w-full py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Checkout
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        let searchTimeout;
        document.getElementById('posSearch').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            const container = document.getElementById('posResults');
            if (q.length < 2) {
                container.innerHTML = '<div class="text-center py-16 text-gray-400"><svg class="w-16 h-16 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg><p class="text-sm">Search for medicines to add to cart</p></div>';
                return;
            }
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('pos.search') }}?q=${encodeURIComponent(q)}&t=${Date.now()}`)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.length) {
                            container.innerHTML = '<div class="text-center py-16 text-gray-400"><p class="text-sm">No medicines found</p></div>';
                            return;
                        }
                        container.innerHTML = '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">' + data.map(m => `
                            <div class="p-4 rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-md transition group">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">${m.name}</p>
                                        <p class="text-xs text-gray-400">${m.generic_name || ''}</p>
                                    </div>
                                    ${m.stock_quantity <= 10 ? '<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-50 text-red-600">LOW</span>' : ''}
                                </div>
                                <div class="flex items-end justify-between mt-3">
                                    <p class="text-lg font-bold text-gray-900">Rs. ${parseFloat(m.sale_price).toLocaleString()}</p>
                                    <button onclick="addToCart(${m.id}, '${m.name.replace(/'/g, "\\'")}', ${m.sale_price}, ${m.stock_quantity})" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition opacity-0 group-hover:opacity-100 ${m.stock_quantity === 0 ? 'hidden' : ''}">+ Add</button>
                                    ${m.stock_quantity === 0 ? '<span class="text-xs text-red-500 font-medium">Out of Stock</span>' : ''}
                                </div>
                            </div>
                        `).join('') + '</div>';
                    });
            }, 300);
        });

        function addToCart(id, name, price, stock) {
            let existing = cart.find(i => i.id === id);
            if (existing) {
                if (existing.qty < stock) existing.qty++;
                else alert('Maximum available stock reached!');
            } else {
                cart.push({ id, name, price, qty: 1, stock });
            }
            renderCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(i => i.id !== id);
            renderCart();
        }

        function updateQty(id, delta) {
            let item = cart.find(i => i.id === id);
            if (!item) return;
            item.qty += delta;
            if (item.qty <= 0) return removeFromCart(id);
            if (item.qty > item.stock) { item.qty = item.stock; alert('Maximum stock reached!'); }
            renderCart();
        }

        function calculateTotals() {
            let subtotal = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
            let discAmt = parseFloat(document.getElementById('posDiscountAmount').value) || 0;
            let discPerc = parseFloat(document.getElementById('posDiscountPercent').value) || 0;
            let taxPerc = parseFloat(document.getElementById('posTaxPercent').value) || 0;
            
            let totalDiscount = discAmt + (subtotal * (discPerc / 100));
            let afterDiscount = subtotal - totalDiscount;
            if (afterDiscount < 0) afterDiscount = 0;

            let taxAmount = afterDiscount * (taxPerc / 100);
            let total = afterDiscount + taxAmount;

            document.getElementById('subtotalDisplay').innerText = 'Rs. ' + subtotal.toLocaleString();
            document.getElementById('discountDisplay').innerText = '- Rs. ' + totalDiscount.toLocaleString();
            document.getElementById('taxDisplay').innerText = '+ Rs. ' + taxAmount.toLocaleString();
            document.getElementById('totalDisplay').innerText = 'Rs. ' + total.toLocaleString();
            
            document.getElementById('checkoutBtn').disabled = cart.length === 0;
            
            return { subtotal, totalDiscount, taxAmount, total, discAmt, discPerc, taxPerc };
        }

        function renderCart() {
            const container = document.getElementById('cartItems');
            document.getElementById('cartCount').innerText = cart.reduce((s, i) => s + i.qty, 0);
            
            if (cart.length === 0) {
                container.innerHTML = '<div class="text-center py-12 text-gray-400"><p class="text-sm">Cart is empty</p></div>';
                calculateTotals();
                return;
            }

            container.innerHTML = cart.map(i => `
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">${i.name}</p>
                        <p class="text-xs text-gray-400">Rs. ${i.price.toLocaleString()} each</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button onclick="updateQty(${i.id}, -1)" class="w-7 h-7 rounded-md bg-white border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-sm font-bold">-</button>
                        <span class="w-8 text-center text-sm font-semibold text-gray-900">${i.qty}</span>
                        <button onclick="updateQty(${i.id}, 1)" class="w-7 h-7 rounded-md bg-white border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-sm font-bold">+</button>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 w-20 text-right">Rs. ${(i.price * i.qty).toLocaleString()}</p>
                    <button onclick="removeFromCart(${i.id})" class="p-1 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            `).join('');
            calculateTotals();
        }

        function checkout() {
            if (cart.length === 0) return;
            const btn = document.getElementById('checkoutBtn');
            btn.disabled = true;
            btn.innerText = 'Processing...';

            const totals = calculateTotals();
            const payload = {
                patient_name: document.getElementById('posPatientName').value || 'Walk-in Customer',
                discount_amount: totals.discAmt,
                discount_percent: totals.discPerc,
                tax_percent: totals.taxPerc,
                items: cart.map(i => ({ medicine_id: i.id, qty: i.qty, price: i.price }))
            };

            fetch('{{ route("pos.checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.sale_id) {
                    window.location.href = `/pos/receipt/${data.sale_id}`;
                } else {
                    alert(data.message || 'Checkout failed. Please check stock.');
                    btn.disabled = false;
                    btn.innerText = 'Checkout';
                }
            })
            .catch(err => {
                alert('Network error occurred.');
                btn.disabled = false;
                btn.innerText = 'Checkout';
            });
        }
    </script>
</x-app-layout>