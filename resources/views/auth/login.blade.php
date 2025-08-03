@extends('layouts.guest')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Welcome Back! 👋</h2>
    <p class="text-gray-600 mt-2">Sign in to your training center account</p>
</div>

<!-- Session Status -->
@if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg p-3">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

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
               autofocus 
               autocomplete="username"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('email')
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
               autocomplete="current-password"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember_me" 
                   type="checkbox" 
                   name="remember"
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                Remember me
            </label>
        </div>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" 
               class="text-sm text-blue-600 hover:text-blue-500">
                Forgot password?
            </a>
        @endif
    </div>

    <!-- Submit Button -->
    <button type="submit" 
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
        🚀 Sign In
    </button>
</form>

<!-- Register Link -->
@if (Route::has('register'))
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" 
               class="font-medium text-blue-600 hover:text-blue-500">
                Create one here
            </a>
        </p>
    </div>
@endif

<!-- Demo Accounts -->
<div class="mt-8 p-4 bg-gray-50 rounded-lg">
    <h3 class="text-sm font-medium text-gray-700 mb-3 text-center">🎯 Demo Accounts</h3>
    <div class="grid grid-cols-1 gap-2 text-xs">
        <div class="bg-white p-2 rounded border">
            <p class="font-medium text-gray-900">👨‍💼 Admin</p>
            <p class="text-gray-600">admin@example.com</p>
        </div>
        <div class="bg-white p-2 rounded border">
            <p class="font-medium text-gray-900">👨‍🏫 Instructor</p>
            <p class="text-gray-600">instructor@example.com</p>
        </div>
        <div class="bg-white p-2 rounded border">
            <p class="font-medium text-gray-900">👨‍🎓 CPMI Student</p>
            <p class="text-gray-600">cpmi@example.com</p>
        </div>
        <p class="text-center text-gray-500 mt-2">Password: password</p>
    </div>
</div>
@endsection