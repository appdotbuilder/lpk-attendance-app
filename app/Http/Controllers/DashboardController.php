<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\PicketReport;
use App\Models\TrainingSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'instructor':
                return $this->instructorDashboard();
            case 'cpmi':
                return $this->cpmiDashboard();
            default:
                return redirect()->route('home');
        }
    }

    /**
     * Admin dashboard with system overview.
     */
    protected function adminDashboard()
    {
        $stats = [
            'total_cpmi' => User::cpmi()->count(),
            'active_cpmi' => User::cpmi()->active()->count(),
            'total_instructors' => User::instructors()->count(),
            'total_classes' => \App\Models\TrainingClass::count(),
            'active_classes' => \App\Models\TrainingClass::active()->count(),
        ];

        $recentAttendance = AttendanceRecord::with(['user', 'trainingClass'])
            ->latest()
            ->limit(10)
            ->get();

        $pendingReports = PicketReport::submitted()
            ->with(['user', 'trainingClass'])
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('dashboard/admin', [
            'stats' => $stats,
            'recentAttendance' => $recentAttendance,
            'pendingReports' => $pendingReports,
        ]);
    }

    /**
     * Instructor dashboard with class management.
     */
    protected function instructorDashboard()
    {
        $user = Auth::user();
        
        $classes = $user->instructedClasses()
            ->with(['students', 'enrollments'])
            ->get();

        $todaySchedules = TrainingSchedule::whereIn('training_class_id', $classes->pluck('id'))
            ->today()
            ->with('trainingClass')
            ->get();

        $recentReports = PicketReport::whereIn('training_class_id', $classes->pluck('id'))
            ->with(['user', 'trainingClass'])
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('dashboard/instructor', [
            'classes' => $classes,
            'todaySchedules' => $todaySchedules,
            'recentReports' => $recentReports,
        ]);
    }

    /**
     * CPMI dashboard with personal status and activities.
     */
    protected function cpmiDashboard()
    {
        $user = Auth::user();
        $currentClass = $user->getCurrentClass();

        if (!$currentClass) {
            return Inertia::render('dashboard/cpmi-no-class');
        }

        // Today's schedule
        $todaySchedules = TrainingSchedule::where('training_class_id', $currentClass->id)
            ->today()
            ->get();

        // Recent attendance
        $recentAttendance = AttendanceRecord::where('user_id', $user->id)
            ->where('training_class_id', $currentClass->id)
            ->with('trainingClass')
            ->latest()
            ->limit(7)
            ->get();

        // Today's attendance
        $todayAttendance = AttendanceRecord::where('user_id', $user->id)
            ->where('training_class_id', $currentClass->id)
            ->whereDate('date', today())
            ->first();

        // Attendance stats
        $attendanceStats = [
            'present' => AttendanceRecord::where('user_id', $user->id)
                ->where('training_class_id', $currentClass->id)
                ->where('status', 'present')
                ->count(),
            'late' => AttendanceRecord::where('user_id', $user->id)
                ->where('training_class_id', $currentClass->id)
                ->where('status', 'late')
                ->count(),
            'absent' => AttendanceRecord::where('user_id', $user->id)
                ->where('training_class_id', $currentClass->id)
                ->where('status', 'absent')
                ->count(),
        ];

        return Inertia::render('dashboard/cpmi', [
            'currentClass' => $currentClass,
            'todaySchedules' => $todaySchedules,
            'recentAttendance' => $recentAttendance,
            'todayAttendance' => $todayAttendance,
            'attendanceStats' => $attendanceStats,
        ]);
    }
}