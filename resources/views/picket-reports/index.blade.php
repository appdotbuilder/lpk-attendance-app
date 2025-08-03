@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <span class="mr-3">📋</span>
                Picket Reports
            </h1>
            <p class="text-gray-600 mt-1">Daily activity and progress reports</p>
        </div>
        
        @if(auth()->user()->isCpmi())
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('picket-reports.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors">
                    <span class="mr-2">📋</span>
                    Submit Report
                </a>
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('picket-reports.index') }}" class="flex flex-col sm:flex-row sm:items-end gap-4">
            <div class="flex-1">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                    📅 Date
                </label>
                <input type="date" name="date" id="date" 
                       value="{{ $filters['date'] ?? '' }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>
            
            <div class="flex-1">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    📊 Status
                </label>
                <select name="status" id="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="">All Status</option>
                    <option value="submitted" {{ ($filters['status'] ?? '') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="reviewed" {{ ($filters['status'] ?? '') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="approved" {{ ($filters['status'] ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" 
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors">
                    🔍 Filter
                </button>
                <a href="{{ route('picket-reports.index') }}" 
                   class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Reports List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if($reports->count() > 0)
            <!-- Desktop View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reporter
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Photos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Submitted
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ substr($report->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $report->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $report->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $report->date->format('M j, Y') }}
                                    <div class="text-xs text-gray-500">{{ $report->date->format('l') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $report->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($report->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                           ($report->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $report->trainingClass->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ count($report->photos ?? []) }} photos
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $report->created_at->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('picket-reports.show', $report) }}" 
                                       class="text-purple-600 hover:text-purple-900">View</a>
                                    @if($report->user_id === auth()->id() && $report->status === 'submitted')
                                        <a href="{{ route('picket-reports.edit', $report) }}" 
                                           class="text-blue-600 hover:text-blue-900">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="sm:hidden">
                @foreach($reports as $report)
                    <div class="p-4 border-b border-gray-200 last:border-b-0">
                        <div class="flex items-start space-x-3">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-medium">
                                    {{ substr($report->user->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $report->user->name }}
                                    </p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $report->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($report->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                           ($report->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">{{ $report->date->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ count($report->photos ?? []) }} photos • {{ $report->created_at->format('H:i') }}
                                </p>
                                <div class="mt-2 flex items-center justify-between">
                                    <p class="text-sm text-gray-600 truncate max-w-48">
                                        {{ Str::limit($report->report, 50) }}
                                    </p>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('picket-reports.show', $report) }}" 
                                           class="text-purple-600 hover:text-purple-900 font-medium text-xs">
                                            View →
                                        </a>
                                        @if($report->user_id === auth()->id() && $report->status === 'submitted')
                                            <a href="{{ route('picket-reports.edit', $report) }}" 
                                               class="text-blue-600 hover:text-blue-900 font-medium text-xs">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($reports->hasPages())
                <div class="bg-gray-50 px-6 py-3">
                    {{ $reports->appends($filters)->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <span class="text-4xl">📋</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Reports Found</h3>
                <p class="text-gray-500 mb-6">No picket reports found for the selected criteria.</p>
                
                @if(auth()->user()->isCpmi())
                    <a href="{{ route('picket-reports.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors">
                        <span class="mr-2">📋</span>
                        Submit Your First Report
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection