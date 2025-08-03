<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\TrainingClass
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property int|null $instructor_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property int $max_students
 * @property string $status
 * @property array|null $schedule
 * @property string|null $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $instructor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassEnrollment> $enrollments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $students
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AttendanceRecord> $attendanceRecords
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TrainingSchedule> $schedules
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PicketReport> $picketReports
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingClass query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingClass active()
 * @method static \Database\Factories\TrainingClassFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class TrainingClass extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'instructor_id',
        'start_date',
        'end_date',
        'max_students',
        'status',
        'schedule',
        'location',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'schedule' => 'array',
        'max_students' => 'integer',
    ];

    /**
     * Get the instructor for this class.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the enrollments for this class.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    /**
     * Get the students enrolled in this class.
     */
    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, ClassEnrollment::class, 'training_class_id', 'id', 'id', 'user_id')
            ->where('class_enrollments.status', 'active');
    }

    /**
     * Get the attendance records for this class.
     */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Get the training schedules for this class.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(TrainingSchedule::class);
    }

    /**
     * Get the picket reports for this class.
     */
    public function picketReports(): HasMany
    {
        return $this->hasMany(PicketReport::class);
    }

    /**
     * Scope a query to only include active classes.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the current enrollment count.
     */
    public function getCurrentEnrollmentCount(): int
    {
        return $this->enrollments()->where('status', 'active')->count();
    }

    /**
     * Check if class is at capacity.
     */
    public function isAtCapacity(): bool
    {
        return $this->getCurrentEnrollmentCount() >= $this->max_students;
    }
}