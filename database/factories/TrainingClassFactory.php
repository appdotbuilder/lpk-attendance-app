<?php

namespace Database\Factories;

use App\Models\TrainingClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingClass>
 */
class TrainingClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-2 months', '+1 month');
        $endDate = (clone $startDate)->modify('+3 months');

        return [
            'name' => 'Batch ' . $this->faker->year . '-' . str_pad((string)$this->faker->numberBetween(1, 12), 2, '0', STR_PAD_LEFT),
            'code' => 'TW-' . $this->faker->unique()->numerify('####'),
            'description' => 'Training program for Indonesian Migrant Worker Candidates going to Taiwan. Comprehensive preparation including language, culture, and workplace skills.',
            'instructor_id' => User::factory()->instructor(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_students' => $this->faker->numberBetween(20, 40),
            'status' => $this->faker->randomElement(['planned', 'active', 'completed']),
            'schedule' => [
                'monday' => ['start' => '08:00', 'end' => '16:00'],
                'tuesday' => ['start' => '08:00', 'end' => '16:00'],
                'wednesday' => ['start' => '08:00', 'end' => '16:00'],
                'thursday' => ['start' => '08:00', 'end' => '16:00'],
                'friday' => ['start' => '08:00', 'end' => '15:00'],
            ],
            'location' => 'LPK Bahana Mega Prestasi Training Center, Jakarta',
        ];
    }

    /**
     * Indicate that the class is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'start_date' => now()->subDays(30),
            'end_date' => now()->addDays(60),
        ]);
    }

    /**
     * Indicate that the class is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'start_date' => now()->subDays(120),
            'end_date' => now()->subDays(30),
        ]);
    }
}