@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <span class="text-2xl">👋</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-blue-100">{{ $currentClass->name ?? 'No Active Class' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('attendance.create') }}" 
           class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">✅</div>
            <div class="font-semibold text-sm">Mark Attendance</div>
        </a>
        <a href="{{ route('picket-reports.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">📋</div>
            <div class="font-semibold text-sm">Submit Report</div>
        </a>
        <a href="{{ route('chat.index') }}" 
           class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-xl text-center transition-colors">
            <div class="text-2xl mb-2">💬</div>
            <div class="font-semibold text-sm">Chat</div>
        </a>
    </div>

    <!-- Today's Status -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <span class="mr-2">📅</span>
            Today's Status - {{ now()->format('l, F j, Y') }}
        </h2>
        
        @if($todayAttendance)
            <div class="flex items-center space-x-4 p-4 bg-green-50 rounded-xl">
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                    <span class="text-white text-xl">✅</span>
                </div>
                <div>
                    <p class="font-semibold text-green-800">Attendance Marked</p>
                    <p class="text-green-600 text-sm">
                        Status: <span class="capitalize font-medium">{{ $todayAttendance->status }}</span>
                        @if($todayAttendance->check_in_time)
                            • Check-in: {{ $todayAttendance->check_in_time->format('H:i') }}
                        @endif
                    </p>
                </div>
            </div>
        @else
            <div class="flex items-center space-x-4 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center">
                    <span class="text-white text-xl">⏰</span>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-yellow-800">Attendance Not Marked</p>
                    <p class="text-yellow-600 text-sm">Don't forget to mark your attendance today!</p>
                </div>
                <a href="{{ route('attendance.create') }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Mark Now
                </a>
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
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                        <div>
                            <p class="font-semibold text-blue-900">{{ $schedule->subject }}</p>
                            <p class="text-blue-600 text-sm">
                                {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $schedule->type }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Attendance Statistics -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4 flex items-center">
            <span class="mr-2">📊</span>
            Attendance Statistics
        </h3>
        <div class="grid grid-cols-3 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-xl">
                <div class="text-2xl font-bold text-green-600">{{ $attendanceStats['present'] }}</div>
                <div class="text-sm text-green-800">Present</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-xl">
                <div class="text-2xl font-bold text-yellow-600">{{ $attendanceStats['late'] }}</div>
                <div class="text-sm text-yellow-800">Late</div>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-xl">
                <div class="text-2xl font-bold text-red-600">{{ $attendanceStats['absent'] }}</div>
                <div class="text-sm text-red-800">Absent</div>
            </div>
        </div>
        
        @php
            $total = array_sum($attendanceStats);
            $presentRate = $total > 0 ? round(($attendanceStats['present'] / $total) * 100, 1) : 0;
        @endphp
        
        <div class="mt-4 p-4 bg-gray-50 rounded-xl">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700">Attendance Rate</span>
                <span class="text-sm font-bold text-gray-900">{{ $presentRate }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ $presentRate }}%"></div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    @if($recentAttendance->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <span class="mr-2">📅</span>
                Recent Attendance
            </h3>
            <div class="space-y-3">
                @foreach($recentAttendance as $attendance)
                    <div class="flex items-center justify-between p-3 border border-gray-100 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-900">{{ $attendance->date->format('M j, Y') }}</p>
                            <p class="text-sm text-gray-500">
                                @if($attendance->check_in_time)
                                    Check-in: {{ $attendance->check_in_time->format('H:i') }}
                                @endif
                                @if($attendance->check_out_time)
                                    • Check-out: {{ $attendance->check_out_time->format('H:i') }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 
                                   ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection