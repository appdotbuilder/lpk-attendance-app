<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePicketReportRequest;
use App\Models\PicketReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PicketReportController extends Controller
{
    /**
     * Display picket reports.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PicketReport::with(['user', 'trainingClass', 'reviewer']);

        if ($user->isCpmi()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isInstructor()) {
            $classIds = $user->instructedClasses()->pluck('id');
            $query->whereIn('training_class_id', $classIds);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $reports = $query->latest('date')->paginate(20);

        return view('picket-reports.index', [
            'reports' => $reports,
            'filters' => $request->only(['status', 'date']),
        ]);
    }

    /**
     * Show the form for creating a new report.
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

        // Check if report already exists for today
        $todayReport = PicketReport::where('user_id', $user->id)
            ->where('training_class_id', $currentClass->id)
            ->whereDate('date', today())
            ->first();

        return view('picket-reports.create', [
            'currentClass' => $currentClass,
            'todayReport' => $todayReport,
        ]);
    }

    /**
     * Store a newly created report.
     */
    public function store(StorePicketReportRequest $request)
    {
        $user = Auth::user();
        $currentClass = $user->getCurrentClass();

        if (!$currentClass) {
            return redirect()->back()
                ->with('error', 'You are not enrolled in any active class.');
        }

        $validated = $request->validated();

        // Check if report already exists for today
        $existingReport = PicketReport::where('user_id', $user->id)
            ->where('training_class_id', $currentClass->id)
            ->whereDate('date', today())
            ->first();

        if ($existingReport) {
            return redirect()->back()
                ->with('error', 'You have already submitted a report for today.');
        }

        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('picket-photos', 'public');
                $photos[] = $path;
            }
        }

        PicketReport::create([
            'user_id' => $user->id,
            'training_class_id' => $currentClass->id,
            'date' => today(),
            'report' => $validated['report'],
            'photos' => $photos,
            'issues' => $validated['issues'] ?? null,
            'suggestions' => $validated['suggestions'] ?? null,
            'status' => 'submitted',
        ]);

        return redirect()->route('picket-reports.index')
            ->with('success', 'Picket report submitted successfully!');
    }

    /**
     * Display the specified report.
     */
    public function show(PicketReport $picketReport)
    {
        $user = Auth::user();

        if ($picketReport->user_id !== $user->id && !$user->isInstructor() && !$user->isAdmin()) {
            abort(403);
        }

        $picketReport->load(['user', 'trainingClass', 'reviewer']);

        return view('picket-reports.show')->with([
            'report' => $picketReport,
        ]);
    }

    /**
     * Show the form for editing the specified report.
     */
    public function edit(PicketReport $picketReport)
    {
        $user = Auth::user();

        if ($picketReport->user_id !== $user->id || $picketReport->status !== 'submitted') {
            abort(403);
        }

        $picketReport->load(['trainingClass']);

        return view('picket-reports.edit')->with([
            'report' => $picketReport,
        ]);
    }

    /**
     * Update the specified report.
     */
    public function update(StorePicketReportRequest $request, PicketReport $picketReport)
    {
        $user = Auth::user();

        if ($picketReport->user_id !== $user->id || $picketReport->status !== 'submitted') {
            abort(403);
        }

        $validated = $request->validated();

        // Handle photo uploads
        $photos = $picketReport->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('picket-photos', 'public');
                $photos[] = $path;
            }
        }

        $picketReport->update([
            'report' => $validated['report'],
            'photos' => $photos,
            'issues' => $validated['issues'] ?? null,
            'suggestions' => $validated['suggestions'] ?? null,
        ]);

        return redirect()->route('picket-reports.show', $picketReport)
            ->with('success', 'Report updated successfully!');
    }


}