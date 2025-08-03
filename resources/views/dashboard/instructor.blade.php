@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-6 text-white">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <span class="text-2xl">👨‍🏫</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Instructor Dashboard</h1>
                    <p class="text-purple-100">Welcome back, {{ auth()->user()->name }}</p>
                    <p class="text-purple-100 text-sm">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('attendance.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">✅</div>
            <div class="font-semibold text-sm">View Attendance</div>
        </a>
        <a href="{{ route('picket-reports.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">📋</div>
            <div class="font-semibold text-sm">Review Reports</div>
        </a>
        <a href="{{ route('chat.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">💬</div>
            <div class="font-semibold text-sm">Chat</div>
        </a>
        <a href="#" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">📊</div>
            <div class="font-semibold text-sm">Analytics</div>
        </a>
    </div>

    <!-- My Classes -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <span class="mr-2">🎓</span>
            My Classes
        </h2>
        
        @if($classes->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($classes as $class)
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 p-4 rounded-xl border border-blue-200">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-gray-900">{{ $class->name }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $class->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($class->status) }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <span class="mr-2">👥</span>
                                <span>{{ $class->enrollments->count() }} students</span>
                            </div>
                            <div class="flex items-center">
                                <span class="mr-2">📅</span>
                                <span>{{ $class->start_date ? $class->start_date->format('M j') : 'TBD' }} - {{ $class->end_date ? $class->end_date->format('M j, Y') : 'TBD' }}</span>
                            </div>
                            @if($class->description)
                                <p class="text-xs text-gray-500 mt-2">{{ Str::limit($class->description, 80) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <span class="text-3xl">🎓</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Classes Assigned</h3>
                <p class="text-gray-500">You haven't been assigned to any classes yet.</p>
            </div>
        @endif
    </div>

    <!-- Today's Schedule -->
    @if($todaySchedules->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <span class="mr-2">🕐</span>
                Today's Schedule
            </h3>
            <div class="space-y-3">
                @foreach($todaySchedules as $schedule)
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl border border-purple-200">
                        <div>
                            <p class="font-semibold text-purple-900">{{ $schedule->subject }}</p>
                            <p class="text-purple-600 text-sm">{{ $schedule->trainingClass->name }}</p>
                            <p class="text-purple-600 text-sm">
                                {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $schedule->type }}
                            </span>
                            <p class="text-xs text-purple-600 mt-1">
                                {{ $schedule->trainingClass->enrollments->count() }} students
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Reports -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold mb-4 flex items-center">
            <span class="mr-2">📋</span>
            Recent Student Reports
        </h3>
        
        @if($recentReports->count() > 0)
            <div class="space-y-3">
                @foreach($recentReports as $report)
                    <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-sm font-medium">
                                {{ substr($report->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900">{{ $report->user->name }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $report->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($report->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                       ($report->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $report->date->format('M j, Y') }} • {{ $report->trainingClass->name }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($report->report, 100) }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    @if(count($report->photos ?? []) > 0)
                                        <span>📸 {{ count($report->photos) }} photos</span>
                                    @endif
                                    <span>⏰ {{ $report->created_at->format('H:i') }}</span>
                                </div>
                                <a href="{{ route('picket-reports.show', $report) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Review →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('picket-reports.index') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View all reports →
                </a>
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <span class="text-3xl">📋</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Recent Reports</h3>
                <p class="text-gray-500">No student reports available for review.</p>
            </div>
        @endif
    </div>
</div>
@endsection