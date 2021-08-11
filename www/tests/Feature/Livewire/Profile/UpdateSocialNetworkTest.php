<?php

namespace Tests\Feature\Livewire\Profile;

use App\Http\Livewire\Profile\UpdateSocialNetwork;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateSocialNetworkTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(UpdateSocialNetwork::class);

        $component->assertStatus(200);
    }
}
