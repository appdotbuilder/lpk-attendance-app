<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ClassEnrollment
 *
 * @property int $id
 * @property int $user_id
 * @property int $training_class_id
 * @property \Illuminate\Support\Carbon $enrolled_at
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\TrainingClass $trainingClass
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|ClassEnrollment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassEnrollment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassEnrollment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassEnrollment active()
 * @method static \Database\Factories\ClassEnrollmentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class ClassEnrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'training_class_id',
        'enrolled_at',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enrolled_at' => 'date',
    ];

    /**
     * Get the user for this enrollment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the training class for this enrollment.
     */
    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class);
    }

    /**
     * Scope a query to only include active enrollments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}