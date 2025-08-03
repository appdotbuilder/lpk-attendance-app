@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">✅</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Attendance Details</h1>
                        <p class="text-green-100">{{ $attendance->user->name }}</p>
                        <p class="text-green-100 text-sm">{{ $attendance->date->format('l, F j, Y') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $attendance->status === 'present' ? 'bg-white/20 text-white' : 
                           ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($attendance->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-6">
            <!-- Basic Information -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">📅 Basic Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium">{{ $attendance->date->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Day:</span>
                            <span class="font-medium">{{ $attendance->date->format('l') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 
                                   ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Class:</span>
                            <span class="font-medium">{{ $attendance->trainingClass->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">🕐 Time Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Check-in:</span>
                            <span class="font-medium">
                                {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i:s') : 'Not recorded' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Check-out:</span>
                            <span class="font-medium">
                                {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i:s') : 'Not recorded' }}
                            </span>
                        </div>
                        @if($attendance->check_in_time && $attendance->check_out_time)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium">
                                    {{ $attendance->check_in_time->diff($attendance->check_out_time)->format('%H:%I') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            @if($attendance->latitude || $attendance->longitude)
                <div>
                    <h3 class="text-lg font-semibold mb-4">📍 Location Information</h3>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            @if($attendance->latitude && $attendance->longitude)
                                <div>
                                    <span class="text-gray-600">GPS Coordinates:</span>
                                    <p class="font-medium">{{ $attendance->latitude }}, {{ $attendance->longitude }}</p>
                                </div>
                            @endif
                            @if($attendance->location_address)
                                <div>
                                    <span class="text-gray-600">Address:</span>
                                    <p class="font-medium">{{ $attendance->location_address }}</p>
                                </div>
                            @endif
                            <div>
                                <span class="text-gray-600">Location Valid:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $attendance->is_valid_location ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $attendance->is_valid_location ? 'Valid' : 'Invalid' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Photo -->
            @if($attendance->photo)
                <div>
                    <h3 class="text-lg font-semibold mb-4">📸 Attendance Photo</h3>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <img src="{{ Storage::url($attendance->photo) }}" 
                             alt="Attendance Photo" 
                             class="max-w-md h-64 object-cover rounded-lg shadow-sm">
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if($attendance->notes)
                <div>
                    <h3 class="text-lg font-semibold mb-4">📝 Notes</h3>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-700">{{ $attendance->notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <a href="{{ route('attendance.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                    ← Back to List
                </a>
                
                @if($attendance->user_id === auth()->id() && !$attendance->check_out_time)
                    <form action="{{ route('attendance.update', $attendance) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            📤 Check Out
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection