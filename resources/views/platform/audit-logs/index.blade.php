@extends('platform.layouts.app')

@section('header', 'Audit Logs')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Activity Logs</h2>
    <p class="text-sm text-gray-500 mt-1">Track all actions performed by Platform Admins.</p>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3.5">Timestamp</th>
                <th class="px-6 py-3.5">Admin</th>
                <th class="px-6 py-3.5">Action</th>
                <th class="px-6 py-3.5">Description</th>
                <th class="px-6 py-3.5">IP Address</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $log->created_at->format('M d, h:i A') }}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $log->admin->name ?? 'System' }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold 
                        @if($log->action === 'create') bg-emerald-50 text-emerald-700 
                        @elseif($log->action === 'update') bg-amber-50 text-amber-700 
                        @elseif($log->action === 'delete') bg-red-50 text-red-700 
                        @else bg-gray-100 text-gray-600 @endif">
                        {{ ucfirst($log->action) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $log->description }}</td>
                <td class="px-6 py-4 text-xs font-mono text-gray-400">{{ $log->ip_address }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No activity yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 flex justify-center">
    {{ $logs->links() }}
</div>
@endsection