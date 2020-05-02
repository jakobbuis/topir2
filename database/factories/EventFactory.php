<?php

$factory->define(\App\Event::class, function (\Faker\Generator $faker) {
    return [
        'data' => (object) [
            "event_name" => "item:added",
            "user_id" => 1234,
            "event_data" => (object) [
                "day_order" => $faker->randomNumber,
                "added_by_uid" => $faker->randomNumber,
                "assigned_by_uid" => $faker->randomNumber,
                "labels" => [],
                "sync_id" => null,
                "in_history" => $faker->boolean,
                "has_notifications" => $faker->boolean,
                "parent_id" => null,
                "checked" => $faker->boolean,
                "date_added" => "2014-09-26T08:25:05Z",
                "id" => 33511505,
                "content" => "Task1",
                "user_id" => 1234,
                "due" => null,
                "children" => null,
                "priority" => 1,
                "child_order" => 1,
                "is_deleted" => $faker->boolean,
                "responsible_uid" => null,
                "project_id" => 128501470,
                "collapsed" => $faker->boolean,
            ],
        ],
    ];
});
