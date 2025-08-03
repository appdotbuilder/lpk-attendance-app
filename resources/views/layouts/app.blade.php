<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">

    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div id="app" class="min-h-screen flex flex-col">
        @if(auth()->check())
            <!-- Mobile-first Navigation Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Mobile menu button -->
                        <button type="button" 
                                class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                                onclick="toggleMobileMenu()">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">🎓</span>
                                </div>
                                <span class="font-semibold text-gray-900 hidden sm:block">Training Center</span>
                            </a>
                        </div>

                        <!-- Desktop Navigation -->
                        <nav class="hidden md:flex space-x-8">
                            <a href="{{ route('dashboard') }}" 
                               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('dashboard') ? 'border-blue-500 text-blue-600' : '' }}">
                                📊 Dashboard
                            </a>
                            <a href="{{ route('attendance.index') }}" 
                               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('attendance.*') ? 'border-blue-500 text-blue-600' : '' }}">
                                ✅ Attendance
                            </a>
                            <a href="{{ route('picket-reports.index') }}" 
                               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('picket-reports.*') ? 'border-blue-500 text-blue-600' : '' }}">
                                📋 Reports
                            </a>
                            <a href="{{ route('chat.index') }}" 
                               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('chat.*') ? 'border-blue-500 text-blue-600' : '' }}">
                                💬 Chat
                            </a>
                        </nav>

                        <!-- User menu -->
                        <div class="relative">
                            <button type="button" 
                                    class="flex items-center max-w-xs bg-white rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    onclick="toggleUserMenu()">
                                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                            </button>
                            
                            <!-- User dropdown menu -->
                            <div id="userMenu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                        <div class="font-medium">{{ auth()->user()->name }}</div>
                                        <div class="text-gray-500">{{ auth()->user()->email }}</div>
                                    </div>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">👤 Profile</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">⚙️ Settings</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            🚪 Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div id="mobileMenu" class="hidden md:hidden">
                    <div class="pt-2 pb-3 space-y-1 bg-white border-t">
                        <a href="{{ route('dashboard') }}" 
                           class="bg-blue-50 border-blue-500 text-blue-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('attendance.index') }}" 
                           class="border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('attendance.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                            ✅ Attendance
                        </a>
                        <a href="{{ route('picket-reports.index') }}" 
                           class="border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('picket-reports.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                            📋 Reports
                        </a>
                        <a href="{{ route('chat.index') }}" 
                           class="border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('chat.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                            💬 Chat
                        </a>
                    </div>
                </div>
            </header>
        @endif

        <!-- Main Content -->
        <main class="flex-1">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-4 mx-4 mt-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-4 mx-4 mt-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Bottom Navigation for Mobile -->
        @if(auth()->check())
            <nav class="md:hidden bg-white border-t border-gray-200 fixed bottom-0 left-0 right-0 z-50">
                <div class="grid grid-cols-4 gap-1">
                    <a href="{{ route('dashboard') }}" 
                       class="flex flex-col items-center justify-center py-2 px-1 text-center {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        </svg>
                        <span class="text-xs mt-1">Dashboard</span>
                    </a>
                    <a href="{{ route('attendance.index') }}" 
                       class="flex flex-col items-center justify-center py-2 px-1 text-center {{ request()->routeIs('attendance.*') ? 'text-blue-600' : 'text-gray-500' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs mt-1">Attendance</span>
                    </a>
                    <a href="{{ route('picket-reports.index') }}" 
                       class="flex flex-col items-center justify-center py-2 px-1 text-center {{ request()->routeIs('picket-reports.*') ? 'text-blue-600' : 'text-gray-500' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-xs mt-1">Reports</span>
                    </a>
                    <a href="{{ route('chat.index') }}" 
                       class="flex flex-col items-center justify-center py-2 px-1 text-center {{ request()->routeIs('chat.*') ? 'text-blue-600' : 'text-gray-500' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="text-xs mt-1">Chat</span>
                    </a>
                </div>
            </nav>
        @endif
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }

        // Close menus when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (!event.target.closest('[onclick="toggleUserMenu()"]') && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
            
            if (!event.target.closest('[onclick="toggleMobileMenu()"]') && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>