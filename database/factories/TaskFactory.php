<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $now = now();
        $start = $this->faker->optional(0.7)->dateTimeBetween('-2 months', '+1 week');
        $due = $start ? $this->faker->optional(0.8)->dateTimeBetween($start, '+2 months') : $this->faker->optional(0.6)->dateTimeBetween('-1 month', '+2 months');

        $status = Arr::random(['pending','in_progress','completed','blocked']);
        $isCompleted = $status === 'completed' ? true : $this->faker->boolean(25);
        if ($isCompleted) {
            $status = 'completed';
        }
        $completedAt = $isCompleted ? $this->faker->optional(0.9)->dateTimeBetween('-2 months', $now) : null;

        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraphs(asText: true),
            'is_completed' => $isCompleted,
            'start_date' => $start,
            'due_date' => $due,
            'completed_at' => $completedAt,
            'reminder_at' => $this->faker->optional()->dateTimeBetween('-1 week', '+1 month'),
            'priority' => Arr::random(['low','normal','high','urgent']),
            'status' => $status,
            'project_id' => $this->faker->optional()->numberBetween(1, 10),
            'assigned_to' => function () {
                // 60% chance of assigning to an existing user, else null
                if ($this->faker->boolean(60)) {
                    return User::query()->inRandomOrder()->value('id') ?? User::factory();
                }
                return null;
            },
            'created_by' => function () {
                return User::query()->inRandomOrder()->value('id') ?? User::factory();
            },
            'updated_by' => function (array $attributes) {
                // Often same as created_by
                return $attributes['created_by'] ?? (User::query()->inRandomOrder()->value('id') ?? User::factory());
            },
            'archived' => $this->faker->boolean(10),
            'position' => $this->faker->optional()->numberBetween(1, 500),
        ];
    }
}
