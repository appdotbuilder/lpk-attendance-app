@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-white">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <span class="text-3xl">✏️</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Edit Picket Report</h1>
                    <p class="text-blue-100">{{ $report->trainingClass->name ?? 'Your Class' }}</p>
                    <p class="text-blue-100 text-sm">{{ $report->date->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('picket-reports.update', $report) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Status Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm">⚠️</span>
                    </div>
                    <div>
                        <p class="font-medium text-yellow-800">Editing Submitted Report</p>
                        <p class="text-yellow-600 text-sm">You can only edit reports that haven't been reviewed yet.</p>
                    </div>
                </div>
            </div>

            <!-- Main Report -->
            <div>
                <label for="report" class="block text-sm font-medium text-gray-700 mb-2">
                    📝 Daily Report <span class="text-red-500">*</span>
                </label>
                <textarea name="report" id="report" rows="6" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Describe today's activities, what you learned, what you accomplished..."
                          required>{{ old('report', $report->report) }}</textarea>
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Any problems, difficulties, or challenges you encountered today...">{{ old('issues', $report->issues) }}</textarea>
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Any suggestions for improving the training program or facilities...">{{ old('suggestions', $report->suggestions) }}</textarea>
                @error('suggestions')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Existing Photos -->
            @if($report->photos && count($report->photos) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📸 Current Photos
                    </label>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        @foreach($report->photos as $index => $photo)
                            <div class="relative bg-gray-50 rounded-xl p-2">
                                <img src="{{ Storage::url($photo) }}" 
                                     alt="Report Photo" 
                                     class="w-full h-24 object-cover rounded-lg">
                                <button type="button" 
                                        onclick="removeExistingPhoto({{ $index }})"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add New Photos -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    📸 Add New Photos (Optional)
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center">
                    <div id="photoPreview" class="hidden mb-4 grid grid-cols-2 gap-4">
                        <!-- Preview images will be inserted here -->
                    </div>
                    <div id="photoUpload">
                        <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <span class="text-2xl">📸</span>
                        </div>
                        <p class="text-gray-600 mb-2">Add new photos to your report</p>
                        <input type="file" name="photos[]" id="photos" accept="image/*" multiple 
                               class="hidden" onchange="previewNewPhotos(this)">
                        <button type="button" onclick="document.getElementById('photos').click()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
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
                Report length: <span id="reportLength">{{ strlen($report->report) }}</span> characters (minimum 50)
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('picket-reports.show', $report) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                    ← Cancel
                </a>
                
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                    💾 Update Report
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
        
        // Trigger on page load
        const event = new Event('input');
        reportTextarea.dispatchEvent(event);
    }

    // Photo preview for new photos
    function previewNewPhotos(input) {
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
                                 onclick="removeNewPhoto(${index})">×</div>
                        `;
                        previewContainer.appendChild(imageDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            uploadSection.innerHTML = `
                <button type="button" onclick="document.getElementById('photos').click()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    📸 Add More Photos
                </button>
                <p class="text-sm text-gray-500 mt-2">${input.files.length} new photo(s) selected</p>
            `;
        }
    }

    function removeNewPhoto(index) {
        const input = document.getElementById('photos');
        const dt = new DataTransfer();
        
        Array.from(input.files).forEach(function(file, i) {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        input.files = dt.files;
        previewNewPhotos(input);
    }

    function removeExistingPhoto(index) {
        // In a real implementation, you would handle removal of existing photos
        // This might involve adding a hidden input to track removed photos
        alert('Photo removal functionality would be implemented here');
    }
</script>
@endpush
@endsection