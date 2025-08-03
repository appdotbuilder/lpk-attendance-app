<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PicketReportController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard routes
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Attendance routes
    Route::resource('attendance', AttendanceController::class)->except(['edit', 'destroy']);
    
    // Picket report routes
    Route::resource('picket-reports', PicketReportController::class);
    
    // Chat routes
    Route::resource('chat', ChatController::class)->only(['index', 'store', 'show']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
