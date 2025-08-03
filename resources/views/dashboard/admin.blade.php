@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <span class="text-2xl">👨‍💼</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                    <p class="text-blue-100">Welcome back, {{ auth()->user()->name }}</p>
                    <p class="text-blue-100 text-sm">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">👨‍🎓</span>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_cpmi'] }}</p>
                    <p class="text-sm text-gray-600">Total CPMI</p>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-sm text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    {{ $stats['active_cpmi'] }} active
                </span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">👨‍🏫</span>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_instructors'] }}</p>
                    <p class="text-sm text-gray-600">Instructors</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🎓</span>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_classes'] }}</p>
                    <p class="text-sm text-gray-600">Total Classes</p>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-sm text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                    {{ $stats['active_classes'] }} active
                </span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingReports->count() }}</p>
                    <p class="text-sm text-gray-600">Pending Reports</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">👥</div>
            <div class="font-semibold text-sm">Manage Users</div>
        </a>
        <a href="#" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">🎓</div>
            <div class="font-semibold text-sm">Classes</div>
        </a>
        <a href="{{ route('attendance.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">✅</div>
            <div class="font-semibold text-sm">Attendance</div>
        </a>
        <a href="{{ route('picket-reports.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">📋</div>
            <div class="font-semibold text-sm">Reports</div>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Recent Attendance -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <span class="mr-2">✅</span>
                Recent Attendance
            </h3>
            
            @if($recentAttendance->count() > 0)
                <div class="space-y-3">
                    @foreach($recentAttendance->take(5) as $attendance)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">
                                        {{ substr($attendance->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $attendance->user->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $attendance->date->format('M j, Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 
                                       ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('attendance.index') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View all attendance →
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-2">
                        <span class="text-2xl">✅</span>
                    </div>
                    <p class="text-gray-500 text-sm">No recent attendance records</p>
                </div>
            @endif
        </div>

        <!-- Pending Reports -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <span class="mr-2">📋</span>
                Pending Reports
            </h3>
            
            @if($pendingReports->count() > 0)
                <div class="space-y-3">
                    @foreach($pendingReports as $report)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-yellow-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">
                                        {{ substr($report->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $report->user->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $report->date->format('M j, Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($report->status) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $report->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('picket-reports.index', ['status' => 'submitted']) }}" 
                       class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                        Review all reports →
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-2">
                        <span class="text-2xl">📋</span>
                    </div>
                    <p class="text-gray-500 text-sm">No pending reports</p>
                </div>
            @endif
        </div>
    </div>

    <!-- System Status -->
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold mb-4 flex items-center">
            <span class="mr-2">⚡</span>
            System Status
        </h3>
        
        <div class="grid md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-xl">
                <div class="w-8 h-8 mx-auto bg-green-500 rounded-full flex items-center justify-center mb-2">
                    <span class="text-white text-sm">✓</span>
                </div>
                <p class="font-medium text-green-800">Database</p>
                <p class="text-sm text-green-600">Online</p>
            </div>
            
            <div class="text-center p-4 bg-green-50 rounded-xl">
                <div class="w-8 h-8 mx-auto bg-green-500 rounded-full flex items-center justify-center mb-2">
                    <span class="text-white text-sm">✓</span>
                </div>
                <p class="font-medium text-green-800">Storage</p>
                <p class="text-sm text-green-600">Available</p>
            </div>
            
            <div class="text-center p-4 bg-green-50 rounded-xl">
                <div class="w-8 h-8 mx-auto bg-green-500 rounded-full flex items-center justify-center mb-2">
                    <span class="text-white text-sm">✓</span>
                </div>
                <p class="font-medium text-green-800">Chat System</p>
                <p class="text-sm text-green-600">Active</p>
            </div>
        </div>
    </div>
</div>
@endsection