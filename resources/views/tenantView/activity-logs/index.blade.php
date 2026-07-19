<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Activity Logs — SwiftPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] } } } }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Top Bar -->
    <nav class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-bolt text-white text-xs"></i>
            </div>
            <span class="font-extrabold text-gray-900">SwiftPOS</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('tenant.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                <i class="fa-solid fa-arrow-left mr-1"></i>Dashboard
            </a>
            <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('tenant.auth.logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Logout</button>
            </form>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Activity Logs</h1>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-3">
                <select name="action" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                    <option value="">All Actions</option>
                    @foreach($actions as $act)
                    <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>

                <select name="user_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                    <option value="">All Users</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="date" value="{{ request('date') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    <i class="fa-solid fa-filter mr-1"></i>Filter
                </button>
                
                <a href="{{ route('tenant.activity-logs') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                    Clear
                </a>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">When</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Action</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Description</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('M d, Y') }}
                            <span class="block text-xs text-gray-400">{{ $log->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="text-sm font-medium text-gray-900">{{ $log->user?->name ?? 'System' }}</span>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $colors = [
                                    'login' => 'bg-emerald-100 text-emerald-700',
                                    'logout' => 'bg-gray-100 text-gray-600',
                                    'patient.create' => 'bg-blue-100 text-blue-700',
                                    'token.create' => 'bg-amber-100 text-amber-700',
                                    'token.complete' => 'bg-emerald-100 text-emerald-700',
                                    'prescription.create' => 'bg-purple-100 text-purple-700',
                                    'invoice.generate' => 'bg-orange-100 text-orange-700',
                                    'invoice.pay' => 'bg-green-100 text-green-700',
                                    'sale.complete' => 'bg-cyan-100 text-cyan-700',
                                    'doctor.create' => 'bg-indigo-100 text-indigo-700',
                                ];
                                $color = $colors[$log->action] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-medium {{ $color }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $log->description ?? '—' }}</td>
                        <td class="px-6 py-3 text-xs text-gray-400 font-mono">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-clipboard-list text-3xl mb-2 block"></i>
                            No activity logs yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="mt-4 flex justify-center gap-1">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</body>
</html>