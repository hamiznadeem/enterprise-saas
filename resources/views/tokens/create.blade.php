<x-app-layout>
    <x-slot name="header">Create New Token</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Generate Token</h2>
                    <p class="text-sm text-gray-500">Register patient and add to doctor's queue</p>
                </div>
            </div>

            <form method="POST" action="{{ route('tokens.store') }}" class="space-y-5">
                @csrf
                @error('generic')
                <div class="p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">{{ $message }}</div>
                @enderror

                <!-- Patient Section -->
                <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        Patient Information
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Patient Name *</label>
                            <input type="text" name="patient_name" value="{{ old('patient_name') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Full name">
                            @error('patient_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                            <input type="text" name="patient_phone" value="{{ old('patient_phone') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="03XX-XXXXXXX">
                            @error('patient_phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age *</label>
                            <input type="number" name="patient_age" value="{{ old('patient_age') }}" required min="0" max="150" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="25">
                            @error('patient_age')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                            <select name="patient_gender" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                                <option value="">Select</option>
                                <option value="male" {{ old('patient_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('patient_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('patient_gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('patient_gender')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Token Section -->
                <div class="p-4 rounded-lg bg-indigo-50 border border-indigo-100">
                    <h3 class="text-sm font-semibold text-indigo-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                        Token Configuration
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-indigo-800 mb-1">Doctor *</label>
                            <select name="doctor_id" required class="w-full rounded-lg border border-indigo-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }} — Rs. {{ number_format($doctor->consultation_fee) }}</option>
                                @endforeach
                            </select>
                            @error('doctor_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-indigo-800 mb-1">Service</label>
                            <select name="service_id" class="w-full rounded-lg border border-indigo-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                                <option value="">General Consultation</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }} — Rs. {{ number_format($service->fee) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                        Generate Token
                    </button>
                    <a href="{{ route('tokens.index') }}" class="px-6 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>