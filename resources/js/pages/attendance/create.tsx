import React, { useState, useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface TrainingClass {
    id: number;
    name: string;
    code: string;
    location: string | null;
}

interface AttendanceRecord {
    id: number;
    date: string;
    status: string;
    check_in_time: string | null;
    check_out_time: string | null;
}

interface Props {
    currentClass: TrainingClass;
    todayAttendance: AttendanceRecord | null;
    [key: string]: unknown;
}

interface LocationData {
    latitude: number | null;
    longitude: number | null;
    accuracy: number | null;
    address: string | null;
    error: string | null;
}

export default function CreateAttendance({ currentClass, todayAttendance }: Props) {
    const [isLoading, setIsLoading] = useState(false);
    const [location, setLocation] = useState<LocationData>({
        latitude: null,
        longitude: null,
        accuracy: null,
        address: null,
        error: null,
    });
    const [photo, setPhoto] = useState<File | null>(null);
    const [photoPreview, setPhotoPreview] = useState<string | null>(null);
    const [notes, setNotes] = useState('');
    const [isGettingLocation, setIsGettingLocation] = useState(false);

    const getCurrentLocation = () => {
        setIsGettingLocation(true);
        setLocation(prev => ({ ...prev, error: null }));

        if (!navigator.geolocation) {
            setLocation(prev => ({ 
                ...prev, 
                error: 'Geolocation is not supported by this browser.' 
            }));
            setIsGettingLocation(false);
            return;
        }

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const { latitude, longitude, accuracy } = position.coords;
                
                try {
                    // Get address from coordinates (using a reverse geocoding service)
                    const response = await fetch(
                        `https://api.opencagedata.com/geocode/v1/json?q=${latitude}+${longitude}&key=YOUR_API_KEY`
                    );
                    
                    let address = `${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
                    if (response.ok) {
                        const data = await response.json();
                        if (data.results && data.results[0]) {
                            address = data.results[0].formatted;
                        }
                    }

                    setLocation({
                        latitude,
                        longitude,
                        accuracy,
                        address,
                        error: null,
                    });
                } catch {
                    setLocation({
                        latitude,
                        longitude,
                        accuracy,
                        address: `${latitude.toFixed(6)}, ${longitude.toFixed(6)}`,
                        error: null,
                    });
                }
                setIsGettingLocation(false);
            },
            (err) => {
                let errorMessage = 'Unable to retrieve location.';
                switch (err.code) {
                    case err.PERMISSION_DENIED:
                        errorMessage = 'Location access denied by user.';
                        break;
                    case err.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information unavailable.';
                        break;
                    case err.TIMEOUT:
                        errorMessage = 'Location request timed out.';
                        break;
                }
                setLocation(prev => ({ ...prev, error: errorMessage }));
                setIsGettingLocation(false);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000, // 5 minutes
            }
        );
    };

    const handlePhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setPhoto(file);
            const reader = new FileReader();
            reader.onload = (e) => {
                setPhotoPreview(e.target?.result as string);
            };
            reader.readAsDataURL(file);
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setIsLoading(true);

        const formData = new FormData();
        if (location.latitude) formData.append('latitude', location.latitude.toString());
        if (location.longitude) formData.append('longitude', location.longitude.toString());
        if (location.address) formData.append('location_address', location.address);
        if (notes) formData.append('notes', notes);
        if (photo) formData.append('photo', photo);

        router.post(route('attendance.store'), formData, {
            onSuccess: () => {
                setIsLoading(false);
            },
            onError: () => {
                setIsLoading(false);
            },
        });
    };

    // Get location on component mount
    useEffect(() => {
        getCurrentLocation();
    }, []);

    const canCheckIn = !todayAttendance || !todayAttendance.check_in_time;
    const currentTime = new Date().toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });

    return (
        <AppShell>
            <Head title="Check In Attendance" />
            
            <div className="max-w-2xl mx-auto space-y-6">
                {/* Header */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div className="text-center">
                        <div className="text-4xl mb-4">✅</div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Check In Attendance
                        </h1>
                        <p className="text-gray-600 dark:text-gray-300">
                            {currentClass.name} • {new Date().toLocaleDateString('id-ID', { 
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}
                        </p>
                        <p className="text-lg font-semibold text-blue-600 dark:text-blue-400 mt-2">
                            {currentTime}
                        </p>
                    </div>
                </div>

                {/* Today's Status */}
                {todayAttendance && (
                    <div className="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div className="flex items-center space-x-3">
                            <div className="text-2xl">ℹ️</div>
                            <div>
                                <h3 className="font-semibold text-blue-800 dark:text-blue-300">
                                    {todayAttendance.check_in_time ? 'Already Checked In' : 'Attendance Record Exists'}
                                </h3>
                                <p className="text-sm text-blue-600 dark:text-blue-400">
                                    {todayAttendance.check_in_time 
                                        ? `Checked in at ${todayAttendance.check_in_time}`
                                        : 'You can still check in for today'
                                    }
                                </p>
                            </div>
                        </div>
                    </div>
                )}

                {canCheckIn ? (
                    <form onSubmit={handleSubmit} className="space-y-6">
                        {/* Location Section */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                📍 Location Verification
                            </h2>
                            
                            <div className="space-y-4">
                                <div className="flex items-center justify-between">
                                    <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Current Location:
                                    </span>
                                    <button
                                        type="button"
                                        onClick={getCurrentLocation}
                                        disabled={isGettingLocation}
                                        className="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                    >
                                        {isGettingLocation ? '🔄 Getting...' : '📍 Get Location'}
                                    </button>
                                </div>

                                {location.error && (
                                    <div className="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                                        <p className="text-sm text-red-600 dark:text-red-400">
                                            ❌ {location.error}
                                        </p>
                                    </div>
                                )}

                                {location.latitude && location.longitude && (
                                    <div className="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                        <div className="space-y-2">
                                            <div className="flex items-center space-x-2">
                                                <span className="text-green-600 dark:text-green-400">✅</span>
                                                <span className="text-sm font-medium text-green-800 dark:text-green-300">
                                                    Location acquired
                                                </span>
                                            </div>
                                            <p className="text-sm text-gray-600 dark:text-gray-300">
                                                <strong>Address:</strong> {location.address}
                                            </p>
                                            <p className="text-xs text-gray-500 dark:text-gray-400">
                                                Coordinates: {location.latitude.toFixed(6)}, {location.longitude.toFixed(6)}
                                                {location.accuracy && ` • Accuracy: ${Math.round(location.accuracy)}m`}
                                            </p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Photo Section */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                📸 Attendance Photo
                            </h2>
                            
                            <div className="space-y-4">
                                <div>
                                    <label 
                                        htmlFor="photo" 
                                        className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >
                                        Take or upload your photo
                                    </label>
                                    <input
                                        type="file"
                                        id="photo"
                                        accept="image/*"
                                        capture="user"
                                        onChange={handlePhotoChange}
                                        className="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-400"
                                    />
                                </div>

                                {photoPreview && (
                                    <div className="mt-4">
                                        <img
                                            src={photoPreview}
                                            alt="Attendance photo preview"
                                            className="w-full max-w-xs mx-auto rounded-lg shadow border"
                                        />
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Notes Section */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                📝 Additional Notes
                            </h2>
                            
                            <textarea
                                value={notes}
                                onChange={(e) => setNotes(e.target.value)}
                                placeholder="Any additional notes or comments... (optional)"
                                rows={3}
                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        {/* Submit Button */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <button
                                type="submit"
                                disabled={isLoading || !location.latitude}
                                className="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white py-4 px-6 rounded-lg font-semibold text-lg transition-colors flex items-center justify-center space-x-2"
                            >
                                {isLoading ? (
                                    <>
                                        <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                        <span>Recording Attendance...</span>
                                    </>
                                ) : (
                                    <>
                                        <span>✅</span>
                                        <span>Check In Now</span>
                                    </>
                                )}
                            </button>
                            
                            {!location.latitude && (
                                <p className="text-sm text-gray-500 dark:text-gray-400 text-center mt-2">
                                    Please allow location access to check in
                                </p>
                            )}
                        </div>
                    </form>
                ) : (
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                        <div className="text-4xl mb-4">✅</div>
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Already Checked In
                        </h2>
                        <p className="text-gray-600 dark:text-gray-300 mb-4">
                            You have already checked in for today at {todayAttendance?.check_in_time}
                        </p>
                        <div className="space-y-3">
                            <button
                                onClick={() => router.visit(route('dashboard'))}
                                className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                            >
                                Back to Dashboard
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </AppShell>
    );
}