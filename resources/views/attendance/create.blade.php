@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-8 text-white">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <span class="text-3xl">✅</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Mark Attendance</h1>
                    <p class="text-green-100">{{ $currentClass->name ?? 'Your Class' }}</p>
                    <p class="text-green-100 text-sm">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Attendance Status Check -->
        @if($todayAttendance && $todayAttendance->check_in_time)
            <div class="p-6">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">✅</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-green-800">Already Checked In</h3>
                            <p class="text-green-600 text-sm">
                                Check-in: {{ $todayAttendance->check_in_time }}
                                @if($todayAttendance->status === 'late')
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Late
                                    </span>
                                @else
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        On Time
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Check-out option -->
                @if(!$todayAttendance->check_out_time)
                    <form action="{{ route('attendance.update', $todayAttendance) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea name="notes" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Any additional notes for today...">{{ $todayAttendance->notes }}</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition-colors">
                            📤 Check Out
                        </button>
                    </form>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-lg">📤</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-blue-800">Already Checked Out</h3>
                                <p class="text-blue-600 text-sm">Check-out: {{ $todayAttendance->check_out_time }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Check-in Form -->
            <form id="attendanceForm" action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                
                <!-- Current Time Display -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-lg">🕐</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Current Time</p>
                                <p class="text-gray-600 text-sm" id="currentTime">{{ now()->format('H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Expected: 08:00</p>
                            <p class="text-xs text-gray-400">Late after: 08:30</p>
                        </div>
                    </div>
                </div>

                <!-- Location Detection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📍 Location Verification
                    </label>
                    <div id="locationStatus" class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">📍</span>
                            </div>
                            <div>
                                <p class="font-medium text-yellow-800">Detecting Location...</p>
                                <p class="text-yellow-600 text-sm">Please allow location access</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="location_address" id="locationAddress">
                </div>

                <!-- Photo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📸 Attendance Photo
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center">
                        <div id="photoPreview" class="hidden mb-4">
                            <img id="previewImage" class="mx-auto h-32 w-32 object-cover rounded-lg">
                        </div>
                        <div id="photoUpload">
                            <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <span class="text-2xl">📸</span>
                            </div>
                            <p class="text-gray-600 mb-2">Take a selfie for attendance verification</p>
                            <input type="file" name="photo" id="photo" accept="image/*" capture="user" 
                                   class="hidden" onchange="previewPhoto(this)">
                            <button type="button" onclick="document.getElementById('photo').click()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                📸 Take Photo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        📝 Notes (Optional)
                    </label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="Any additional notes..."></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" disabled
                        class="w-full bg-gray-400 text-white py-4 px-4 rounded-xl font-semibold text-lg transition-all disabled:cursor-not-allowed">
                    <span id="submitText">📍 Detecting Location...</span>
                </button>
            </form>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Update current time every second
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour12: false });
        document.getElementById('currentTime').textContent = timeString;
    }
    setInterval(updateTime, 1000);

    // Geolocation
    let locationDetected = false;

    function detectLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    
                    // Update UI
                    const locationStatus = document.getElementById('locationStatus');
                    locationStatus.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">✅</span>
                            </div>
                            <div>
                                <p class="font-medium text-green-800">Location Detected</p>
                                <p class="text-green-600 text-sm">Coordinates: ${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                            </div>
                        </div>
                    `;
                    locationStatus.className = 'bg-green-50 border border-green-200 rounded-xl p-4';
                    
                    locationDetected = true;
                    updateSubmitButton();
                    
                    // Reverse geocoding (optional - you can add a service like Google Maps API)
                    document.getElementById('locationAddress').value = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                },
                function(error) {
                    const locationStatus = document.getElementById('locationStatus');
                    locationStatus.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">❌</span>
                            </div>
                            <div>
                                <p class="font-medium text-red-800">Location Access Denied</p>
                                <p class="text-red-600 text-sm">Please enable location services and refresh</p>
                            </div>
                        </div>
                    `;
                    locationStatus.className = 'bg-red-50 border border-red-200 rounded-xl p-4';
                }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }

    // Photo preview
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('photoPreview').classList.remove('hidden');
                document.getElementById('photoUpload').innerHTML = `
                    <button type="button" onclick="document.getElementById('photo').click()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        📸 Retake Photo
                    </button>
                `;
                updateSubmitButton();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Update submit button state
    function updateSubmitButton() {
        const photo = document.getElementById('photo').files[0];
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        
        if (locationDetected && photo) {
            submitBtn.disabled = false;
            submitBtn.className = 'w-full bg-green-600 hover:bg-green-700 text-white py-4 px-4 rounded-xl font-semibold text-lg transition-all';
            submitText.textContent = '✅ Mark Attendance';
        } else if (locationDetected) {
            submitBtn.className = 'w-full bg-yellow-600 text-white py-4 px-4 rounded-xl font-semibold text-lg';
            submitText.textContent = '📸 Photo Required';
        }
    }

    // Initialize location detection on page load
    document.addEventListener('DOMContentLoaded', function() {
        detectLocation();
    });
</script>
@endpush
@endsection