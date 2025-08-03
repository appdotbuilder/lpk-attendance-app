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

    <title>🎓 Professional Training Center - Manage Your Training Programs</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-lg">🎓</span>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl text-gray-900">Training Center</h1>
                        <p class="text-xs text-gray-500">Professional Development</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                📊 Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md font-medium">
                                Sign In
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
            <div class="text-center">
                <h1 class="text-4xl sm:text-6xl font-bold text-gray-900 mb-6">
                    🎓 Professional Training
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                        Management System
                    </span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10">
                    Streamline your training operations with comprehensive attendance tracking, 
                    real-time reporting, and seamless communication tools. Built for modern training centers.
                </p>
                
                @if (!auth()->check())
                    <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12">
                        <a href="{{ route('register') }}" 
                           class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg transform hover:scale-105 transition-all">
                            🚀 Start Your Free Trial
                        </a>
                        <a href="{{ route('login') }}" 
                           class="bg-white hover:bg-gray-50 text-gray-900 px-8 py-4 rounded-xl font-semibold text-lg shadow-lg border border-gray-200 transition-colors">
                            📝 Sign In
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    ✨ Everything You Need to Manage Training
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    From attendance tracking to detailed reporting, our platform covers all aspects of training management.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Attendance Management -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">✅</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Smart Attendance Tracking</h3>
                    <p class="text-gray-600">
                        Real-time attendance monitoring with photo verification, GPS location tracking, and automated reports.
                    </p>
                    <div class="mt-4 flex items-center space-x-2">
                        <div class="w-16 h-3 bg-green-200 rounded-full">
                            <div class="w-12 h-3 bg-green-500 rounded-full"></div>
                        </div>
                        <span class="text-sm text-green-600 font-medium">95% accuracy</span>
                    </div>
                </div>

                <!-- Dashboard & Analytics -->
                <div class="bg-gradient-to-br from-blue-50 to-cyan-100 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">📊</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Comprehensive Dashboard</h3>
                    <p class="text-gray-600">
                        Get instant insights with real-time analytics, progress tracking, and performance metrics visualization.
                    </p>
                    <div class="mt-4 flex items-center space-x-2">
                        <div class="w-16 h-3 bg-blue-200 rounded-full">
                            <div class="w-14 h-3 bg-blue-500 rounded-full"></div>
                        </div>
                        <span class="text-sm text-blue-600 font-medium">Real-time data</span>
                    </div>
                </div>

                <!-- Reporting System -->
                <div class="bg-gradient-to-br from-purple-50 to-violet-100 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">📋</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Advanced Reporting</h3>
                    <p class="text-gray-600">
                        Generate detailed picket reports, training summaries, and performance analytics with export capabilities.
                    </p>
                    <div class="mt-4 flex items-center space-x-2">
                        <div class="w-16 h-3 bg-purple-200 rounded-full">
                            <div class="w-13 h-3 bg-purple-500 rounded-full"></div>
                        </div>
                        <span class="text-sm text-purple-600 font-medium">Auto-generated</span>
                    </div>
                </div>

                <!-- Communication Tools -->
                <div class="bg-gradient-to-br from-orange-50 to-amber-100 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">💬</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Real-time Chat</h3>
                    <p class="text-gray-600">
                        Instant messaging between instructors and trainees with file sharing and group discussions.
                    </p>
                    <div class="mt-4 flex items-center space-x-2">
                        <div class="w-16 h-3 bg-orange-200 rounded-full">
                            <div class="w-15 h-3 bg-orange-500 rounded-full"></div>
                        </div>
                        <span class="text-sm text-orange-600 font-medium">Instant delivery</span>
                    </div>
                </div>

                <!-- User Management -->
                <div class="bg-gradient-to-br from-rose-50 to-pink-100 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-rose-500 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">👥</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">User Management</h3>
                    <p class="text-gray-600">
                        Manage trainees, instructors, and administrators with role-based permissions and access control.
                    </p>
                    <div class="mt-4 flex items-center space-x-2">
                        <div class="w-16 h-3 bg-rose-200 rounded-full">
                            <div class="w-14 h-3 bg-rose-500 rounded-full"></div>
                        </div>
                        <span class="text-sm text-rose-600 font-medium">Role-based</span>
                    </div>
                </div>

                <!-- Mobile Responsive -->
                <div class="bg-gradient-to-br from-teal-50 to-cyan-100 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">📱</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Mobile-First Design</h3>
                    <p class="text-gray-600">
                        PWA-enabled mobile experience with offline capabilities and push notifications for seamless access.
                    </p>
                    <div class="mt-4 flex items-center space-x-2">
                        <div class="w-16 h-3 bg-teal-200 rounded-full">
                            <div class="w-full h-3 bg-teal-500 rounded-full"></div>
                        </div>
                        <span class="text-sm text-teal-600 font-medium">PWA Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-16 bg-gradient-to-r from-blue-600 to-indigo-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                    🚀 Trusted by Training Centers Worldwide
                </h2>
                <p class="text-xl text-blue-100">
                    Join thousands of organizations already using our platform
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-blue-100">Training Centers</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">50K+</div>
                    <div class="text-blue-100">Active Users</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">99.9%</div>
                    <div class="text-blue-100">Uptime</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <div class="text-blue-100">Support</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    @if (!auth()->check())
        <div class="py-16 bg-gray-50">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">
                    🎯 Ready to Transform Your Training Management?
                </h2>
                <p class="text-xl text-gray-600 mb-8">
                    Join thousands of training centers that have streamlined their operations with our platform.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-8">
                    <a href="{{ route('register') }}" 
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg transform hover:scale-105 transition-all">
                        🚀 Start Free Trial - No Credit Card Required
                    </a>
                </div>
                <p class="text-sm text-gray-500">
                    ✅ 14-day free trial • ✅ No setup fees • ✅ Cancel anytime
                </p>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg">🎓</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl">Training Center</h3>
                            <p class="text-gray-400 text-sm">Professional Development Platform</p>
                        </div>
                    </div>
                    <p class="text-gray-400 max-w-md">
                        Empowering training centers with modern technology to deliver exceptional learning experiences and streamlined operations.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Features</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>✅ Attendance Tracking</li>
                        <li>📊 Analytics Dashboard</li>
                        <li>📋 Report Generation</li>
                        <li>💬 Real-time Chat</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>📚 Documentation</li>
                        <li>🎥 Video Tutorials</li>
                        <li>💬 Live Chat Support</li>
                        <li>📧 Email Support</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Training Center Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- PWA Install Prompt -->
    <div id="pwa-install-prompt" class="hidden fixed bottom-4 left-4 right-4 bg-white rounded-lg shadow-lg p-4 border border-gray-200 z-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold">🎓</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Install Training Center</p>
                    <p class="text-sm text-gray-600">Get the full app experience</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <button onclick="dismissInstallPrompt()" class="px-3 py-1 text-gray-500 text-sm">
                    Maybe Later
                </button>
                <button onclick="installPWA()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">
                    Install
                </button>
            </div>
        </div>
    </div>

    <script>
        // PWA Installation
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            document.getElementById('pwa-install-prompt').classList.remove('hidden');
        });

        function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    deferredPrompt = null;
                    document.getElementById('pwa-install-prompt').classList.add('hidden');
                });
            }
        }

        function dismissInstallPrompt() {
            document.getElementById('pwa-install-prompt').classList.add('hidden');
        }

        // Service Worker Registration
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
</body>
</html>