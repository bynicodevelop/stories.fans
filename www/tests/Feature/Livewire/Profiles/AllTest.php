<?php

namespace Tests\Feature\Livewire\Profiles;

use App\Http\Livewire\Profiles\All;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class AllTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(All::class);

        $component->assertStatus(200);
    }
}
