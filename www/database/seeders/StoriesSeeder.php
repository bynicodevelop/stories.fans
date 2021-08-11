<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->hasInvitationLinks(1)->create([
            'name' => "Stories.fans",
            'slug' => "storiesfans",
            'email' => "contact@stories.fans",
        ]);
    }
}
