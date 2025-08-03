import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface AttendanceRecord {
    id: number;
    date: string;
    status: string;
    check_in_time: string | null;
    check_out_time: string | null;
    is_valid_location: boolean;
}

interface TrainingSchedule {
    id: number;
    subject: string;
    description: string | null;
    start_time: string;
    end_time: string;
    room: string | null;
    type: string;
    is_mandatory: boolean;
}

interface TrainingClass {
    id: number;
    name: string;
    code: string;
    description: string | null;
    status: string;
    instructor: {
        name: string;
    } | null;
}

interface AttendanceStats {
    present: number;
    late: number;
    absent: number;
}

interface Props {
    currentClass: TrainingClass;
    todaySchedules: TrainingSchedule[];
    recentAttendance: AttendanceRecord[];
    todayAttendance: AttendanceRecord | null;
    attendanceStats: AttendanceStats;
    [key: string]: unknown;
}

export default function CpmiDashboard({ 
    currentClass, 
    todaySchedules, 
    recentAttendance, 
    todayAttendance, 
    attendanceStats 
}: Props) {
    const getStatusBadge = (status: string) => {
        const statusColors = {
            present: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            late: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            absent: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            excused: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            sick: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        };

        return (
            <span className={`px-2 py-1 text-xs font-medium rounded-full ${statusColors[status as keyof typeof statusColors] || 'bg-gray-100 text-gray-800'}`}>
                {status.charAt(0).toUpperCase() + status.slice(1)}
            </span>
        );
    };

    const getTypeIcon = (type: string) => {
        const icons = {
            theory: '📚',
            practical: '🔧',
            exam: '📝',
            field_trip: '🚌',
            orientation: '🎯',
        };
        return icons[type as keyof typeof icons] || '📋';
    };

    const totalAttendance = attendanceStats.present + attendanceStats.late + attendanceStats.absent;
    const attendancePercentage = totalAttendance > 0 ? Math.round((attendanceStats.present / totalAttendance) * 100) : 0;

    return (
        <AppShell>
            <Head title="CPMI Dashboard" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div className="flex items-center justify-between mb-4">
                        <div>
                            <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
                                👨‍🎓 CPMI Dashboard
                            </h1>
                            <p className="text-gray-600 dark:text-gray-300">
                                Welcome to your training management system
                            </p>
                        </div>
                        <div className="text-right">
                            <p className="text-sm text-gray-600 dark:text-gray-300">Current Class</p>
                            <p className="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                {currentClass.name}
                            </p>
                        </div>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <Link
                        href={route('attendance.create')}
                        className="bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 transition-colors"
                    >
                        <div className="flex items-center space-x-3">
                            <div className="text-2xl">✅</div>
                            <div>
                                <h3 className="font-semibold text-green-800 dark:text-green-300">Check In</h3>
                                <p className="text-sm text-green-600 dark:text-green-400">Record attendance</p>
                            </div>
                        </div>
                    </Link>

                    <Link
                        href={route('picket-reports.create')}
                        className="bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4 transition-colors"
                    >
                        <div className="flex items-center space-x-3">
                            <div className="text-2xl">📝</div>
                            <div>
                                <h3 className="font-semibold text-blue-800 dark:text-blue-300">Daily Report</h3>
                                <p className="text-sm text-blue-600 dark:text-blue-400">Submit picket report</p>
                            </div>
                        </div>
                    </Link>

                    <Link
                        href={route('attendance.index')}
                        className="bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-900/30 border border-purple-200 dark:border-purple-800 rounded-lg p-4 transition-colors"
                    >
                        <div className="flex items-center space-x-3">
                            <div className="text-2xl">📊</div>
                            <div>
                                <h3 className="font-semibold text-purple-800 dark:text-purple-300">History</h3>
                                <p className="text-sm text-purple-600 dark:text-purple-400">View attendance</p>
                            </div>
                        </div>
                    </Link>

                    <div className="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                        <div className="flex items-center space-x-3">
                            <div className="text-2xl">💬</div>
                            <div>
                                <h3 className="font-semibold text-orange-800 dark:text-orange-300">Chat</h3>
                                <p className="text-sm text-orange-600 dark:text-orange-400">Coming soon</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Today's Status */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        📅 Today's Status - {new Date().toLocaleDateString('id-ID', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        })}
                    </h2>
                    
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {/* Attendance Status */}
                        <div className="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 className="font-medium text-gray-900 dark:text-white mb-3">Attendance Today</h3>
                            {todayAttendance ? (
                                <div className="space-y-2">
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-gray-600 dark:text-gray-300">Status:</span>
                                        {getStatusBadge(todayAttendance.status)}
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-gray-600 dark:text-gray-300">Check In:</span>
                                        <span className="text-sm font-medium text-gray-900 dark:text-white">
                                            {todayAttendance.check_in_time || 'Not checked in'}
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-gray-600 dark:text-gray-300">Location Valid:</span>
                                        <span className={`text-sm font-medium ${todayAttendance.is_valid_location ? 'text-green-600' : 'text-red-600'}`}>
                                            {todayAttendance.is_valid_location ? '✅ Valid' : '❌ Invalid'}
                                        </span>
                                    </div>
                                </div>
                            ) : (
                                <div className="text-center py-4">
                                    <div className="text-3xl mb-2">⏰</div>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">No attendance recorded yet</p>
                                    <Link
                                        href={route('attendance.create')}
                                        className="mt-2 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                    >
                                        Check In Now
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Today's Schedule */}
                        <div className="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 className="font-medium text-gray-900 dark:text-white mb-3">Today's Schedule</h3>
                            {todaySchedules.length > 0 ? (
                                <div className="space-y-3">
                                    {todaySchedules.map((schedule) => (
                                        <div key={schedule.id} className="bg-white dark:bg-gray-600 rounded-lg p-3">
                                            <div className="flex items-start space-x-3">
                                                <div className="text-lg">{getTypeIcon(schedule.type)}</div>
                                                <div className="flex-1 min-w-0">
                                                    <p className="font-medium text-gray-900 dark:text-white text-sm">
                                                        {schedule.subject}
                                                    </p>
                                                    <p className="text-xs text-gray-600 dark:text-gray-300">
                                                        {schedule.start_time} - {schedule.end_time}
                                                        {schedule.room && ` • ${schedule.room}`}
                                                    </p>
                                                    {schedule.is_mandatory && (
                                                        <span className="inline-block mt-1 px-2 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded dark:bg-red-900 dark:text-red-300">
                                                            Mandatory
                                                        </span>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-4">
                                    <div className="text-3xl mb-2">📅</div>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">No classes scheduled today</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Attendance Statistics */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">📊 Attendance Statistics</h2>
                    
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div className="text-center">
                            <div className="text-2xl font-bold text-green-600 dark:text-green-400">{attendanceStats.present}</div>
                            <div className="text-sm text-gray-600 dark:text-gray-300">Present</div>
                        </div>
                        <div className="text-center">
                            <div className="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{attendanceStats.late}</div>
                            <div className="text-sm text-gray-600 dark:text-gray-300">Late</div>
                        </div>
                        <div className="text-center">
                            <div className="text-2xl font-bold text-red-600 dark:text-red-400">{attendanceStats.absent}</div>
                            <div className="text-sm text-gray-600 dark:text-gray-300">Absent</div>
                        </div>
                        <div className="text-center">
                            <div className="text-2xl font-bold text-blue-600 dark:text-blue-400">{attendancePercentage}%</div>
                            <div className="text-sm text-gray-600 dark:text-gray-300">Attendance Rate</div>
                        </div>
                    </div>

                    {/* Progress Bar */}
                    <div className="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                        <div 
                            className="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                            style={{ width: `${attendancePercentage}%` }}
                        ></div>
                    </div>
                </div>

                {/* Recent Attendance */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div className="flex items-center justify-between mb-4">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white">📋 Recent Attendance</h2>
                        <Link
                            href={route('attendance.index')}
                            className="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium"
                        >
                            View All →
                        </Link>
                    </div>
                    
                    {recentAttendance.length > 0 ? (
                        <div className="space-y-3">
                            {recentAttendance.map((record) => (
                                <div key={record.id} className="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                    <div className="flex items-center space-x-3">
                                        <div className="text-sm font-medium text-gray-900 dark:text-white">
                                            {new Date(record.date).toLocaleDateString('id-ID')}
                                        </div>
                                        {getStatusBadge(record.status)}
                                    </div>
                                    <div className="text-right">
                                        <div className="text-sm text-gray-600 dark:text-gray-300">
                                            {record.check_in_time ? `In: ${record.check_in_time}` : 'No check-in'}
                                        </div>
                                        {record.check_out_time && (
                                            <div className="text-sm text-gray-600 dark:text-gray-300">
                                                Out: {record.check_out_time}
                                            </div>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-8">
                            <div className="text-4xl mb-4">📅</div>
                            <p className="text-gray-600 dark:text-gray-300">No attendance records yet</p>
                            <p className="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                Start checking in to see your attendance history
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </AppShell>
    );
}