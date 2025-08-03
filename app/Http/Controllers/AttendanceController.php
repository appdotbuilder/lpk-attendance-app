<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\TrainingClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AttendanceController extends Controller
{
    /**
     * Display attendance records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = AttendanceRecord::with(['user', 'trainingClass']);

        if ($user->isCpmi()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isInstructor()) {
            $classIds = $user->instructedClasses()->pluck('id');
            $query->whereIn('training_class_id', $classIds);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendance = $query->latest('date')->paginate(20);

        return view('attendance.index', [
            'attendance' => $attendance,
            'filters' => $request->only(['date', 'status']),
        ]);
    }

    /**
     * Show check-in form.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isCpmi()) {
            return redirect()->route('dashboard');
        }

        $currentClass = $user->getCurrentClass();
        
        if (!$currentClass) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not enrolled in any active class.');
        }

        // Check if already checked in today
        $todayAttendance = AttendanceRecord::where('user_id', $user->id)
            ->where('training_class_id', $currentClass->id)
            ->whereDate('date', today())
            ->first();

        return view('attendance.create', [
            'currentClass' => $currentClass,
            'todayAttendance' => $todayAttendance,
        ]);
    }

    /**
     * Store attendance record.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $user = Auth::user();
        $currentClass = $user->getCurrentClass();

        if (!$currentClass) {
            return redirect()->back()
                ->with('error', 'You are not enrolled in any active class.');
        }

        $validated = $request->validated();

        // Check if already checked in today
        $existingAttendance = AttendanceRecord::where('user_id', $user->id)
            ->where('training_class_id', $currentClass->id)
            ->whereDate('date', today())
            ->first();

        if ($existingAttendance && $existingAttendance->check_in_time) {
            return redirect()->back()
                ->with('error', 'You have already checked in today.');
        }

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance-photos', 'public');
        }

        // Validate location (simplified - in real app, you'd check against training center coordinates)
        $isValidLocation = $this->validateLocation(
            $validated['latitude'] ?? null,
            $validated['longitude'] ?? null
        );

        // Determine status based on time
        $currentTime = now()->format('H:i:s');
        $status = 'present';
        
        // Simple logic: if after 8:30 AM, mark as late
        if ($currentTime > '08:30:00') {
            $status = 'late';
        }

        $attendanceData = [
            'user_id' => $user->id,
            'training_class_id' => $currentClass->id,
            'date' => today(),
            'check_in_time' => now()->format('H:i:s'),
            'status' => $status,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'location_address' => $validated['location_address'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'photo' => $photoPath,
            'is_valid_location' => $isValidLocation,
        ];

        if ($existingAttendance) {
            $existingAttendance->update($attendanceData);
            $attendance = $existingAttendance;
        } else {
            $attendance = AttendanceRecord::create($attendanceData);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Attendance recorded successfully!');
    }

    /**
     * Update attendance record (check-out).
     */
    public function update(Request $request, AttendanceRecord $attendance)
    {
        $user = Auth::user();

        if ($attendance->user_id !== $user->id && !$user->isInstructor() && !$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'check_out_time' => 'nullable|date_format:H:i:s',
            'notes' => 'nullable|string|max:1000',
        ]);

        $attendance->update([
            'check_out_time' => $request->check_out_time ?? now()->format('H:i:s'),
            'notes' => $request->notes,
        ]);

        return redirect()->back()
            ->with('success', 'Check-out recorded successfully!');
    }

    /**
     * Show attendance record details.
     */
    public function show(AttendanceRecord $attendance)
    {
        $user = Auth::user();

        if ($attendance->user_id !== $user->id && !$user->isInstructor() && !$user->isAdmin()) {
            abort(403);
        }

        $attendance->load(['user', 'trainingClass']);

        return view('attendance.show')->with([
            'attendance' => $attendance,
        ]);
    }

    /**
     * Validate location against training center coordinates.
     */
    protected function validateLocation($latitude, $longitude): bool
    {
        if (!$latitude || !$longitude) {
            return false;
        }

        // Training center coordinates (example - replace with actual coordinates)
        $centerLat = -6.2088;  // Jakarta example
        $centerLng = 106.8456;
        $allowedRadius = 0.5; // 500 meters

        // Calculate distance using Haversine formula (simplified)
        $distance = $this->calculateDistance($latitude, $longitude, $centerLat, $centerLng);
        
        return $distance <= $allowedRadius;
    }

    /**
     * Calculate distance between two coordinates in kilometers.
     */
    protected function calculateDistance($lat1, $lng1, $lat2, $lng2): float
    {
        $earthRadius = 6371; // Earth radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}