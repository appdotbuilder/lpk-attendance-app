<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\TrainingClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-30 days', 'now');
        $checkInTime = $this->faker->time('H:i:s', '09:00:00');
        $checkOutTime = $this->faker->optional(0.8)->time('H:i:s', '17:00:00');
        
        // Determine status based on check-in time
        $status = 'present';
        if ($checkInTime > '08:30:00') {
            $status = 'late';
        }
        
        // Random absent status
        if ($this->faker->boolean(10)) { // 10% chance of absent
            $status = 'absent';
            $checkInTime = null;
            $checkOutTime = null;
        }

        return [
            'user_id' => User::factory()->cpmi(),
            'training_class_id' => TrainingClass::factory(),
            'date' => $date,
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'status' => $status,
            'latitude' => $this->faker->latitude(-6.3, -6.1), // Jakarta area
            'longitude' => $this->faker->longitude(106.7, 106.9),
            'location_address' => $this->faker->address(),
            'notes' => $this->faker->optional()->sentence(),
            'photo' => $this->faker->optional(0.3)->imageUrl(400, 300, 'people'),
            'is_valid_location' => $this->faker->boolean(85), // 85% valid locations
        ];
    }

    /**
     * Indicate that the attendance is present.
     */
    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'present',
            'check_in_time' => $this->faker->time('H:i:s', '08:30:00'),
        ]);
    }

    /**
     * Indicate that the attendance is late.
     */
    public function late(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'late',
            'check_in_time' => $this->faker->time('H:i:s', '10:00:00'),
        ]);
    }

    /**
     * Indicate that the attendance is absent.
     */
    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'absent',
            'check_in_time' => null,
            'check_out_time' => null,
            'latitude' => null,
            'longitude' => null,
            'location_address' => null,
            'photo' => null,
        ]);
    }
}