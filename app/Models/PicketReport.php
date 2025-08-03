<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PicketReport
 *
 * @property int $id
 * @property int $user_id
 * @property int $training_class_id
 * @property \Illuminate\Support\Carbon $date
 * @property string $report
 * @property array|null $photos
 * @property string|null $issues
 * @property string|null $suggestions
 * @property string $status
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property string|null $review_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\TrainingClass $trainingClass
 * @property-read \App\Models\User|null $reviewer
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|PicketReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PicketReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PicketReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|PicketReport submitted()
 * @method static \Illuminate\Database\Eloquent\Builder|PicketReport pending()

 * 
 * @mixin \Eloquent
 */
class PicketReport extends Model
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
        'date',
        'report',
        'photos',
        'issues',
        'suggestions',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'photos' => 'array',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user who submitted this report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the training class for this report.
     */
    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class);
    }

    /**
     * Get the user who reviewed this report.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope a query to only include submitted reports.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope a query to only include pending reports.
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'reviewed']);
    }
}