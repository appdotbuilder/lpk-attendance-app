@extends('layouts.guest')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Join Training Center! 🎓</h2>
    <p class="text-gray-600 mt-2">Create your account to get started</p>
</div>

<form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            👤 Full Name
        </label>
        <input id="name" 
               type="text" 
               name="name" 
               value="{{ old('name') }}" 
               required 
               autofocus 
               autocomplete="name"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email Address -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            📧 Email Address
        </label>
        <input id="email" 
               type="email" 
               name="email" 
               value="{{ old('email') }}" 
               required 
               autocomplete="username"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Role Selection -->
    <div>
        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
            🎯 Role
        </label>
        <select id="role" 
                name="role" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select your role...</option>
            <option value="cpmi" {{ old('role') === 'cpmi' ? 'selected' : '' }}>👨‍🎓 CPMI Student</option>
            <option value="instructor" {{ old('role') === 'instructor' ? 'selected' : '' }}>👨‍🏫 Instructor</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>👨‍💼 Administrator</option>
        </select>
        @error('role')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Phone (Optional) -->
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
            📱 Phone Number (Optional)
        </label>
        <input id="phone" 
               type="tel" 
               name="phone" 
               value="{{ old('phone') }}" 
               autocomplete="tel"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('phone')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            🔒 Password
        </label>
        <input id="password" 
               type="password" 
               name="password" 
               required 
               autocomplete="new-password"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            🔒 Confirm Password
        </label>
        <input id="password_confirmation" 
               type="password" 
               name="password_confirmation" 
               required 
               autocomplete="new-password"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('password_confirmation')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Terms Agreement -->
    <div class="flex items-center">
        <input id="terms" 
               type="checkbox" 
               name="terms" 
               required
               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
        <label for="terms" class="ml-2 block text-sm text-gray-700">
            I agree to the 
            <a href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</a> 
            and 
            <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>
        </label>
    </div>

    <!-- Submit Button -->
    <button type="submit" 
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
        🚀 Create Account
    </button>
</form>

<!-- Login Link -->
<div class="mt-6 text-center">
    <p class="text-sm text-gray-600">
        Already have an account?
        <a href="{{ route('login') }}" 
           class="font-medium text-blue-600 hover:text-blue-500">
            Sign in here
        </a>
    </p>
</div>

<!-- Features Preview -->
<div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
    <h3 class="text-sm font-medium text-blue-900 mb-3 text-center">✨ What you'll get:</h3>
    <div class="space-y-2 text-xs text-blue-800">
        <div class="flex items-center">
            <span class="mr-2">✅</span>
            <span>Smart attendance tracking with GPS</span>
        </div>
        <div class="flex items-center">
            <span class="mr-2">📊</span>
            <span>Real-time progress dashboard</span>
        </div>
        <div class="flex items-center">
            <span class="mr-2">📋</span>
            <span>Daily report submission</span>
        </div>
        <div class="flex items-center">
            <span class="mr-2">💬</span>
            <span>Real-time chat with instructors</span>
        </div>
        <div class="flex items-center">
            <span class="mr-2">📱</span>
            <span>Mobile-friendly PWA experience</span>
        </div>
    </div>
</div>
@endsection