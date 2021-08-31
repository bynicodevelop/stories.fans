<?php

namespace Tests\Unit\App\Traits;

use App\Models\User;
use App\Traits\CreateUniqueSlug;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateUniqueSlugTest extends TestCase
{
    use DatabaseMigrations;
    use CreateUniqueSlug;

    public function test_create_new_slug()
    {
        User::factory()->create([
            "name" => "jeff",
            "slug" => "jeff"
        ]);

        $response = $this->getUniqueSlug("john");

        $this->assertEquals($response, "john");
    }

    public function test_create_new_slug_already_exists()
    {
        User::factory()->create([
            "name" => "jeff",
            "slug" => "jeff"
        ]);

        $response = $this->getUniqueSlug("jeff");

        $this->assertEquals($response, "jeff-1");
    }
}
