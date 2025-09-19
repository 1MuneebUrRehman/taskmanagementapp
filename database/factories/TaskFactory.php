<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $catalog = [
            [
                'title' => 'Fix login issue on website',
                'description' => 'Users cannot log in due to session timeouts. Analyze session config and middleware, then patch.',
                'priority' => 'high',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Prepare monthly sales report',
                'description' => 'Compile data from POS and CRM, verify figures, and share the PDF with management.',
                'priority' => 'normal',
                'status' => 'pending',
            ],
            [
                'title' => 'Database backup',
                'description' => 'Run full backup, verify checksum, and upload to S3 cold storage.',
                'priority' => 'urgent',
                'status' => 'completed',
            ],
            [
                'title' => 'Design landing page',
                'description' => 'Create a responsive landing page for the fall campaign with hero banner and CTA.',
                'priority' => 'high',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Team meeting preparation',
                'description' => 'Draft slides covering roadmap, deliverables, risks, and Q&A.',
                'priority' => 'low',
                'status' => 'pending',
            ],
            [
                'title' => 'Customer onboarding email flow',
                'description' => 'Write a 3-step onboarding email sequence and set it up in the ESP.',
                'priority' => 'normal',
                'status' => 'pending',
            ],
        ];

        $template = $catalog[array_rand($catalog)];

        // Ensure coherent dates
        $start = now()->subDays(random_int(1, 20));
        $due = (clone $start)->addDays(random_int(2, 15));
        $isCompleted = $template['status'] === 'completed';
        $completedAt = $isCompleted ? (clone $start)->addDays(random_int(1, max(1, $start->diffInDays(min($due, now()))))) : null;
        $reminder = !$isCompleted ? (clone $start)->addDays(random_int(1, max(1, $start->diffInDays($due)))) : null;

        // Pick involved users
        $ownerId = User::query()->inRandomOrder()->value('id') ?? User::factory();
        $assigneeId = User::query()->inRandomOrder()->value('id') ?? null;
        $creatorId = $ownerId;
        $updaterId = $assigneeId ?? $ownerId;

        return [
            'user_id' => $ownerId,
            'title' => $template['title'],
            'description' => $template['description'],
            'is_completed' => $isCompleted,
            'start_date' => $start,
            'due_date' => $due,
            'completed_at' => $completedAt,
            'reminder_at' => $reminder,
            'priority' => $template['priority'],
            'status' => $template['status'],
            'assigned_to' => $assigneeId,
            'created_by' => $creatorId,
            'updated_by' => $updaterId,
            'archived' => false,
        ];
    }
}
