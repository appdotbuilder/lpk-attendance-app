@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center">
        <!-- Icon -->
        <div class="mx-auto w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-8">
            <span class="text-4xl">🎓</span>
        </div>
        
        <!-- Heading -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            Welcome to Training Center, {{ auth()->user()->name }}!
        </h1>
        
        <!-- Message -->
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            You're not currently enrolled in any training class. Please contact your administrator to get enrolled in a training program.
        </p>
        
        <!-- Status Card -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-8 max-w-md mx-auto">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center">
                    <span class="text-white text-xl">⏳</span>
                </div>
                <div>
                    <h3 class="font-semibold text-yellow-800">Enrollment Status</h3>
                    <p class="text-yellow-600 text-sm">Awaiting Class Assignment</p>
                </div>
            </div>
            <p class="text-yellow-700 text-sm">
                Your account is active, but you need to be enrolled in a training class to access attendance and reporting features.
            </p>
        </div>
        
        <!-- Contact Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-md mx-auto">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center justify-center">
                <span class="mr-2">📞</span>
                Need Help?
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <p class="flex items-center justify-center">
                    <span class="mr-2">📧</span>
                    Contact: admin@trainingcenter.com
                </p>
                <p class="flex items-center justify-center">
                    <span class="mr-2">🕐</span>
                    Office Hours: 8:00 AM - 5:00 PM
                </p>
            </div>
        </div>
        
        <!-- User Profile Info -->
        <div class="mt-8 bg-gray-50 rounded-2xl p-6 max-w-md mx-auto">
            <h3 class="font-semibold text-gray-900 mb-4">Your Profile</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Name:</span>
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Role:</span>
                    <span class="font-medium capitalize">{{ auth()->user()->role }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ ucfirst(auth()->user()->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection