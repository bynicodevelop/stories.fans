<?php

namespace Tests\Feature\Livewire\Profile;

use App\Http\Livewire\Profile\InvitedLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class InvitedLinkTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(InvitedLink::class);

        $component->assertStatus(200);
    }
}
