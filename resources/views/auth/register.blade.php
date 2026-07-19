<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="sm:mx-auto w-full max-w-xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Register your ePOS today for <span class="text-teal-600">FREE</span></h2>
                <p class="mt-2 text-gray-500">Start your 14-day risk-free trial. No credit card required.</p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 border border-gray-100">
                
                <!-- Step 1: Business Type -->
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Step 1: What kind of business you own?</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="business_type" value="clinic" class="peer sr-only" checked>
                            <div class="p-4 border-2 rounded-xl border-gray-200 hover:border-teal-500 peer-checked:border-teal-600 peer-checked:bg-teal-50 transition-all">
                                <span class="text-2xl mb-2 block">🏥</span>
                                <span class="font-semibold text-gray-800 text-sm">Clinic / Hospital</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="business_type" value="pharmacy" class="peer sr-only">
                            <div class="p-4 border-2 rounded-xl border-gray-200 hover:border-teal-500 peer-checked:border-teal-600 peer-checked:bg-teal-50 transition-all">
                                <span class="text-2xl mb-2 block">💊</span>
                                <span class="font-semibold text-gray-800 text-sm">Pharmacy</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="business_type" value="mart" class="peer sr-only">
                            <div class="p-4 border-2 rounded-xl border-gray-200 hover:border-teal-500 peer-checked:border-teal-600 peer-checked:bg-teal-50 transition-all">
                                <span class="text-2xl mb-2 block">🛒</span>
                                <span class="font-semibold text-gray-800 text-sm">Super Mart</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="business_type" value="restaurant" class="peer sr-only">
                            <div class="p-4 border-2 rounded-xl border-gray-200 hover:border-teal-500 peer-checked:border-teal-600 peer-checked:bg-teal-50 transition-all">
                                <span class="text-2xl mb-2 block">🍽️</span>
                                <span class="font-semibold text-gray-800 text-sm">Restaurant / Cafe</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Step 2: Outlets -->
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Step 2: Number of outlets you maintain?</h3>
                    <div class="grid grid-cols-4 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="outlets" value="1" class="peer sr-only" checked>
                            <div class="border-2 rounded-xl p-3 text-center border-gray-200 hover:border-teal-500 peer-checked:border-teal-600 peer-checked:bg-teal-50 font-bold text-gray-800 text-sm transition-all">1</div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="outlets" value="2-5" class="peer sr-only">
                            <div class="border-2 rounded-xl p-3 text-center border-gray-200 hover:border-teal-500 peer-checked:border-teal-600 peer-checked:bg-teal-50 font-bold text-gray-800 text-sm transition-all">2-5</div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="outlets" value="6-10" class="peer sr-only">
                            <div class="border-2 rounded-xl p-3 text-center border-gray-200 hover:border-teal-500 peer-checked:border-teal-600 peer-checked:bg-teal-50 font-bold text-gray-800 text-sm transition-all">6-10</div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="outlets" value="10+" class="peer sr-only">
                            <div class="border-2 rounded-xl p-3 text-center border-gray-200 hover:border-teal-500 peer-checked:border-teal-600 peer-checked:bg-teal-50 font-bold text-gray-800 text-sm transition-all">10+</div>
                        </label>
                    </div>
                </div>

                <!-- Step 3: Form Details -->
                <div>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Step 3: Details</h3>
                    <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Name *</label>
                                <input type="text" name="name" required autofocus class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition" placeholder="John Doe">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Store / Clinic Name *</label>
                                <input type="text" name="company_name" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition" placeholder="Ahmed Medical Center">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">E - Mail Address *</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition" placeholder="john@example.com">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition" placeholder="Min 8 characters">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                                <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition" placeholder="Repeat password">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out flex items-center justify-center gap-2">
                            Start 14 Days Free Trial
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5-5m0 5h7a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V5a2 2 0 012-2h7a2 2 0 002 2v8a2 2 0 002 2h9a2 2 0 002-2v-5a2 2 0 00-2-2h-9a2 2 0 00-2 2z"></path></svg>
                        </button>

                        <p class="mt-4 text-xs text-center text-gray-500">By clicking "Start 14 Days Free Trial", you agree to our <a href="#" class="text-teal-600 underline">Terms of Service</a> and <a href="#" class="text-teal-600 underline">Privacy Policy</a>.</p>

                        <p class="mt-2 text-center text-sm font-medium text-gray-600">Already have an account? <a href="{{ route('login') }}" class="text-teal-600 font-bold hover:underline">Log in</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>