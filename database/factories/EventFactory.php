<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition()
    {
        return [
            'data' => (object) [
                'event_name' => 'item:added',
                'user_id' => 1234,
                'event_data' => (object) [
                    'day_order' => $this->faker->randomNumber(),
                    'added_by_uid' => $this->faker->randomNumber(),
                    'assigned_by_uid' => $this->faker->randomNumber(),
                    'labels' => [],
                    'sync_id' => null,
                    'in_history' => $this->faker->boolean(),
                    'has_notifications' => $this->faker->boolean(),
                    'parent_id' => null,
                    'checked' => $this->faker->boolean(),
                    'date_added' => '2014-09-26T08:25:05Z',
                    'id' => 33511505,
                    'content' => 'Task1',
                    'user_id' => 1234,
                    'due' => null,
                    'children' => null,
                    'priority' => 1,
                    'child_order' => 1,
                    'is_deleted' => $this->faker->boolean(),
                    'responsible_uid' => null,
                    'project_id' => 128501470,
                    'collapsed' => $this->faker->boolean(),
                ],
            ],n
        ];
    }
}
