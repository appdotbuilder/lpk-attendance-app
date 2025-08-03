@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">📋</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Picket Report</h1>
                        <p class="text-purple-100">{{ $report->user->name }}</p>
                        <p class="text-purple-100 text-sm">{{ $report->date->format('l, F j, Y') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $report->status === 'approved' ? 'bg-green-100 text-green-800' : 
                           ($report->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                           ($report->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : 'bg-white/20 text-white')) }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-6">
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4">📅 Report Information</h3>
                <div class="grid md:grid-cols-3 gap-4 bg-gray-50 rounded-xl p-4">
                    <div>
                        <span class="text-gray-600">Date:</span>
                        <p class="font-medium">{{ $report->date->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Class:</span>
                        <p class="font-medium">{{ $report->trainingClass->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Submitted:</span>
                        <p class="font-medium">{{ $report->created_at->format('M j, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Report -->
            <div>
                <h3 class="text-lg font-semibold mb-4">📝 Daily Report</h3>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $report->report }}</p>
                </div>
            </div>

            <!-- Issues -->
            @if($report->issues)
                <div>
                    <h3 class="text-lg font-semibold mb-4">⚠️ Issues or Challenges</h3>
                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $report->issues }}</p>
                    </div>
                </div>
            @endif

            <!-- Suggestions -->
            @if($report->suggestions)
                <div>
                    <h3 class="text-lg font-semibold mb-4">💡 Suggestions</h3>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $report->suggestions }}</p>
                    </div>
                </div>
            @endif

            <!-- Photos -->
            @if($report->photos && count($report->photos) > 0)
                <div>
                    <h3 class="text-lg font-semibold mb-4">📸 Photos ({{ count($report->photos) }})</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($report->photos as $photo)
                            <div class="bg-gray-50 rounded-xl p-2">
                                <img src="{{ Storage::url($photo) }}" 
                                     alt="Report Photo" 
                                     class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="openPhotoModal('{{ Storage::url($photo) }}')">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Review Information -->
            @if($report->reviewer || $report->review_notes)
                <div>
                    <h3 class="text-lg font-semibold mb-4">👨‍🏫 Review Information</h3>
                    <div class="bg-gray-50 rounded-xl p-4">
                        @if($report->reviewer)
                            <div class="mb-3">
                                <span class="text-gray-600">Reviewed by:</span>
                                <p class="font-medium">{{ $report->reviewer->name }}</p>
                            </div>
                        @endif
                        @if($report->review_notes)
                            <div>
                                <span class="text-gray-600">Review Notes:</span>
                                <p class="text-gray-700 mt-1">{{ $report->review_notes }}</p>
                            </div>
                        @endif
                        @if($report->reviewed_at)
                            <div class="mt-3">
                                <span class="text-gray-600">Reviewed at:</span>
                                <p class="font-medium">{{ $report->reviewed_at->format('M j, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <a href="{{ route('picket-reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                    ← Back to Reports
                </a>
                
                <div class="flex space-x-2">
                    @if($report->user_id === auth()->id() && $report->status === 'submitted')
                        <a href="{{ route('picket-reports.edit', $report) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            ✏️ Edit Report
                        </a>
                    @endif
                    
                    @if(auth()->user()->isInstructor() || auth()->user()->isAdmin())
                        @if($report->status === 'submitted')
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                                ✅ Approve
                            </button>
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                                ❌ Reject
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="Report Photo" class="max-w-full max-h-full object-contain rounded-lg">
        <button onclick="closePhotoModal()" 
                class="absolute top-4 right-4 bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75 transition-opacity">
            ×
        </button>
    </div>
</div>

@push('scripts')
<script>
    function openPhotoModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('photoModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside the image
    document.getElementById('photoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePhotoModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePhotoModal();
        }
    });
</script>
@endpush
@endsection