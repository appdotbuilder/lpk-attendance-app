@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <span class="mr-3">✅</span>
                Attendance Records
            </h1>
            <p class="text-gray-600 mt-1">Track and manage attendance records</p>
        </div>
        
        @if(auth()->user()->isCpmi())
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('attendance.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors">
                    <span class="mr-2">✅</span>
                    Mark Attendance
                </a>
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-col sm:flex-row sm:items-end gap-4">
            <div class="flex-1">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                    📅 Date
                </label>
                <input type="date" name="date" id="date" 
                       value="{{ $filters['date'] ?? '' }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex-1">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    📊 Status
                </label>
                <select name="status" id="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="present" {{ ($filters['status'] ?? '') === 'present' ? 'selected' : '' }}>Present</option>
                    <option value="late" {{ ($filters['status'] ?? '') === 'late' ? 'selected' : '' }}>Late</option>
                    <option value="absent" {{ ($filters['status'] ?? '') === 'absent' ? 'selected' : '' }}>Absent</option>
                </select>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    🔍 Filter
                </button>
                <a href="{{ route('attendance.index') }}" 
                   class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Attendance Records -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if($attendance->count() > 0)
            <!-- Desktop View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Check In
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Check Out
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendance as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ substr($record->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $record->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $record->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $record->date->format('M j, Y') }}
                                    <div class="text-xs text-gray-500">{{ $record->date->format('l') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $record->check_in_time ? $record->check_in_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $record->check_out_time ? $record->check_out_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $record->status === 'present' ? 'bg-green-100 text-green-800' : 
                                           ($record->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $record->trainingClass->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('attendance.show', $record) }}" 
                                       class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="sm:hidden">
                @foreach($attendance as $record)
                    <div class="p-4 border-b border-gray-200 last:border-b-0">
                        <div class="flex items-start space-x-3">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-medium">
                                    {{ substr($record->user->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $record->user->name }}
                                    </p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $record->status === 'present' ? 'bg-green-100 text-green-800' : 
                                           ($record->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">{{ $record->date->format('M j, Y') }}</p>
                                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                    <span>
                                        In: {{ $record->check_in_time ? $record->check_in_time->format('H:i') : '-' }}
                                        | Out: {{ $record->check_out_time ? $record->check_out_time->format('H:i') : '-' }}
                                    </span>
                                    <a href="{{ route('attendance.show', $record) }}" 
                                       class="text-blue-600 hover:text-blue-900 font-medium">
                                        View →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($attendance->hasPages())
                <div class="bg-gray-50 px-6 py-3">
                    {{ $attendance->appends($filters)->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <span class="text-4xl">📅</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Attendance Records</h3>
                <p class="text-gray-500 mb-6">No attendance records found for the selected criteria.</p>
                
                @if(auth()->user()->isCpmi())
                    <a href="{{ route('attendance.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors">
                        <span class="mr-2">✅</span>
                        Mark Your First Attendance
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection