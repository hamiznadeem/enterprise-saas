<x-app-layout>
    <x-slot name="header">
        Activity Logs
    </x-slot>

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Activity Logs</h1>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Action</label>
                    <select name="action" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="">All</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">User</label>
                    <select name="user_id" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="">All</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="border rounded-lg px-3 py-2 text-sm">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Filter</button>
                <a href="{{ route('tenant.activity-logs') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 text-sm">Clear</a>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $log->created_at->format('M j, g:i A') }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded-full font-mono">{{ $log->action }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $log->description ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 font-mono">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                    @endforeach
                    @if($logs->isEmpty())
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No logs found.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->withQueryString()->links() }}
        </div>
</x-app-layout>