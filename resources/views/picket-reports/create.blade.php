@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-8 text-white">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <span class="text-3xl">📋</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Daily Picket Report</h1>
                    <p class="text-purple-100">{{ $currentClass->name ?? 'Your Class' }}</p>
                    <p class="text-purple-100 text-sm">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Report Status Check -->
        @if($todayReport)
            <div class="p-6">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">✅</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-green-800">Report Already Submitted</h3>
                            <p class="text-green-600 text-sm">
                                Status: <span class="capitalize font-medium">{{ $todayReport->status }}</span>
                                • Submitted: {{ $todayReport->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <a href="{{ route('picket-reports.show', $todayReport) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition-colors text-center block">
                        👁️ View Report
                    </a>
                    
                    @if($todayReport->status === 'submitted')
                        <a href="{{ route('picket-reports.edit', $todayReport) }}" 
                           class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-3 px-4 rounded-lg font-semibold transition-colors text-center block">
                            ✏️ Edit Report
                        </a>
                    @endif
                </div>
            </div>
        @else
            <!-- Report Form -->
            <form action="{{ route('picket-reports.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                
                <!-- Date and Time Display -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-lg">📅</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Report Date</p>
                                <p class="text-gray-600 text-sm">{{ now()->format('l, F j, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Current Time</p>
                            <p class="font-medium text-gray-900" id="currentTime">{{ now()->format('H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Main Report -->
                <div>
                    <label for="report" class="block text-sm font-medium text-gray-700 mb-2">
                        📝 Daily Report <span class="text-red-500">*</span>
                    </label>
                    <textarea name="report" id="report" rows="6" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                              placeholder="Describe today's activities, what you learned, what you accomplished..."
                              required></textarea>
                    <p class="text-sm text-gray-500 mt-1">Minimum 50 characters. Be detailed about your daily activities.</p>
                    @error('report')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Issues (Optional) -->
                <div>
                    <label for="issues" class="block text-sm font-medium text-gray-700 mb-2">
                        ⚠️ Issues or Challenges (Optional)
                    </label>
                    <textarea name="issues" id="issues" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                              placeholder="Any problems, difficulties, or challenges you encountered today..."></textarea>
                    @error('issues')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Suggestions (Optional) -->
                <div>
                    <label for="suggestions" class="block text-sm font-medium text-gray-700 mb-2">
                        💡 Suggestions or Improvements (Optional)
                    </label>
                    <textarea name="suggestions" id="suggestions" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                              placeholder="Any suggestions for improving the training program or facilities..."></textarea>
                    @error('suggestions')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📸 Photos (Optional)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center">
                        <div id="photoPreview" class="hidden mb-4 grid grid-cols-2 gap-4">
                            <!-- Preview images will be inserted here -->
                        </div>
                        <div id="photoUpload">
                            <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <span class="text-2xl">📸</span>
                            </div>
                            <p class="text-gray-600 mb-2">Add photos to support your report</p>
                            <input type="file" name="photos[]" id="photos" accept="image/*" multiple 
                                   class="hidden" onchange="previewPhotos(this)">
                            <button type="button" onclick="document.getElementById('photos').click()"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                📸 Add Photos
                            </button>
                            <p class="text-sm text-gray-500 mt-2">You can select multiple photos</p>
                        </div>
                    </div>
                    @error('photos')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Character Counter -->
                <div class="text-sm text-gray-500">
                    Report length: <span id="reportLength">0</span> characters (minimum 50)
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white py-4 px-4 rounded-xl font-semibold text-lg transition-colors">
                    📋 Submit Daily Report
                </button>
            </form>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Update current time every minute
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour12: false,
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('currentTime').textContent = timeString;
    }
    setInterval(updateTime, 60000);

    // Character counter for report
    const reportTextarea = document.getElementById('report');
    const reportLength = document.getElementById('reportLength');
    const submitBtn = document.getElementById('submitBtn');

    if (reportTextarea) {
        reportTextarea.addEventListener('input', function() {
            const length = this.value.length;
            reportLength.textContent = length;
            
            if (length < 50) {
                reportLength.style.color = '#ef4444'; // red
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                reportLength.style.color = '#10b981'; // green
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }

    // Photo preview
    function previewPhotos(input) {
        const previewContainer = document.getElementById('photoPreview');
        const uploadSection = document.getElementById('photoUpload');
        
        if (input.files && input.files.length > 0) {
            previewContainer.innerHTML = '';
            previewContainer.classList.remove('hidden');
            
            Array.from(input.files).forEach(function(file, index) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'relative';
                        imageDiv.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                            <div class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs cursor-pointer"
                                 onclick="removePhoto(${index})">×</div>
                        `;
                        previewContainer.appendChild(imageDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            uploadSection.innerHTML = `
                <button type="button" onclick="document.getElementById('photos').click()"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    📸 Add More Photos
                </button>
                <p class="text-sm text-gray-500 mt-2">${input.files.length} photo(s) selected</p>
            `;
        }
    }

    function removePhoto(index) {
        const input = document.getElementById('photos');
        const dt = new DataTransfer();
        
        Array.from(input.files).forEach(function(file, i) {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        input.files = dt.files;
        previewPhotos(input);
    }

    // Auto-save draft (localStorage)
    function saveDraft() {
        const report = document.getElementById('report').value;
        const issues = document.getElementById('issues').value;
        const suggestions = document.getElementById('suggestions').value;
        
        localStorage.setItem('picketReportDraft', JSON.stringify({
            report: report,
            issues: issues,
            suggestions: suggestions,
            date: '{{ now()->format("Y-m-d") }}'
        }));
    }

    function loadDraft() {
        const draft = localStorage.getItem('picketReportDraft');
        if (draft) {
            const data = JSON.parse(draft);
            if (data.date === '{{ now()->format("Y-m-d") }}') {
                document.getElementById('report').value = data.report || '';
                document.getElementById('issues').value = data.issues || '';
                document.getElementById('suggestions').value = data.suggestions || '';
                
                // Trigger character counter
                const event = new Event('input');
                document.getElementById('report').dispatchEvent(event);
                
                // Show notification
                if (data.report || data.issues || data.suggestions) {
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    notification.textContent = '💾 Draft restored from previous session';
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            }
        }
    }

    // Save draft on input
    document.addEventListener('DOMContentLoaded', function() {
        loadDraft();
        
        ['report', 'issues', 'suggestions'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', saveDraft);
            }
        });
    });

    // Clear draft on successful submission
    document.querySelector('form').addEventListener('submit', function() {
        localStorage.removeItem('picketReportDraft');
    });
</script>
@endpush
@endsection