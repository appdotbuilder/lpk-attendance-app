import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface TrainingClass {
    id: number;
    name: string;
    code: string;
}

interface PicketReport {
    id: number;
    date: string;
    report: string;
    status: string;
}

interface Props {
    currentClass: TrainingClass;
    todayReport: PicketReport | null;
    [key: string]: unknown;
}

export default function CreatePicketReport({ currentClass, todayReport }: Props) {
    const [isLoading, setIsLoading] = useState(false);
    const [report, setReport] = useState('');
    const [issues, setIssues] = useState('');
    const [suggestions, setSuggestions] = useState('');
    const [photos, setPhotos] = useState<File[]>([]);
    const [photoPreviews, setPhotoPreviews] = useState<string[]>([]);

    const handlePhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = Array.from(e.target.files || []);
        
        if (photos.length + files.length > 5) {
            alert('Maximum 5 photos allowed');
            return;
        }

        setPhotos(prev => [...prev, ...files]);

        // Create previews
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                setPhotoPreviews(prev => [...prev, e.target?.result as string]);
            };
            reader.readAsDataURL(file);
        });
    };

    const removePhoto = (index: number) => {
        setPhotos(prev => prev.filter((_, i) => i !== index));
        setPhotoPreviews(prev => prev.filter((_, i) => i !== index));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        if (report.length < 50) {
            alert('Report must be at least 50 characters long');
            return;
        }

        setIsLoading(true);

        const formData = new FormData();
        formData.append('report', report);
        if (issues) formData.append('issues', issues);
        if (suggestions) formData.append('suggestions', suggestions);
        
        photos.forEach(photo => {
            formData.append('photos[]', photo);
        });

        router.post(route('picket-reports.store'), formData, {
            onSuccess: () => {
                setIsLoading(false);
            },
            onError: () => {
                setIsLoading(false);
            },
        });
    };

    if (todayReport) {
        return (
            <AppShell>
                <Head title="Daily Picket Report" />
                
                <div className="max-w-2xl mx-auto">
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                        <div className="text-4xl mb-4">📝</div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Report Already Submitted
                        </h1>
                        <p className="text-gray-600 dark:text-gray-300 mb-4">
                            You have already submitted your daily picket report for today.
                        </p>
                        <div className="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                            <div className="text-left">
                                <p className="text-sm text-blue-800 dark:text-blue-300 mb-2">
                                    <strong>Status:</strong> {todayReport.status.charAt(0).toUpperCase() + todayReport.status.slice(1)}
                                </p>
                                <p className="text-sm text-blue-600 dark:text-blue-400">
                                    <strong>Report:</strong> {todayReport.report.substring(0, 150)}...
                                </p>
                            </div>
                        </div>
                        <div className="space-y-3">
                            <button
                                onClick={() => router.visit(route('picket-reports.show', todayReport.id))}
                                className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors mr-3"
                            >
                                View Report
                            </button>
                            <button
                                onClick={() => router.visit(route('dashboard'))}
                                className="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                            >
                                Back to Dashboard
                            </button>
                        </div>
                    </div>
                </div>
            </AppShell>
        );
    }

    return (
        <AppShell>
            <Head title="Submit Daily Picket Report" />
            
            <div className="max-w-3xl mx-auto space-y-6">
                {/* Header */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div className="text-center">
                        <div className="text-4xl mb-4">📝</div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Daily Picket Report
                        </h1>
                        <p className="text-gray-600 dark:text-gray-300">
                            {currentClass.name} • {new Date().toLocaleDateString('id-ID', { 
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}
                        </p>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* Main Report */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            📋 Daily Activity Report
                        </h2>
                        
                        <div className="space-y-4">
                            <div>
                                <label 
                                    htmlFor="report" 
                                    className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Describe today's activities, what you learned, and your observations *
                                </label>
                                <textarea
                                    id="report"
                                    value={report}
                                    onChange={(e) => setReport(e.target.value)}
                                    placeholder="Today I participated in the following activities:
- Morning orientation session about Taiwan workplace culture
- Practical training on safety procedures
- Language practice with Indonesian-Mandarin vocabulary
- Group discussion about cultural adaptation

Key learnings:
- Importance of following safety protocols
- Basic Mandarin phrases for daily communication
- Understanding of Taiwan work environment expectations

Observations:
- All classmates were actively participating
- Training equipment was in good condition
- Clear explanations from instructors"
                                    rows={12}
                                    required
                                    minLength={50}
                                    maxLength={2000}
                                    className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                />
                                <div className="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <span>Minimum 50 characters required</span>
                                    <span>{report.length}/2000</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Issues Section */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            ⚠️ Issues or Problems (Optional)
                        </h2>
                        
                        <div>
                            <label 
                                htmlFor="issues" 
                                className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Report any issues, problems, or concerns encountered today
                            </label>
                            <textarea
                                id="issues"
                                value={issues}
                                onChange={(e) => setIssues(e.target.value)}
                                placeholder="Examples:
- Classroom air conditioning not working properly
- Some training materials were not available
- Difficulty understanding certain instructions
- Equipment malfunction
- Safety concerns
- Transportation issues

If no issues, you can leave this blank."
                                rows={5}
                                maxLength={1000}
                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            />
                            <div className="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {issues.length}/1000
                            </div>
                        </div>
                    </div>

                    {/* Suggestions Section */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            💡 Suggestions for Improvement (Optional)
                        </h2>
                        
                        <div>
                            <label 
                                htmlFor="suggestions" 
                                className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Share your ideas to improve the training program
                            </label>
                            <textarea
                                id="suggestions"
                                value={suggestions}
                                onChange={(e) => setSuggestions(e.target.value)}
                                placeholder="Examples:
- More practical exercises would be helpful
- Additional language practice sessions
- Field trips to relevant workplaces
- Guest speakers from Taiwan
- Better training materials or equipment
- Extended break times
- More interactive learning methods

Your feedback helps improve the program for everyone!"
                                rows={5}
                                maxLength={1000}
                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            />
                            <div className="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {suggestions.length}/1000
                            </div>
                        </div>
                    </div>

                    {/* Photos Section */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            📸 Photos (Optional - Maximum 5)
                        </h2>
                        
                        <div className="space-y-4">
                            <div>
                                <label 
                                    htmlFor="photos" 
                                    className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Upload photos from today's activities
                                </label>
                                <input
                                    type="file"
                                    id="photos"
                                    accept="image/*"
                                    multiple
                                    onChange={handlePhotoChange}
                                    className="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-400"
                                />
                                <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Maximum 5 photos, 2MB each. JPEG, PNG, JPG formats supported.
                                </p>
                            </div>

                            {photoPreviews.length > 0 && (
                                <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    {photoPreviews.map((preview, index) => (
                                        <div key={index} className="relative">
                                            <img
                                                src={preview}
                                                alt={`Preview ${index + 1}`}
                                                className="w-full h-24 object-cover rounded-lg border"
                                            />
                                            <button
                                                type="button"
                                                onClick={() => removePhoto(index)}
                                                className="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                                            >
                                                ×
                                            </button>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Submit Button */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <button
                            type="submit"
                            disabled={isLoading || report.length < 50}
                            className="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white py-4 px-6 rounded-lg font-semibold text-lg transition-colors flex items-center justify-center space-x-2"
                        >
                            {isLoading ? (
                                <>
                                    <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                    <span>Submitting Report...</span>
                                </>
                            ) : (
                                <>
                                    <span>📝</span>
                                    <span>Submit Daily Report</span>
                                </>
                            )}
                        </button>
                        
                        {report.length < 50 && (
                            <p className="text-sm text-gray-500 dark:text-gray-400 text-center mt-2">
                                Please write at least 50 characters in your daily report
                            </p>
                        )}
                    </div>
                </form>
            </div>
        </AppShell>
    );
}