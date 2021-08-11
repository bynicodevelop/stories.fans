<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StorySeeder::class
        ]);

        \App\Models\User::factory(50)->has(
            Post::factory()->count(10)->state(function (array $attributes, User $user) {
                if (mt_rand(0, 1) == 0) {
                    return [];
                }

                Plan::factory()->count(1)->create(
                    [
                        'user_id' => $user['id']
                    ]
                );

                return [
                    "is_premium" => rand(0, 1) == 1
                ];
            })
        )->create([
            'parent_id' => 1
        ]);
    }
}
