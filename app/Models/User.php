<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $phone
 * @property string|null $nik
 * @property string|null $birth_place
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property string|null $profile_photo
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $last_seen
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, AttendanceRecord> $attendanceRecords
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ClassEnrollment> $enrollments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TrainingClass> $instructedClasses
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PicketReport> $picketReports
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ChatMessage> $chatMessages
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \Illuminate\Database\Eloquent\Builder|User cpmi()
 * @method static \Illuminate\Database\Eloquent\Builder|User instructors()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'profile_photo',
        'status',
        'last_seen',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'last_seen' => 'datetime',
        ];
    }

    /**
     * Get the classes that this user instructs.
     */
    public function instructedClasses(): HasMany
    {
        return $this->hasMany(TrainingClass::class, 'instructor_id');
    }

    /**
     * Get the class enrollments for this user.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    /**
     * Get the attendance records for this user.
     */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Get the picket reports submitted by this user.
     */
    public function picketReports(): HasMany
    {
        return $this->hasMany(PicketReport::class);
    }

    /**
     * Get the chat messages sent by this user.
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include CPMI users.
     */
    public function scopeCpmi($query)
    {
        return $query->where('role', 'cpmi');
    }

    /**
     * Scope a query to only include instructors.
     */
    public function scopeInstructors($query)
    {
        return $query->where('role', 'instructor');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is instructor.
     */
    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    /**
     * Check if user is CPMI.
     */
    public function isCpmi(): bool
    {
        return $this->role === 'cpmi';
    }

    /**
     * Get user's current active class enrollment.
     */
    public function getCurrentClass(): ?TrainingClass
    {
        /** @var ClassEnrollment|null $enrollment */
        $enrollment = $this->enrollments()
            ->where('status', 'active')
            ->with('trainingClass')
            ->first();
            
        return $enrollment?->trainingClass;
    }
}