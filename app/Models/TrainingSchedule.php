<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\TrainingSchedule
 *
 * @property int $id
 * @property int $training_class_id
 * @property string $subject
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $date
 * @property string $start_time
 * @property string $end_time
 * @property string|null $room
 * @property string|null $materials
 * @property string $type
 * @property bool $is_mandatory
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TrainingClass $trainingClass
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSchedule upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSchedule today()

 * 
 * @mixin \Eloquent
 */
class TrainingSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'training_class_id',
        'subject',
        'description',
        'date',
        'start_time',
        'end_time',
        'room',
        'materials',
        'type',
        'is_mandatory',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'is_mandatory' => 'boolean',
    ];

    /**
     * Get the training class for this schedule.
     */
    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class);
    }

    /**
     * Scope a query to only include upcoming schedules.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }

    /**
     * Scope a query to only include today's schedules.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', now()->toDateString());
    }
}