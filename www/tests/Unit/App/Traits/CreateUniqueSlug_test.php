<?php

namespace Tests\Unit\App\Traits;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;

class CreateUniqueSlug_test extends TestCase
{
    use DatabaseMigrations;

    public function test_create_new_slug()
    {
        User::factory()->create([
            "name" => "jeff",
            "slug" => "jeff"
        ]);

        $createUser = new CreateNewUser();

        $response = $createUser->getUniqueSlug("john");

        $this->assertEquals($response, "john");
    }

    public function test_create_new_slug_already_exists()
    {
        User::factory()->create([
            "name" => "jeff",
            "slug" => "jeff"
        ]);

        $createUser = new CreateNewUser();

        $response = $createUser->getUniqueSlug("jeff");

        $this->assertEquals($response, "jeff-1");
    }
}
