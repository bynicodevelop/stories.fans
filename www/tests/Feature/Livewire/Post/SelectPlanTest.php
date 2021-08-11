<?php

namespace Tests\Feature\Livewire\Post;

use App\Http\Livewire\Post\SelectPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SelectPlanTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(SelectPlan::class);

        $component->assertStatus(200);
    }
}
