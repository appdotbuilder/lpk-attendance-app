import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

export default function CpmiNoClass() {
    return (
        <AppShell>
            <Head title="No Active Class" />
            
            <div className="max-w-2xl mx-auto text-center">
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
                    <div className="text-6xl mb-6">🎓</div>
                    <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        No Active Class Enrollment
                    </h1>
                    <p className="text-gray-600 dark:text-gray-300 mb-6">
                        You are not currently enrolled in any active training class. 
                        Please contact the administration office to get enrolled in a training program.
                    </p>
                    
                    <div className="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                        <h3 className="font-semibold text-blue-800 dark:text-blue-300 mb-2">
                            What's Next?
                        </h3>
                        <ul className="text-sm text-blue-600 dark:text-blue-400 text-left space-y-1">
                            <li>• Contact the administration office</li>
                            <li>• Complete your enrollment documentation</li>
                            <li>• Wait for class assignment notification</li>
                            <li>• Prepare for your Taiwan training journey</li>
                        </ul>
                    </div>

                    <div className="space-y-4">
                        <Link
                            href={route('dashboard')}
                            className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-block"
                        >
                            Refresh Dashboard
                        </Link>
                        
                        <div className="text-sm text-gray-500 dark:text-gray-400">
                            <p>Need help? Contact us:</p>
                            <p className="font-medium">📧 admin@lpkbmp.com</p>
                            <p className="font-medium">📞 +62-21-1234-5678</p>
                        </div>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}