import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="LPK Bahana Mega Prestasi - Training Management System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-indigo-900">
                {/* Header */}
                <header className="w-full bg-white/80 backdrop-blur-sm shadow-sm dark:bg-gray-900/80">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center py-4">
                            <div className="flex items-center space-x-2">
                                <div className="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span className="text-white font-bold text-lg">🎓</span>
                                </div>
                                <div>
                                    <h1 className="text-xl font-bold text-gray-900 dark:text-white">LPK Bahana Mega Prestasi</h1>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">Training Management System</p>
                                </div>
                            </div>
                            <nav className="flex items-center space-x-4">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <div className="flex space-x-3">
                                        <Link
                                            href={route('login')}
                                            className="text-gray-700 hover:text-blue-600 px-4 py-2 rounded-lg font-medium transition-colors dark:text-gray-300 dark:hover:text-blue-400"
                                        >
                                            Login
                                        </Link>
                                        <Link
                                            href={route('register')}
                                            className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                                        >
                                            Register
                                        </Link>
                                    </div>
                                )}
                            </nav>
                        </div>
                    </div>
                </header>

                {/* Hero Section */}
                <main className="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
                    <div className="max-w-4xl mx-auto text-center">
                        <div className="mb-8">
                            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                                🇹🇼 Complete Training Management
                                <span className="block text-blue-600 dark:text-blue-400">for Taiwan CPMI Program</span>
                            </h1>
                            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                                Comprehensive attendance tracking, training schedules, real-time communication, and progress monitoring 
                                for Indonesian Migrant Worker Candidates preparing for Taiwan deployment.
                            </p>
                        </div>

                        {/* Feature Grid */}
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                            {/* CPMI Features */}
                            <div className="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-xl p-6 shadow-lg">
                                <div className="text-3xl mb-4">👨‍🎓</div>
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">CPMI Dashboard</h3>
                                <ul className="text-sm text-gray-600 dark:text-gray-300 text-left space-y-1">
                                    <li>📍 GPS-based attendance check-in/out</li>
                                    <li>📅 View training schedules & materials</li>
                                    <li>📊 Personal attendance statistics</li>
                                    <li>📝 Daily picket reports with photos</li>
                                    <li>🔔 Receive notifications & announcements</li>
                                </ul>
                            </div>

                            {/* Instructor Features */}
                            <div className="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-xl p-6 shadow-lg">
                                <div className="text-3xl mb-4">👩‍🏫</div>
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Instructor Tools</h3>
                                <ul className="text-sm text-gray-600 dark:text-gray-300 text-left space-y-1">
                                    <li>📋 Manage class schedules & curricula</li>
                                    <li>✅ Monitor student attendance</li>
                                    <li>📄 Review picket reports</li>
                                    <li>📢 Send class notifications</li>
                                    <li>💬 Chat with students</li>
                                </ul>
                            </div>

                            {/* Admin Features */}
                            <div className="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-xl p-6 shadow-lg md:col-span-2 lg:col-span-1">
                                <div className="text-3xl mb-4">⚙️</div>
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Admin Control</h3>
                                <ul className="text-sm text-gray-600 dark:text-gray-300 text-left space-y-1">
                                    <li>👥 Complete user management</li>
                                    <li>🏫 Training class administration</li>
                                    <li>📈 Comprehensive reporting & analytics</li>
                                    <li>⚙️ Global system settings</li>
                                    <li>📤 Export data & reports</li>
                                </ul>
                            </div>
                        </div>

                        {/* Technology Highlights */}
                        <div className="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-xl p-8 shadow-lg mb-8">
                            <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-6">🚀 Modern Features</h3>
                            <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div className="text-center">
                                    <div className="text-2xl mb-2">📱</div>
                                    <h4 className="font-semibold text-gray-900 dark:text-white mb-1">Mobile-First</h4>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">PWA-like experience</p>
                                </div>
                                <div className="text-center">
                                    <div className="text-2xl mb-2">🌐</div>
                                    <h4 className="font-semibold text-gray-900 dark:text-white mb-1">Real-time</h4>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">Live chat & notifications</p>
                                </div>
                                <div className="text-center">
                                    <div className="text-2xl mb-2">🔒</div>
                                    <h4 className="font-semibold text-gray-900 dark:text-white mb-1">Secure</h4>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">Role-based access</p>
                                </div>
                                <div className="text-center">
                                    <div className="text-2xl mb-2">📊</div>
                                    <h4 className="font-semibold text-gray-900 dark:text-white mb-1">Analytics</h4>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">Detailed reporting</p>
                                </div>
                            </div>
                        </div>

                        {/* CTA Buttons */}
                        {!auth.user && (
                            <div className="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                                <Link
                                    href={route('register')}
                                    className="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg"
                                >
                                    🚀 Get Started - Register Now
                                </Link>
                                <Link
                                    href={route('login')}
                                    className="bg-white/80 hover:bg-white text-gray-900 px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg dark:bg-gray-800/80 dark:hover:bg-gray-800 dark:text-white"
                                >
                                    🔑 Already Have Account? Login
                                </Link>
                            </div>
                        )}

                        {auth.user && (
                            <div className="text-center">
                                <p className="text-lg text-gray-600 dark:text-gray-300 mb-4">
                                    Welcome back, <span className="font-semibold text-blue-600 dark:text-blue-400">{auth.user.name}</span>! 
                                    Ready to continue your training journey?
                                </p>
                                <Link
                                    href={route('dashboard')}
                                    className="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg"
                                >
                                    📊 Go to Dashboard
                                </Link>
                            </div>
                        )}
                    </div>
                </main>

                {/* Footer */}
                <footer className="bg-white/80 backdrop-blur-sm border-t dark:bg-gray-900/80 dark:border-gray-700">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                        <div className="text-center text-sm text-gray-600 dark:text-gray-300">
                            <p>© 2024 LPK Bahana Mega Prestasi. Empowering Indonesian workers for Taiwan opportunities.</p>
                            <p className="mt-2">
                                Built with ❤️ using Laravel & React • 
                                <a 
                                    href="https://app.build" 
                                    target="_blank" 
                                    className="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 ml-1"
                                >
                                    Powered by app.build
                                </a>
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}